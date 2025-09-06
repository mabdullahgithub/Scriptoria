<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Articles - Scriptoria</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- External CSS --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/glow-cursor.css') }}">
</head>
<body>
    {{-- Glow Cursor --}}
    @include('components.glow-cursor')

    {{-- Navigation --}}
    <div class="dashboard-nav">
        <a href="{{ route('home') }}" class="nav-btn">Home</a>
        @if(auth()->user()->is_admin)
            <a href="{{ route('admin.articles.index') }}" class="nav-btn">Admin Panel</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="nav-btn">Logout</button>
        </form>
    </div>

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="nav-logo">Scriptoria</a>

    <div class="dashboard-container">
        {{-- Welcome Message --}}
        <div class="welcome-message">
            <div class="welcome-content">
                <div class="welcome-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div class="welcome-text">
                    <h3>Welcome back, {{ auth()->user()->name }}!</h3>
                    <p>Ready to create amazing content? You're doing great with your writing journey.</p>
                </div>
                <div class="welcome-stats">
                    <div class="welcome-stat">
                        <span class="welcome-stat-number">{{ $articles->count() }}</span>
                        <span class="welcome-stat-label">Articles</span>
                    </div>
                    <div class="welcome-stat">
                        <span class="welcome-stat-number">{{ $articles->where('status', App\Enums\ArticleStatus::PUBLISHED)->count() }}</span>
                        <span class="welcome-stat-label">Published</span>
                    </div>
                    <div class="welcome-stat">
                        <span class="welcome-stat-number">{{ $articles->sum(function($article) { return str_word_count($article->content); }) }}</span>
                        <span class="welcome-stat-label">Words</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        {{-- Stats Grid --}}
        <div class="stats-grid">
            <div class="stat-card">
                <svg class="stat-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                <span class="stat-number">{{ $articles->count() }}</span>
                <span class="stat-label">Total Articles</span>
            </div>
            <div class="stat-card">
                <svg class="stat-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                <span class="stat-number">{{ $articles->where('status', App\Enums\ArticleStatus::PUBLISHED)->count() }}</span>
                <span class="stat-label">Published</span>
            </div>
            <div class="stat-card">
                <svg class="stat-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <span class="stat-number">{{ $articles->where('status', App\Enums\ArticleStatus::PENDING_REVIEW)->count() }}</span>
                <span class="stat-label">Pending Review</span>
            </div>
            <div class="stat-card">
                <svg class="stat-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                <span class="stat-number">{{ $articles->where('status', App\Enums\ArticleStatus::DRAFT)->count() }}</span>
                <span class="stat-label">Drafts</span>
            </div>
        </div>

        {{-- Create Article Button --}}
        <div style="display: flex; justify-content: flex-end; margin-bottom: 30px;">
            <a href="{{ route('articles.create') }}" class="action-btn primary">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4v16m8-8H4"/></svg>
                Create New Article
            </a>
        </div>

        {{-- Articles Section --}}
        @if($articles->count() > 0)
            <div class="articles-grid">
                @foreach($articles as $article)
                <div class="article-card">
                    <div class="article-header">
                        <h3 class="article-title">{{ $article->title }}</h3>
                        <span class="status-badge status-{{ $article->status->value }}">
                            {{ $article->status->label() }}
                        </span>
                    </div>
                    
                    @if($article->excerpt)
                        <p class="article-excerpt">{{ $article->excerpt }}</p>
                    @else
                        <p class="article-excerpt">{{ Str::limit($article->content, 150) }}</p>
                    @endif
                    
                    <div class="article-meta">
                        <span class="article-date">Created {{ $article->created_at->format('M d, Y') }}</span>
                        @if($article->published_at)
                            <span class="article-date">• Published {{ $article->published_at->format('M d, Y') }}</span>
                        @endif
                    </div>
                    
                    <div class="article-actions">
                        <a href="{{ route('articles.show', $article) }}" class="btn-small btn-view">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            View
                        </a>
                        
                        @if(in_array($article->status, [App\Enums\ArticleStatus::DRAFT, App\Enums\ArticleStatus::REJECTED]))
                            <a href="{{ route('articles.edit', $article) }}" class="btn-small btn-edit">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                Edit
                            </a>
                            
                            <form action="{{ route('articles.submit', $article) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-small btn-submit" onclick="return confirm('Submit article for review?')">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                    Submit
                                </button>
                            </form>
                        @endif
                        
                        @if($article->status !== App\Enums\ArticleStatus::PUBLISHED)
                            <form action="{{ route('articles.destroy', $article) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small btn-delete" onclick="return confirm('Delete this article?')">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($articles->hasPages())
                <div style="margin-top: 40px; display: flex; justify-content: center;">
                    {{ $articles->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <h3>No Articles Yet</h3>
                <p>Start creating amazing content with Scriptoria CMS!</p>
                <a href="{{ route('articles.create') }}" class="action-btn primary">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4v16m8-8H4"/></svg>
                    Create Your First Article
                </a>
            </div>
        @endif
    </div>

    {{-- External JavaScript --}}
    <script src="{{ asset('js/components/glow-cursor.js') }}"></script>
</body>
</html>
