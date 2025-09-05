<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    public $collects = ArticleResource::class;

    public function toArray(Request $request): array
    {
        return [
            'articles' => $this->collection->map(function ($article) {
                return [
                    'title' => $article->title,
                    'content' => $article->content,
                    'author' => $article->user->name,
                    'published_at' => $article->published_at ? $article->published_at->format('Y-m-d H:i:s') : null,
                ];
            }),
            'meta' => [
                'version' => 'v1',
                'total' => $this->collection->count(),
                'timestamp' => now()->toISOString()
            ]
        ];
    }
}
