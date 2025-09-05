<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatus;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\ArticleCollection;
use App\Http\Resources\Api\V1\ArticleResource;
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

            $resource = new ArticleCollection($articles);
            
            return $this->successResponse(
                $resource->toArray(request()),
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

            $resource = new ArticleResource($article);
            
            return $this->successResponse(
                $resource->toArray(request()),
                'Article retrieved successfully',
                null,
                HttpStatus::OK->value
            );
        });
    }
}
