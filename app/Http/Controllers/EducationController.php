<?php
// app/Http/Controllers/EducationController.php
namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\ReaksiKonten;
use App\Models\StatistikEdukasi;
use App\Models\MediaKonten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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

        $perPage = $request->get('per_page', 9);
        $konten = $query->paginate($perPage);

        // Get user reactions if logged in
        $userReactions = [];
        if (Session::has('user_id')) {
            $userId = Session::get('user_id');
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
        $userId = Session::get('user_id');
        
        if ($userId) {
            $userReaction = ReaksiKonten::where('id_konten', $id)
                ->where('id_user', $userId)
                ->first();
        } else {
            $userReaction = ReaksiKonten::where('id_konten', $id)
                ->where('ip_address', request()->ip())
                ->whereNull('id_user')
                ->first();
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
        Log::info('=== REACTION REQUEST START ===', [
            'konten_id' => $id,
            'request_data' => $request->all(),
            'session' => Session::all(),
            'ip' => $request->ip()
        ]);

        try {
            $request->validate([
                'tipe_reaksi' => 'required|in:suka,membantu,menarik,inspiratif'
            ]);

            $konten = Education::findOrFail($id);
            $tipeReaksi = $request->tipe_reaksi;
            $userId = Session::get('user_id');
            $ipAddress = $request->ip();

            Log::info('Processing reaction', [
                'userId' => $userId,
                'ipAddress' => $ipAddress,
                'reactionType' => $tipeReaksi
            ]);

            // Build query to check existing reaction
            $query = ReaksiKonten::where('id_konten', $id);
            
            if ($userId) {
                // Logged in user - check by user_id
                $existingReaction = $query->where('id_user', $userId)->first();
                Log::info('Checking reaction for user', ['user_id' => $userId, 'found' => !is_null($existingReaction)]);
            } else {
                // Guest - check by IP
                $existingReaction = $query->where('ip_address', $ipAddress)
                    ->whereNull('id_user')
                    ->first();
                Log::info('Checking reaction for guest', ['ip' => $ipAddress, 'found' => !is_null($existingReaction)]);
            }

            if ($existingReaction) {
                if ($existingReaction->tipe_reaksi === $tipeReaksi) {
                    // Same reaction - remove it
                    Log::info('Removing existing reaction');
                    $existingReaction->delete();
                    
                    $this->updateReactionStats($konten, $tipeReaksi, 'remove');
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Reaksi dihapus',
                        'action' => 'removed',
                        'counts' => $konten->fresh()->getReactionCounts()
                    ]);
                } else {
                    // Different reaction - update
                    Log::info('Updating reaction', ['from' => $existingReaction->tipe_reaksi, 'to' => $tipeReaksi]);
                    $oldTipe = $existingReaction->tipe_reaksi;
                    $existingReaction->update([
                        'tipe_reaksi' => $tipeReaksi,
                        'created_at' => now()
                    ]);
                    
                    $this->updateReactionStats($konten, $oldTipe, 'remove');
                    $this->updateReactionStats($konten, $tipeReaksi, 'add');
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Reaksi diperbarui',
                        'action' => 'updated',
                        'counts' => $konten->fresh()->getReactionCounts()
                    ]);
                }
            } else {
                // Add new reaction
                Log::info('Creating new reaction');
                
                $reactionData = [
                    'id_konten' => $id,
                    'tipe_reaksi' => $tipeReaksi,
                    'created_at' => now()
                ];

                if ($userId) {
                    $reactionData['id_user'] = $userId;
                    $reactionData['ip_address'] = null; // Clear IP for logged in users
                } else {
                    $reactionData['id_user'] = null;
                    $reactionData['ip_address'] = $ipAddress;
                }

                ReaksiKonten::create($reactionData);
                
                $this->updateReactionStats($konten, $tipeReaksi, 'add');
                
                Log::info('New reaction created successfully');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Reaksi ditambahkan',
                    'action' => 'added',
                    'counts' => $konten->fresh()->getReactionCounts()
                ]);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Reaction error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        } finally {
            Log::info('=== REACTION REQUEST END ===');
        }
    }

    /**
     * Helper method to update reaction statistics
     */
    private function updateReactionStats($konten, $tipeReaksi, $action)
    {
        try {
            $stats = $konten->statistik;
            
            if (!$stats) {
                $stats = StatistikEdukasi::create([
                    'id_konten' => $konten->id_konten,
                    'total_view' => 0,
                    'total_suka' => 0,
                    'total_membantu' => 0,
                    'total_menarik' => 0,
                    'total_inspiratif' => 0,
                    'last_updated' => now()
                ]);
            }

            $column = 'total_' . $tipeReaksi;
            
            if ($action === 'add') {
                $stats->increment($column);
            } else if ($action === 'remove') {
                if ($stats->{$column} > 0) {
                    $stats->decrement($column);
                }
            }
            
            $stats->last_updated = now();
            $stats->save();

            Log::info('Stats updated', [
                'column' => $column,
                'action' => $action,
                'new_value' => $stats->{$column}
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating reaction stats: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * ADMIN ONLY - Show create form
     */
    public function create()
    {
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            return redirect()->route('education.index')->with('error', 'Unauthorized access');
        }
        
        return view('education.create');
    }

    /**
     * ADMIN ONLY - Store new content
     */
    public function store(Request $request)
    {
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            return redirect()->route('education.index')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'judul' => 'required|max:255',
            'ringkasan' => 'nullable|max:1000',
            'isi' => 'required',
            'gambar_cover' => 'nullable|image|max:2048',
            'penulis' => 'required|max:100',
            'waktu_baca' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published'
        ]);

        if ($request->hasFile('gambar_cover')) {
            $file = $request->file('gambar_cover');
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('education/covers', $filename, 'public');
            $validated['gambar_cover'] = $path;
        }

        $validated['tanggal_upload'] = now();
        $validated['konten'] = $validated['isi']; // Untuk backward compatibility

        $konten = Education::create($validated);

        StatistikEdukasi::create([
            'id_konten' => $konten->id_konten,
            'total_view' => 0,
            'total_suka' => 0,
            'total_membantu' => 0,
            'total_menarik' => 0,
            'total_inspiratif' => 0,
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
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            return redirect()->route('education.index')->with('error', 'Unauthorized access');
        }

        $konten = Education::findOrFail($id);
        return view('education.edit', compact('konten'));
    }

    /**
     * ADMIN ONLY - Update content
     */
    public function update(Request $request, $id)
    {
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            return redirect()->route('education.index')->with('error', 'Unauthorized access');
        }

        $konten = Education::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|max:255',
            'ringkasan' => 'nullable|max:1000',
            'isi' => 'required',
            'gambar_cover' => 'nullable|image|max:2048',
            'penulis' => 'required|max:100',
            'waktu_baca' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published'
        ]);

        if ($request->hasFile('gambar_cover')) {
            if ($konten->gambar_cover) {
                Storage::disk('public')->delete($konten->gambar_cover);
            }
            
            $file = $request->file('gambar_cover');
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('education/covers', $filename, 'public');
            $validated['gambar_cover'] = $path;
        }

        $validated['konten'] = $validated['isi']; // Untuk backward compatibility
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
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            return redirect()->route('education.index')->with('error', 'Unauthorized access');
        }

        $konten = Education::findOrFail($id);

        if ($konten->gambar_cover) {
            Storage::disk('public')->delete($konten->gambar_cover);
        }

        $konten->delete();

        return redirect()
            ->route('education.index')
            ->with('success', 'Konten edukasi berhasil dihapus');
    }
}