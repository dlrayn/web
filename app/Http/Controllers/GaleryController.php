<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galery;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GaleryController extends Controller
{
    public function index()
    {
        $galeries = Galery::with('post')->get();
        return view('galery.index', compact('galeries'));
    }

    public function create()
    {
        $posts = Post::all(['id', 'judul']);
        return view('galery.create', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'position' => 'required|integer|min:1',
            'status' => 'required|in:draft,public'
        ]);

        try {
            DB::beginTransaction();

            // Adjust positions if necessary
            if (Galery::where('position', '>=', $request->position)->exists()) {
                Galery::where('position', '>=', $request->position)
                      ->increment('position');
            }

            Galery::create([
                'post_id' => $request->post_id,
                'position' => $request->position,
                'status' => $request->status
            ]);

            DB::commit();

            return redirect()->route('galery.index')
                           ->with('success', 'Galeri berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withErrors(['error' => 'Gagal menambahkan galeri: ' . $e->getMessage()])
                           ->withInput();
        }
    }

    public function show(Galery $galery)
    {
        return view('galery.show', compact('galery'));
    }

    public function edit(Galery $galery)
    {
        $posts = Post::all(['id', 'judul']);
        return view('galery.edit', compact('galery', 'posts'));
    }

    public function update(Request $request, Galery $galery)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'position' => 'required|integer|min:1',
            'status' => 'required|in:draft,public'
        ]);

        try {
            DB::beginTransaction();

            $oldPosition = $galery->position;
            $newPosition = $request->position;

            // Handle position reordering
            if ($oldPosition != $newPosition) {
                if ($oldPosition < $newPosition) {
                    Galery::whereBetween('position', [$oldPosition + 1, $newPosition])
                          ->decrement('position');
                } else {
                    Galery::whereBetween('position', [$newPosition, $oldPosition - 1])
                          ->increment('position');
                }
            }

            $galery->update([
                'post_id' => $request->post_id,
                'position' => $newPosition,
                'status' => $request->status
            ]);

            DB::commit();

            return redirect()->route('galery.index')
                           ->with('success', 'Galeri berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withErrors(['error' => 'Gagal memperbarui galeri: ' . $e->getMessage()])
                           ->withInput();
        }
    }

    public function destroy(Galery $galery)
    {
        try {
            DB::beginTransaction();

            // Reorder remaining items
            Galery::where('position', '>', $galery->position)
                  ->decrement('position');

            $galery->delete();

            DB::commit();

            return redirect()->route('galery.index')
                           ->with('success', 'Galeri berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withErrors(['error' => 'Gagal menghapus galeri: ' . $e->getMessage()]);
        }
    }
}
