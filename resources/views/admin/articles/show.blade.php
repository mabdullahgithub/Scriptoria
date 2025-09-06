<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ $article->title }} - Scriptoria</title>
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
        <a href="{{ route('admin.articles.index') }}" class="nav-btn">Admin Dashboard</a>
        <a href="{{ route('home') }}" class="nav-btn">Home</a>
        <form method="POST" action="{{ route('logout') }}" class="inline-form">
            @csrf
            <button type="submit" class="nav-btn">Logout</button>
        </form>
    </div>

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="nav-logo">Scriptoria</a>

    <div class="dashboard-container">
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

        {{-- Article Header --}}
        <div class="article-view-header">
            <div class="article-view-info">
                <h1 class="article-view-title">{{ $article->title }}</h1>
                <div class="article-view-meta">
                    <div class="author-section">
                        <div class="author-avatar">{{ substr($article->user->name, 0, 1) }}</div>
                        <div class="author-details">
                            <span class="author-name">{{ $article->user->name }}</span>
                            <span class="author-email">{{ $article->user->email }}</span>
                        </div>
                    </div>
                    <div class="article-dates">
                        <div class="date-item">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>
                            Created {{ $article->created_at->format('F j, Y \a\t g:i A') }}
                        </div>
                        @if($article->published_at)
                            <div class="date-item">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                Published {{ $article->published_at->format('F j, Y \a\t g:i A') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="article-view-actions">
                <span class="status-badge status-{{ $article->status->value }}">
                    {{ $article->status->label() }}
                </span>
                <a href="{{ route('admin.articles.edit', $article) }}" class="action-btn primary">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    Edit Article
                </a>
            </div>
        </div>

        {{-- Article Content --}}
        <div class="article-view-container">
            @if($article->excerpt)
                <div class="article-excerpt-display">
                    <h3>Article Excerpt</h3>
                    <p>{{ $article->excerpt }}</p>
                </div>
            @endif

            <div class="article-content-display">
                <h3>Article Content</h3>
                <div class="content-text">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </div>
        </div>

        {{-- Admin Review Actions --}}
        @if($article->status === App\Enums\ArticleStatus::PENDING_REVIEW)
            <div class="review-actions-container">
                <h3>Review Actions</h3>
                <p>This article is waiting for your review. You can approve it to publish or reject it to send back to the author.</p>
                
                <div class="review-actions">
                    <form action="{{ route('admin.articles.approve', $article) }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="action-btn success">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            Approve & Publish
                        </button>
                    </form>
                    <form action="{{ route('admin.articles.reject', $article) }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="action-btn danger" onclick="return confirm('Are you sure you want to reject this article? The author will be able to edit and resubmit it.')">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/></svg>
                            Reject Article
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Navigation Actions --}}
        <div class="navigation-actions">
            <a href="{{ route('admin.articles.index') }}" class="action-btn secondary">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.42-1.41L7.83 13H20v-2z"/></svg>
                Back to Admin Dashboard
            </a>
            <a href="{{ route('admin.articles.index', $article) }}" class="action-btn secondary">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                View Public Article
            </a>
        </div>
    </div>

    {{-- External JavaScript --}}
    <script src="{{ asset('js/components/glow-cursor.js') }}"></script>
</body>
</html>
