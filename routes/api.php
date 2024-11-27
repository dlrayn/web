<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetugasController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\GaleryController;
use App\Http\Controllers\Api\FotoController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\HomeController;

Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::get('/galery', [GaleryController::class, 'index']);
    Route::post('/galery', [GaleryController::class, 'store']);
    Route::get('/galery/{id}', [GaleryController::class, 'show']);
    Route::put('/galery/{id}', [GaleryController::class, 'update']);
    Route::delete('/galery/{id}', [GaleryController::class, 'destroy']);
});
Route::get('/login-test', function () {
    return view('auth.login-test');
});

Route::get('/profile', [ProfileController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/galery', [GaleryController::class, 'index']);
Route::get('/foto', [FotoController::class, 'index']);
Route::get('/posts/{post}/gallery', [PostController::class, 'getGallery']);

Route::prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'index']);
    Route::post('/', [ProfileController::class, 'store']);
    Route::get('/{id}', [ProfileController::class, 'show']);
    Route::put('/{id}', [ProfileController::class, 'update']);
    Route::delete('/{id}', [ProfileController::class, 'destroy']);
    Route::post('/create', [ProfileController::class, 'create']);
});

Route::prefix('v1')->group(function () {
    Route::get('/profiles', [ApiController::class, 'getProfiles']);
    Route::get('/kategoris', [ApiController::class, 'getKategoris']);
    Route::get('/posts', [ApiController::class, 'getPosts']);
    Route::get('/galeries', [ApiController::class, 'getGaleries']);
    Route::get('/fotos', [ApiController::class, 'getFotos']);
    Route::get('/all', [ApiController::class, 'getAllData']);
});

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);
Route::post('/kategori/create', [KategoriController::class, 'create']);
Route::post('/foto/create', [FotoController::class, 'create']);

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index']);

// Home Routes
Route::get('/home', [HomeController::class, 'index']);
Route::post('/home/create', [HomeController::class, 'create']);

// Profile Routes
Route::get('/profiles', [ProfileController::class, 'index']);
Route::post('/profiles', [ProfileController::class, 'store']);
Route::get('/profiles/{id}', [ProfileController::class, 'show']);
Route::put('/profiles/{id}', [ProfileController::class, 'update']);
Route::delete('/profiles/{id}', [ProfileController::class, 'destroy']);
Route::post('/profiles/create', [ProfileController::class, 'create']);

// Kategori Routes
Route::get('/kategori', [KategoriController::class, 'index']);
Route::post('/kategori', [KategoriController::class, 'store']);
Route::get('/kategori/{id}', [KategoriController::class, 'show']);
Route::put('/kategori/{id}', [KategoriController::class, 'update']);
Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);
Route::post('/kategori/create', [KategoriController::class, 'create']);

// Post Routes
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);
Route::post('/posts/create', [PostController::class, 'create']);

// Galery Routes
Route::get('/galery', [GaleryController::class, 'index']);
Route::post('/galery', [GaleryController::class, 'store']);
Route::get('/galery/{id}', [GaleryController::class, 'show']);
Route::put('/galery/{id}', [GaleryController::class, 'update']);
Route::delete('/galery/{id}', [GaleryController::class, 'destroy']);
Route::post('/galery/create', [GaleryController::class, 'create']);

// Foto Routes
Route::get('/foto', [FotoController::class, 'index']);
Route::post('/foto', [FotoController::class, 'store']);
Route::get('/foto/{id}', [FotoController::class, 'show']);
Route::put('/foto/{id}', [FotoController::class, 'update']);
Route::delete('/foto/{id}', [FotoController::class, 'destroy']);
Route::post('/foto/create', [FotoController::class, 'create']);

// Petugas Routes
Route::get('/petugas', [PetugasController::class, 'index']);
Route::post('/petugas', [PetugasController::class, 'store']);
Route::get('/petugas/{id}', [PetugasController::class, 'show']);
Route::put('/petugas/{id}', [PetugasController::class, 'update']);
Route::delete('/petugas/{id}', [PetugasController::class, 'destroy']);
