<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        // Get published articles for public display
        $articles = Article::with('user')
            ->where('status', ArticleStatus::PUBLISHED)
            ->latest()
            ->take(12) // Limit to 12 latest articles
            ->get();

        // Calculate statistics for the dashboard
        $stats = [
            'total_articles' => Article::count(),
            'published_articles' => Article::where('status', ArticleStatus::PUBLISHED)->count(),
            'total_writers' => User::where('is_admin', false)->count(),
            'pending_review' => Article::where('status', ArticleStatus::PENDING_REVIEW)->count(),
        ];

        return view('home', compact('articles', 'stats'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json([
                'articles' => [],
                'message' => 'Please enter a search term'
            ]);
        }

        $articles = Article::with('user')
            ->where('status', ArticleStatus::PUBLISHED)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'excerpt' => Str::limit($article->content, 150),
                    'author' => $article->user->name,
                    'created_at' => $article->created_at->format('M d, Y'),
                    'read_time' => ceil(str_word_count($article->content) / 200),
                    'url' => route('article.show', $article)
                ];
            });

        return response()->json([
            'articles' => $articles,
            'count' => $articles->count(),
            'query' => $query
        ]);
    }

    public function showArticle(Article $article)
    {
        // Only show published articles to public
        if ($article->status !== ArticleStatus::PUBLISHED) {
            abort(404);
        }

        return view('articles.show', compact('article'));
    }
}
