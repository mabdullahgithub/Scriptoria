<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Scriptoria</title>
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
        <form method="POST" action="{{ route('logout') }}" class="inline-form">
            @csrf
            <button type="submit" class="nav-btn">Logout</button>
        </form>
    </div>

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="nav-logo">Scriptoria</a>

    {{-- Search Bar --}}
    <div class="search-container">
        <form method="GET" class="search-form">
            <div class="search-input-wrapper">
                <svg class="search-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/>
                </svg>
                <input type="text" 
                       name="search" 
                       placeholder="Search articles by title, author, or content..." 
                       value="{{ request('search') }}"
                       class="search-input">
                @if(request('search'))
                    <a href="{{ route('admin.articles.index') }}" class="search-clear">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                        </svg>
                    </a>
                @endif
            </div>
            {{-- Preserve other filters --}}
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request('show_deleted'))
                <input type="hidden" name="show_deleted" value="{{ request('show_deleted') }}">
            @endif
        </form>
    </div>

    <div class="dashboard-container">
        {{-- Admin Welcome Message --}}
        <div class="welcome-message">
            <div class="welcome-content">
                <div class="welcome-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div class="welcome-text">
                    <h3>Admin Dashboard</h3>
                    <p>Welcome back, {{ auth()->user()->name }}! Manage all content and users with powerful admin tools.</p>
                </div>
                <div class="welcome-stats">
                    <div class="welcome-stat">
                        <span class="welcome-stat-number">{{ $articles->count() }}</span>
                        <span class="welcome-stat-label">Current View</span>
                    </div>
                    <div class="welcome-stat">
                        <span class="welcome-stat-number">{{ $articles->where('status', App\Enums\ArticleStatus::PENDING_REVIEW)->count() }}</span>
                        <span class="welcome-stat-label">Pending Review</span>
                    </div>
                    <div class="welcome-stat">
                        <span class="welcome-stat-number">{{ $articles->where('status', App\Enums\ArticleStatus::PUBLISHED)->count() }}</span>
                        <span class="welcome-stat-label">Published</span>
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

        {{-- Admin Stats Grid --}}
        <div class="stats-grid">
            <div class="stat-card">
                <svg class="stat-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                <span class="stat-number">{{ $articles->count() }}</span>
                <span class="stat-label">Current View</span>
            </div>
            <div class="stat-card">
                <svg class="stat-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <span class="stat-number">{{ $articles->where('status', App\Enums\ArticleStatus::PENDING_REVIEW)->count() }}</span>
                <span class="stat-label">Pending Review</span>
            </div>
            <div class="stat-card">
                <svg class="stat-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                <span class="stat-number">{{ $articles->where('status', App\Enums\ArticleStatus::PUBLISHED)->count() }}</span>
                <span class="stat-label">Published</span>
            </div>
            <div class="stat-card">
                <svg class="stat-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                <span class="stat-number">{{ $articles->where('status', App\Enums\ArticleStatus::DRAFT)->count() }}</span>
                <span class="stat-label">Drafts</span>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="filters-container">
            <h3>Filter Articles</h3>
            <form method="GET" class="filters-form">
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="filter-select">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="show_deleted">Show Deleted</label>
                    <select name="show_deleted" id="show_deleted" class="filter-select">
                        <option value="false" {{ request('show_deleted') === 'false' || !request('show_deleted') ? 'selected' : '' }}>Active Only</option>
                        <option value="true" {{ request('show_deleted') === 'true' ? 'selected' : '' }}>Deleted Only</option>
                        <option value="with" {{ request('show_deleted') === 'with' ? 'selected' : '' }}>Include Deleted</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-small btn-primary">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.articles.index') }}" class="btn-small btn-secondary">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/></svg>
                        Clear
                    </a>
                </div>
            </form>
        </div>

        {{-- Articles Section --}}
        @if($articles->count() > 0)
            <div class="articles-grid">
                @foreach($articles as $article)
                <div class="article-card {{ $article->trashed() ? 'article-deleted' : '' }}">
                    <div class="article-header">
                        <h3 class="article-title">
                            {{ $article->title }}
                            @if($article->trashed())
                                <span class="deleted-badge">DELETED</span>
                            @endif
                        </h3>
                        <span class="status-badge status-{{ $article->status->value }}">
                            {{ $article->status->label() }}
                        </span>
                    </div>
                    
                    <div class="article-meta-admin">
                        <div class="author-info">
                            <div class="author-avatar">{{ substr($article->user->name, 0, 1) }}</div>
                            <div class="author-details">
                                <span class="author-name">{{ $article->user->name }}</span>
                                <span class="author-email">{{ $article->user->email }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($article->excerpt)
                        <p class="article-excerpt">{{ $article->excerpt }}</p>
                    @else
                        <p class="article-excerpt">{{ Str::limit($article->content, 120) }}</p>
                    @endif
                    
                    <div class="article-meta">
                        <span class="article-date">Created {{ $article->created_at->format('M d, Y') }}</span>
                        @if($article->published_at)
                            <span class="article-date">• Published {{ $article->published_at->format('M d, Y') }}</span>
                        @endif
                        @if($article->trashed())
                            <span class="article-date article-deleted-date">• Deleted {{ $article->deleted_at->format('M d, Y') }}</span>
                        @endif
                    </div>
                    
                    <div class="article-actions">
                        @if($article->trashed())
                            {{-- Deleted article actions --}}
                            <form action="{{ route('admin.articles.restore', $article) }}" method="POST" class="inline-form">
                                @csrf
                                <button type="submit" class="btn-small btn-success">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10 10 10 0 0 0 10-10A10 10 0 0 0 12 2M7 12l5 3 9-9-1.5-1.5L12 12l-3.5-3.5L7 12z"/></svg>
                                    Restore
                                </button>
                            </form>
                            <form action="{{ route('admin.articles.force-delete', $article) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small btn-danger" onclick="return confirm('Permanently delete this article? This cannot be undone.')">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    Delete Forever
                                </button>
                            </form>
                        @else
                            {{-- Active article actions --}}
                            <a href="{{ route('admin.articles.show', $article) }}" class="btn-small btn-view">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                View
                            </a>
                            <a href="{{ route('admin.articles.edit', $article) }}" class="btn-small btn-edit">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                Edit
                            </a>
                            
                            @if($article->status === App\Enums\ArticleStatus::PENDING_REVIEW)
                                <form action="{{ route('admin.articles.approve', $article) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-small btn-success">
                                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.articles.reject', $article) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-small btn-danger" onclick="return confirm('Reject this article?')">
                                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/></svg>
                                        Reject
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline-form">
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
        @else
            <div class="empty-state">
                <h3>No Articles Found</h3>
                <p>No articles match the current filter criteria.</p>
                <a href="{{ route('admin.articles.index') }}" class="action-btn primary">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/></svg>
                    Clear Filters
                </a>
            </div>
        @endif
    </div>

    {{-- External JavaScript --}}
    <script src="{{ asset('js/components/glow-cursor.js') }}"></script>
</body>
</html>
