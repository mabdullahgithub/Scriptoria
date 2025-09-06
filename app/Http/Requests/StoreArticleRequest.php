<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->isWriter() || Auth::user()->isAdmin());
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:articles,title',
            'content' => 'required|string|min:100',
            'excerpt' => 'required|string|max:500|min:10',
            'action' => 'required|in:draft,submit',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The article title is required.',
            'title.unique' => 'An article with this title already exists.',
            'content.required' => 'The article content is required.',
            'content.min' => 'The article content must be at least 100 characters.',
            'excerpt.required' => 'The article excerpt is required.',
            'excerpt.min' => 'The excerpt must be at least 10 characters.',
            'excerpt.max' => 'The excerpt cannot exceed 500 characters.',
        ];
    }

    // Removed auto-generation of excerpt - now required to be provided by user
}
