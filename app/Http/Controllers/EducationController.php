<?php
// app/Http/Controllers/EducationController.php
namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\ReaksiKonten;
use App\Models\StatistikEdukasi;
use App\Models\MediaKonten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EducationController extends Controller
{
    /**
     * Display education catalog
     */
    public function index(Request $request)
    {
        $query = Education::where('status', 'published')
            ->with(['statistik'])
            ->orderBy('tanggal_upload', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 9);
        $konten = $query->paginate($perPage);

        // Get user reactions if logged in
        $userReactions = [];
        if (Auth::check()) {
            $userId = Auth::id();
            $userReactions = ReaksiKonten::where('id_user', $userId)
                ->pluck('tipe_reaksi', 'id_konten')
                ->toArray();
        }

        return view('education.index', compact('konten', 'userReactions'));
    }

    /**
     * Show education detail
     */
    public function show($id)
    {
        $konten = Education::where('status', 'published')
            ->where('id_konten', $id)
            ->with(['statistik', 'media'])
            ->firstOrFail();

        // Increment view count
        $konten->incrementView();

        // Get user reaction
        $userReaction = null;
        if (Auth::check()) {
            $userReaction = $konten->hasUserReacted(Auth::id());
        } else {
            $userReaction = $konten->hasUserReacted(null, request()->ip());
        }

        // Get related content
        $related = Education::where('status', 'published')
            ->where('id_konten', '!=', $id)
            ->with(['statistik'])
            ->orderBy('tanggal_upload', 'desc')
            ->limit(3)
            ->get();

        return view('education.show', compact('konten', 'related', 'userReaction'));
    }

    /**
     * Add reaction to content
     */
    public function addReaction(Request $request, $id)
    {
        $request->validate([
            'tipe_reaksi' => 'required|in:suka,membantu,menarik,inspiratif'
        ]);

        $konten = Education::findOrFail($id);
        $tipeReaksi = $request->tipe_reaksi;
        $userId = Auth::check() ? Auth::id() : null;
        $ipAddress = $request->ip();

        // Check if user already reacted
        $existingReaction = $konten->hasUserReacted($userId, $ipAddress);

        if ($existingReaction) {
            // Update existing reaction
            $oldTipe = $existingReaction->tipe_reaksi;
            
            if ($oldTipe === $tipeReaksi) {
                // Same reaction - remove it
                $existingReaction->delete();
                
                // Update statistics
                $stats = $konten->statistik ?? StatistikEdukasi::create(['id_konten' => $id]);
                $stats->updateReaction($tipeReaksi, 'remove');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Reaksi dihapus',
                    'action' => 'removed',
                    'counts' => $konten->fresh()->getReactionCounts()
                ]);
            } else {
                // Different reaction - update
                $existingReaction->update(['tipe_reaksi' => $tipeReaksi]);
                
                // Update statistics
                $stats = $konten->statistik;
                $stats->updateReaction($oldTipe, 'remove');
                $stats->updateReaction($tipeReaksi, 'add');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Reaksi diperbarui',
                    'action' => 'updated',
                    'tipe_reaksi' => $tipeReaksi,
                    'counts' => $konten->fresh()->getReactionCounts()
                ]);
            }
        } else {
            // Add new reaction
            ReaksiKonten::create([
                'id_konten' => $id,
                'id_user' => $userId,
                'tipe_reaksi' => $tipeReaksi,
                'ip_address' => $ipAddress
            ]);
            
            // Update statistics
            $stats = $konten->statistik ?? StatistikEdukasi::create(['id_konten' => $id]);
            $stats->updateReaction($tipeReaksi, 'add');
            
            return response()->json([
                'success' => true,
                'message' => 'Reaksi ditambahkan',
                'action' => 'added',
                'tipe_reaksi' => $tipeReaksi,
                'counts' => $konten->fresh()->getReactionCounts()
            ]);
        }
    }

    /**
     * ADMIN ONLY - Show create form
     */
    public function create()
    {
        $this->authorize('create', Education::class);
        return view('education.create');
    }

    /**
     * ADMIN ONLY - Store new content
     */
    public function store(Request $request)
    {
        $this->authorize('create', Education::class);

        $validated = $request->validate([
            'judul' => 'required|max:255',
            'ringkasan' => 'nullable|max:1000',
            'isi' => 'required',
            'gambar_cover' => 'nullable|image|max:2048',
            'penulis' => 'required|max:100',
            'waktu_baca' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published'
        ]);

        // Handle cover image
        if ($request->hasFile('gambar_cover')) {
            $file = $request->file('gambar_cover');
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('education/covers', $filename, 'public');
            $validated['gambar_cover'] = $path;
        }

        $validated['tanggal_upload'] = now();

        $konten = Education::create($validated);

        // Create initial statistics
        StatistikEdukasi::create([
            'id_konten' => $konten->id_konten,
            'last_updated' => now()
        ]);

        return redirect()
            ->route('education.show', $konten->id_konten)
            ->with('success', 'Konten edukasi berhasil ditambahkan');
    }

    /**
     * ADMIN ONLY - Show edit form
     */
    public function edit($id)
    {
        $konten = Education::findOrFail($id);
        $this->authorize('update', $konten);
        
        return view('education.edit', compact('konten'));
    }

    /**
     * ADMIN ONLY - Update content
     */
    public function update(Request $request, $id)
    {
        $konten = Education::findOrFail($id);
        $this->authorize('update', $konten);

        $validated = $request->validate([
            'judul' => 'required|max:255',
            'ringkasan' => 'nullable|max:1000',
            'isi' => 'required',
            'gambar_cover' => 'nullable|image|max:2048',
            'penulis' => 'required|max:100',
            'waktu_baca' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published'
        ]);

        // Handle cover image
        if ($request->hasFile('gambar_cover')) {
            // Delete old image
            if ($konten->gambar_cover) {
                Storage::disk('public')->delete($konten->gambar_cover);
            }
            
            $file = $request->file('gambar_cover');
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('education/covers', $filename, 'public');
            $validated['gambar_cover'] = $path;
        }

        $konten->update($validated);

        return redirect()
            ->route('education.show', $konten->id_konten)
            ->with('success', 'Konten edukasi berhasil diperbarui');
    }

    /**
     * ADMIN ONLY - Delete content
     */
    public function destroy($id)
    {
        $konten = Education::findOrFail($id);
        $this->authorize('delete', $konten);

        // Delete cover image
        if ($konten->gambar_cover) {
            Storage::disk('public')->delete($konten->gambar_cover);
        }

        $konten->delete();

        return redirect()
            ->route('education.index')
            ->with('success', 'Konten edukasi berhasil dihapus');
    }
}