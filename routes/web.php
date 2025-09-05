<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Writer routes
Route::middleware(['auth', 'verified', 'writer'])->group(function () {
    Route::resource('articles', ArticleController::class);
    Route::post('articles/{article}/submit', [ArticleController::class, 'submit'])->name('articles.submit');
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('articles', AdminArticleController::class)->except(['create', 'store']);
    Route::post('articles/{article}/approve', [AdminArticleController::class, 'approve'])->name('articles.approve');
    Route::post('articles/{article}/reject', [AdminArticleController::class, 'reject'])->name('articles.reject');
    Route::post('articles/{article}/restore', [AdminArticleController::class, 'restore'])->name('articles.restore');
    Route::delete('articles/{article}/force-delete', [AdminArticleController::class, 'forceDelete'])->name('articles.force-delete');
    Route::get('articles/stats', [AdminArticleController::class, 'stats'])->name('articles.stats');
});

require __DIR__.'/auth.php';
