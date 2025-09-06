<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scriptoria - Content Management System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- External CSS --}}
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/glow-cursor.css') }}">
</head>
<body>
    {{-- Glow Cursor --}}
    @include('components.glow-cursor')

    {{-- Header Navigation --}}
    <div class="header-nav">
        @auth
            <div class="user-menu">
                <div class="user-btn">
                    <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <span>{{ auth()->user()->name }}</span>
                </div>
            </div>
            <a href="{{ route('articles.index') }}" class="auth-btn">My Articles</a>
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.articles.index') }}" class="auth-btn">Admin</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="inline-form">
                @csrf
                <button type="submit" class="auth-btn logout-btn">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="auth-btn">Login</a>
            <a href="{{ route('register') }}" class="auth-btn primary">Register</a>
        @endauth
    </div>

    <div class="container">
        {{-- Simple Header --}}
        <div class="simple-header">
            <h1>Scriptoria</h1>
            <p>A powerful content management system built with Laravel</p>
            
            {{-- Search Box moved here --}}
            <div class="header-search">
                <input type="text" id="searchInput" class="search-input" placeholder="Search articles... (Ctrl+K)">
            </div>
        </div>

        <div class="articles-section">
            <h2 class="section-title">Latest Published Articles</h2>
            <div class="articles-grid" id="articlesGrid">
                @forelse($articles as $article)
                <div class="article-card" data-title="{{ strtolower($article->title) }}" data-content="{{ strtolower($article->content) }}">
                    <div class="article-header">
                        <h3 class="article-title">{{ $article->title }}</h3>
                        <span class="status-badge status-{{ $article->status->value }}">
                            {{ $article->status->label() }}
                        </span>
                    </div>
                    <div class="article-author">By {{ $article->user->name }}</div>
                    <p class="article-excerpt">{{ $article->excerpt ?: Str::limit($article->content, 150) }}</p>
                    <div class="article-meta">
                        <span class="article-date">{{ $article->created_at->format('M d, Y') }}</span>
                        <span class="read-time">{{ ceil(str_word_count($article->content) / 200) }} min read</span>
                    </div>
                    <div class="article-actions">
                        <div></div>
                        @if($article->status->value === 'published')
                            <a href="{{ route('article.show', $article) }}" class="read-more-btn">Read Article</a>
                        @else
                            <span class="status-label">{{ $article->status->label() }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="error-message">
                    <h3>No Articles Yet</h3>
                    <p>Start creating amazing content with Scriptoria CMS!</p>
                    @auth
                        <a href="{{ route('articles.create') }}" class="read-more-btn inline-margin-btn">Create Your First Article</a>
                    @else
                        <a href="{{ route('register') }}" class="read-more-btn inline-margin-btn">Join Scriptoria</a>
                    @endauth
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- External JavaScript --}}
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/components/glow-cursor.js') }}"></script>
</body>
</html>