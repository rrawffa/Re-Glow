<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommunityController extends Controller
{
    // Show blade view (main page)
    public function index()
    {
        return view('community.index');
    }

    // API: get posts, excluding those reported by current user
    public function fetchPosts(Request $request)
    {
        $userId = $request->user()->id;
        // select posts not reported by this user
        $reportedIds = Report::where('reporter_id', $userId)->pluck('post_id')->toArray();
        $posts = Post::whereNotIn('id', $reportedIds)
                     ->orderBy('created_at', 'desc')
                     ->get();

        // format createdAt in ms for frontend convenience
        $posts->transform(function($p){
            return [
                'id' => $p->id,
                'owner_id' => $p->owner_id,
                'user_name' => $p->user_name,
                'body' => $p->body,
                'image_path' => $p->image_path ? asset('storage/'.$p->image_path) : null,
                'createdAt' => $p->created_at->getTimestampMs(),
            ];
        });

        return response()->json(['success'=>true, 'posts'=>$posts]);
    }

    // API: create post (multipart)
    public function store(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'body' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        if($validator->fails()){
            return response()->json(['success'=>false,'errors'=>$validator->errors()], 422);
        }

        $imagePath = null;
        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('uploads', 'public'); // stored in storage/app/public/uploads
        }

        $post = Post::create([
            'owner_id' => $user->id,
            'user_name' => $user->name ?? 'username_',
            'body' => $request->input('body'),
            'image_path' => $imagePath
        ]);

        return response()->json([
            'success'=>true,
            'post' => [
                'id'=>$post->id,
                'owner_id'=>$post->owner_id,
                'user_name'=>$post->user_name,
                'body'=>$post->body,
                'image_path'=>$post->image_path ? asset('storage/'.$post->image_path) : null,
                'createdAt' => $post->created_at->getTimestampMs()
            ]
        ]);
    }

    // API: edit post (owner only, within 24 hours)
    public function update(Request $request, Post $post)
    {
        $user = $request->user();
        if($post->owner_id !== $user->id){
            return response()->json(['success'=>false,'error'=>'not_owner'],403);
        }
        $hours = now()->diffInHours($post->created_at);
        if($hours > 24){
            return response()->json(['success'=>false,'error'=>'edit_window_expired'],403);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required|string|max:2000'
        ]);
        if($validator->fails()){
            return response()->json(['success'=>false,'errors'=>$validator->errors()],422);
        }

        $post->body = $request->input('body');
        $post->save();

        return response()->json(['success'=>true]);
    }

    // API: delete post (owner only)
    public function destroy(Request $request, Post $post)
    {
        $user = $request->user();
        if($post->owner_id !== $user->id){
            return response()->json(['success'=>false,'error'=>'not_owner'],403);
        }

        // delete image file if any
        if($post->image_path){
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return response()->json(['success'=>true]);
    }

    // API: report post (any user can report; store record so reported post hides for reporter)
    public function report(Request $request, Post $post)
    {
        $user = $request->user();
        // prevent duplicate
        $exists = Report::where('post_id', $post->id)->where('reporter_id', $user->id)->exists();
        if($exists){
            return response()->json(['success'=>true, 'already_reported'=>true]);
        }

        Report::create([
            'post_id' => $post->id,
            'reporter_id' => $user->id
        ]);

        return response()->json(['success'=>true]);
    }
}
