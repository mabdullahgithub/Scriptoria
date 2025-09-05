<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\V1\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApiController::class, 'index'])->name('api.index');

Route::middleware(['api', 'api.version:v1'])->prefix('v1')->group(function () {
    Route::get('articles', [ArticleController::class, 'index'])->name('api.v1.articles.index');
    Route::get('articles/{id}', [ArticleController::class, 'show'])->name('api.v1.articles.show');
});