<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ArticleStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Traits\ApiResponses;
use App\Traits\DatabaseTransaction;
use App\Traits\ApiExceptionHandler;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    use ApiResponses, DatabaseTransaction, ApiExceptionHandler;

    public function index(Request $request)
    {
        $query = Article::with('user')->latest();

        // Search functionality
        if ($request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', "%{$searchTerm}%")
                               ->orWhere('email', 'like', "%{$searchTerm}%");
                  });
            });
        }

        if ($request->status && ArticleStatus::tryFrom($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->author_id) {
            $query->where('user_id', $request->author_id);
        }

        // Add option to view deleted articles
        if ($request->show_deleted === 'true') {
            $query->onlyTrashed();
        } elseif ($request->show_deleted === 'with') {
            $query->withTrashed();
        }

        $articles = $query->get();
        $statuses = ArticleStatus::cases();

        return view('admin.articles.index', compact('articles', 'statuses'));
    }

    public function show(Article $article)
    {
        $article->load('user');
        return view('admin.articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $article->load('user');
        $statuses = ArticleStatus::cases();
        return view('admin.articles.edit', compact('article', 'statuses'));
    }

    public function update(Request $request, Article $article)
    {
        return $this->handleApiCall(function () use ($request, $article) {
            return $this->executeInTransaction(function () use ($request, $article) {
                $request->validate([
                    'title' => 'required|string|max:255',
                    'content' => 'required|string|min:100',
                    'excerpt' => 'nullable|string|max:500',
                    'status' => 'required|in:' . implode(',', array_column(ArticleStatus::cases(), 'value')),
                ]);

                $data = $request->only(['title', 'content', 'excerpt', 'status']);
                $data['excerpt'] = $data['excerpt'] ?: substr(strip_tags($data['content']), 0, 200) . '...';
                $data['status'] = ArticleStatus::from($data['status']);

                $article->update($data);

                $statusMessage = match($article->status) {
                    ArticleStatus::PUBLISHED => 'Article published successfully.',
                    ArticleStatus::REJECTED => 'Article rejected.',
                    ArticleStatus::PENDING_REVIEW => 'Article status updated to pending review.',
                    ArticleStatus::DRAFT => 'Article status updated to draft.',
                };

                return redirect()
                    ->route('admin.articles.show', $article)
                    ->with('success', $statusMessage);
            });
        });
    }

    public function destroy(Article $article)
    {
        return $this->handleApiCall(function () use ($article) {
            return $this->executeInTransaction(function () use ($article) {
                $title = $article->title;
                $article->delete();

                return redirect()
                    ->route('admin.articles.index')
                    ->with('success', "Article '{$title}' deleted successfully.");
            });
        });
    }

    public function approve(Article $article)
    {
        return $this->handleApiCall(function () use ($article) {
            return $this->executeInTransaction(function () use ($article) {
                if ($article->status !== ArticleStatus::PENDING_REVIEW) {
                    return redirect()
                        ->route('admin.articles.show', $article)
                        ->with('error', 'Only articles pending review can be approved.');
                }

                $article->update([
                    'status' => ArticleStatus::PUBLISHED,
                    'published_at' => now(),
                ]);

                return redirect()
                    ->route('admin.articles.show', $article)
                    ->with('success', 'Article approved and published successfully.');
            });
        });
    }

    public function reject(Article $article)
    {
        return $this->handleApiCall(function () use ($article) {
            return $this->executeInTransaction(function () use ($article) {
                if ($article->status !== ArticleStatus::PENDING_REVIEW) {
                    return redirect()
                        ->route('admin.articles.show', $article)
                        ->with('error', 'Only articles pending review can be rejected.');
                }

                $article->update(['status' => ArticleStatus::REJECTED]);

                return redirect()
                    ->route('admin.articles.show', $article)
                    ->with('success', 'Article rejected successfully.');
            });
        });
    }

    public function stats()
    {
        $stats = [
            'total' => Article::withTrashed()->count(),
            'active' => Article::count(),
            'published' => Article::published()->count(),
            'pending' => Article::pendingReview()->count(),
            'draft' => Article::draft()->count(),
            'rejected' => Article::rejected()->count(),
            'deleted' => Article::onlyTrashed()->count(),
        ];

        return response()->json($stats);
    }

    public function restore($id)
    {
        return $this->handleApiCall(function () use ($id) {
            return $this->executeInTransaction(function () use ($id) {
                $article = Article::onlyTrashed()->findOrFail($id);
                $article->restore();

                return redirect()
                    ->route('admin.articles.index')
                    ->with('success', 'Article restored successfully.');
            });
        });
    }

    public function forceDelete($id)
    {
        return $this->handleApiCall(function () use ($id) {
            return $this->executeInTransaction(function () use ($id) {
                $article = Article::onlyTrashed()->findOrFail($id);
                $title = $article->title;
                $article->forceDelete();

                return redirect()
                    ->route('admin.articles.index', ['show_deleted' => 'true'])
                    ->with('success', "Article '{$title}' permanently deleted.");
            });
        });
    }
}
