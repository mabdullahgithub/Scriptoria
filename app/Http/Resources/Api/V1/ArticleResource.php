<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->user->name,
            'published_at' => $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null,
            'meta' => [
                'version' => 'v1',
                'id' => $this->id
            ]
        ];
    }
}
