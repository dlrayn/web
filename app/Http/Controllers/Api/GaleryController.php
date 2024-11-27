<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Galery;
use Illuminate\Http\Request;

class GaleryController extends Controller
{
    public function index()
    {
        try {
            $galeries = Galery::with(['post'])->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $galeries
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'position' => 'required|integer',
            'status' => 'required|in:draft,public'
        ]);

        try {
            $galery = Galery::create([
                'post_id' => $request->post_id,
                'position' => $request->position,
                'status' => $request->status
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Galery berhasil ditambahkan',
                'data' => $galery->load('post')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan galery: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $galery = Galery::with('post')->find($id);
            
            if (!$galery) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Galery tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $galery
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $galery = Galery::find($id);

        if (!$galery) {
            return response()->json([
                'status' => 'error',
                'message' => 'Galery tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'position' => 'required|integer',
            'status' => 'required|in:draft,public'
        ]);

        try {
            $galery->update([
                'post_id' => $request->post_id,
                'position' => $request->position,
                'status' => $request->status
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Galery berhasil diupdate',
                'data' => $galery->load('post')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate galery: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $galery = Galery::find($id);

            if (!$galery) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Galery tidak ditemukan'
                ], 404);
            }

            $galery->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Galery berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus galery: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'position' => 'required|integer|min:1',
            'status' => 'required|in:draft,public'
        ]);

        try {
            // Check if the position already exists and adjust if necessary
            if (Galery::where('position', '>=', $request->position)->exists()) {
                Galery::where('position', '>=', $request->position)
                      ->increment('position');
            }

            $galery = Galery::create([
                'post_id' => $request->post_id,
                'position' => $request->position,
                'status' => $request->status
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Galery berhasil ditambahkan',
                'data' => $galery->load('post')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan galery: ' . $e->getMessage()
            ], 500);
        }
    }
}
