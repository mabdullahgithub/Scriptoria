<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatus;
use App\Events\ArticleSubmitted;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Traits\ApiResponses;
use App\Traits\DatabaseTransaction;
use App\Traits\ApiExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    use ApiResponses, DatabaseTransaction, ApiExceptionHandler;

    public function index()
    {
        $articles = Article::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

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
                $data = $request->only(['title', 'content', 'excerpt']);
                $data['user_id'] = Auth::id();
                $data['status'] = ArticleStatus::DRAFT;

                $article = Article::create($data);

                return redirect()
                    ->route('articles.show', $article)
                    ->with('success', 'Article created successfully as draft.');
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

        if ($article->status !== ArticleStatus::DRAFT) {
            return redirect()
                ->route('articles.show', $article)
                ->with('error', 'You can only edit articles in draft status.');
        }

        return view('articles.edit', compact('article'));
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        return $this->handleApiCall(function () use ($request, $article) {
            return $this->executeInTransaction(function () use ($request, $article) {
                $data = $request->only(['title', 'content', 'excerpt']);
                $article->update($data);

                return redirect()
                    ->route('articles.show', $article)
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

                $article->delete(); // This will now soft delete

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
