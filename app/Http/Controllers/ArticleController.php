<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatus;
use App\Events\ArticleSubmitted;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Traits\ApiExceptionHandler;
use App\Traits\ApiResponses;
use App\Traits\DatabaseTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    use ApiExceptionHandler, ApiResponses, DatabaseTransaction;

    public function index()
    {
        $articles = Article::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(StoreArticleRequest $request)
    {
        return $this->handleApiCall(function () use ($request) {
            return $this->executeInTransaction(function () use ($request) {
                $data = $request->validated();
                $data['user_id'] = Auth::id();

                if ($request->input('action') === 'submit') {
                    // Submit for review
                    $data['status'] = ArticleStatus::PENDING_REVIEW;
                    $article = Article::create($data);
                    event(new ArticleSubmitted($article));
                    
                    return redirect()
                        ->route('articles.index')
                        ->with('success', 'Article created and submitted for review successfully.');
                } else {
                    // Save as draft
                    $data['status'] = ArticleStatus::DRAFT;
                    $article = Article::create($data);
                    
                    return redirect()
                        ->route('articles.index')
                        ->with('success', 'Article saved as draft successfully.');
                }
            });
        });
    }

    public function show(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403, 'You can only view your own articles.');
        }

        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own articles.');
        }

        if (!in_array($article->status, [ArticleStatus::DRAFT, ArticleStatus::REJECTED])) {
            return redirect()
                ->route('articles.index')
                ->with('error', 'You can only edit draft or rejected articles.');
        }

        return view('articles.edit', compact('article'));
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        return $this->handleApiCall(function () use ($request, $article) {
            return $this->executeInTransaction(function () use ($request, $article) {
                if ($article->user_id !== Auth::id()) {
                    abort(403, 'You can only update your own articles.');
                }

                if (!in_array($article->status, [ArticleStatus::DRAFT, ArticleStatus::REJECTED])) {
                    return redirect()
                        ->route('articles.index')
                        ->with('error', 'You can only update draft or rejected articles.');
                }

                $article->update($request->validated());

                return redirect()
                    ->route('articles.index')
                    ->with('success', 'Article updated successfully.');
            });
        });
    }

    public function destroy(Article $article)
    {
        return $this->handleApiCall(function () use ($article) {
            return $this->executeInTransaction(function () use ($article) {
                if ($article->user_id !== Auth::id()) {
                    abort(403, 'You can only delete your own articles.');
                }

                if ($article->status === ArticleStatus::PUBLISHED) {
                    return redirect()
                        ->route('articles.index')
                        ->with('error', 'You cannot delete published articles.');
                }

                $article->delete();

                return redirect()
                    ->route('articles.index')
                    ->with('success', 'Article deleted successfully.');
            });
        });
    }

    public function submit(Article $article)
    {
        return $this->handleApiCall(function () use ($article) {
            return $this->executeInTransaction(function () use ($article) {
                if ($article->user_id !== Auth::id()) {
                    abort(403, 'You can only submit your own articles.');
                }

                if ($article->status !== ArticleStatus::DRAFT) {
                    return redirect()
                        ->route('articles.show', $article)
                        ->with('error', 'Article is not in draft status.');
                }

                $article->update(['status' => ArticleStatus::PENDING_REVIEW]);

                event(new ArticleSubmitted($article));

                return redirect()
                    ->route('articles.show', $article)
                    ->with('success', 'Article submitted for review successfully.');
            });
        });
    }
}