<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Propiedades
    Route::resource('properties', PropertyController::class);
    Route::post('/properties/{property}/reorder-images', [PropertyController::class, 'reorderImages'])->name('properties.reorder-images');

    // Blog - Artículos
    Route::resource('articles', ArticleController::class);
    Route::post('/articles/{article}/reorder-images', [ArticleController::class, 'reorderImages'])->name('articles.reorder-images');
    Route::delete('/article-images/{image}', [ArticleController::class, 'deleteImage'])->name('article-images.destroy');

    // Comentarios
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::patch('/comments/{comment}/toggle-approval', [CommentController::class, 'toggleApproval'])->name('comments.toggle-approval');
});

// Rutas públicas para comentarios
Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');

require __DIR__ . '/auth.php';
