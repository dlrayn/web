<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Kategori;
use App\Models\Post;
use App\Models\Galery;
use App\Models\Foto;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'profiles' => Profile::all(),
            'kategoris' => Kategori::all(),
            'posts' => Post::all(),
            'galeries' => Galery::all(),
            'fotos' => Foto::all()
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            // Profile validation
            'profile.judul' => 'required|string|max:255',
            'profile.isi' => 'required|string',
            
            // Kategori validation
            'kategori.nama' => 'required|string|max:255',
            
            // Post validation
            'post.judul' => 'required|string|max:255',
            'post.isi' => 'required|string',
            'post.kategori_id' => 'required|exists:kategoris,id',
            
            // Galery validation
            'galery.judul' => 'required|string|max:255',
            'galery.deskripsi' => 'required|string',
            
            // Foto validation
            'foto.judul' => 'required|string|max:255',
            'foto.deskripsi' => 'required|string',
            'foto.image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            \DB::beginTransaction();

            $profile = Profile::create($request->profile);
            $kategori = Kategori::create($request->kategori);
            $post = Post::create($request->post);
            $galery = Galery::create($request->galery);

            // Handle file upload for foto
            $image = $request->file('foto.image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);

            $foto = Foto::create([
                'judul' => $request->input('foto.judul'),
                'deskripsi' => $request->input('foto.deskripsi'),
                'image' => $imageName
            ]);

            \DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan',
                'data' => [
                    'profile' => $profile,
                    'kategori' => $kategori,
                    'post' => $post,
                    'galery' => $galery,
                    'foto' => $foto
                ]
            ], 201);

        } catch (\Exception $e) {
            \DB::rollback();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data: ' . $e->getMessage()
            ], 500);
        }
    }
} 