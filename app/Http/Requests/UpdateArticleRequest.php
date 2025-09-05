<?php

namespace App\Http\Requests;

use App\Enums\ArticleStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $article = $this->route('article');
        
        if (Auth::user()->isWriter()) {
            return $article->user_id === Auth::id() && 
                   $article->status === ArticleStatus::DRAFT;
        }
        
        return Auth::user()->isAdmin();
    }

    public function rules(): array
    {
        $article = $this->route('article');
        
        $rules = [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('articles', 'title')->ignore($article->id)
            ],
            'content' => 'required|string|min:100',
            'excerpt' => 'nullable|string|max:500',
        ];

        if (Auth::user()->isAdmin()) {
            $rules['status'] = [
                'sometimes',
                Rule::enum(ArticleStatus::class)
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The article title is required.',
            'title.unique' => 'An article with this title already exists.',
            'content.required' => 'The article content is required.',
            'content.min' => 'The article content must be at least 100 characters.',
            'excerpt.max' => 'The excerpt cannot exceed 500 characters.',
            'status.enum' => 'The selected status is invalid.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->excerpt && $this->content) {
            $this->merge([
                'excerpt' => substr(strip_tags($this->content), 0, 200) . '...'
            ]);
        }
    }
}
