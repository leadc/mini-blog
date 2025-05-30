<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// Agregar el controlador de posts
use App\Http\Controllers\PostController;
use App\Http\Controllers\LandingTextController;

Route::get('/', function () {
    return view('blog');
});

require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    $perPage = request('perPage', 25);
    $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 25;
    $posts = \App\Models\Post::with('user')->latest()->paginate($perPage)->appends(['perPage' => $perPage]);
    return view('dashboard', compact('posts'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Ruta para crear post
Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts/upload-image', [PostController::class, 'uploadImage'])->name('posts.uploadImage');
    Route::post('/posts/delete-image', [PostController::class, 'deleteImage'])->name('posts.deleteImage');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::get('/admin/landing/edit', [LandingTextController::class, 'edit'])->name('landing.edit');
    Route::post('/admin/landing/update', [LandingTextController::class, 'update'])->name('landing.update');
});

// Ruta pública para ver un post por su id
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/blog', function () {
    $posts = \App\Models\Post::latest()->paginate(12);
    return view('posts.index', compact('posts'));
})->name('posts.index');
