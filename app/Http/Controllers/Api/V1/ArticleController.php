<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatus;
use App\Http\Controllers\Api\BaseApiController;
use App\Models\Article;

class ArticleController extends BaseApiController
{
    public function index()
    {
        return $this->handleApiCall(function () {
            $articles = Article::published()
                ->with('user:id,name')
                ->latest('published_at')
                ->get();

            $data = [
                'articles' => $articles->map(function ($article) {
                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        'content' => $article->content,
                        'author' => $article->user->name,
                        'published_at' => $article->published_at ? $article->published_at->format('Y-m-d H:i:s') : null,
                        'created_at' => $article->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'version' => 'v1',
                    'total' => $articles->count(),
                    'timestamp' => now()->toISOString()
                ]
            ];
            
            return $this->successResponse(
                $data,
                'Published articles retrieved successfully',
                null,
                HttpStatus::OK->value
            );
        });
    }

    public function show($id)
    {
        return $this->handleApiCall(function () use ($id) {
            $article = Article::published()
                ->with('user:id,name')
                ->findOrFail($id);

            $data = [
                'id' => $article->id,
                'title' => $article->title,
                'content' => $article->content,
                'author' => $article->user->name,
                'published_at' => $article->published_at ? $article->published_at->format('Y-m-d H:i:s') : null,
                'created_at' => $article->created_at->format('Y-m-d H:i:s'),
                'meta' => [
                    'version' => 'v1',
                    'id' => $article->id
                ]
            ];
            
            return $this->successResponse(
                $data,
                'Article retrieved successfully',
                null,
                HttpStatus::OK->value
            );
        });
    }
}
