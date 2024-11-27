<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use App\Models\Post;
use App\Models\Kategori;
use App\Models\Galery;
use App\Models\Foto;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'posts' => Post::count(),
            'petugas' => Petugas::count(),
            'kategoris' => Kategori::count(),
            'galery' => Galery::count(),
            'foto' => Foto::count()
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
} 