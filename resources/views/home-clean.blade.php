<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scriptoria - Content Management System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- External CSS --}}
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/breadcrumb.css') }}">
    
    {{-- Additional styles for search highlighting --}}
    <style>
        .search-highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: 600;
        }
        
        /* Fix for CSS lint warning */
        .name {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    {{-- Breadcrumb Navigation --}}
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            [
                'title' => 'Home',
                'icon' => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M10 19v-5h4v5c0 .55.45 1 1 1h3c.55 0 1-.45 1-1v-7h1.7c.46 0 .68-.57.33-.87L12.67 3.6c-.38-.34-.96-.34-1.34 0l-8.36 7.53c-.34.3-.13.87.33.87H5v7c0 .55.45 1 1 1h3c.55 0 1-.45 1-1z"/></svg>'
            ]
        ]
    ])

    <div class="container">
        {{-- Search Box --}}
        <div class="search-box">
            <input type="text" id="searchInput" class="search-input" placeholder="Search articles... (Ctrl+K)">
        </div>

        {{-- Profile Section --}}
        <div class="profile-card">
            <div class="cms-badge">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                CMS Admin
            </div>
            
            <div class="profile-content">
                <div class="profile-left">
                    <div class="logo-large">S</div>
                </div>
                
                <div class="profile-right">
                    <h1 class="name">Scriptoria</h1>
                    <p class="username">@scriptoria-cms</p>
                    <p class="bio">A powerful content management system built with Laravel. Manage articles, users, and content with ease.</p>
                    
                    <div class="stats">
                        <div class="stat">
                            <span class="stat-number">{{ $stats['total_articles'] }}</span>
                            <span class="stat-label">Articles</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">{{ $stats['published_articles'] }}</span>
                            <span class="stat-label">Published</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">{{ $stats['total_writers'] }}</span>
                            <span class="stat-label">Writers</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">{{ $stats['pending_review'] }}</span>
                            <span class="stat-label">Pending</span>
                        </div>
                    </div>
                </div>
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
                    <p class="article-excerpt">{{ Str::limit($article->content, 150) }}</p>
                    <div class="article-meta">
                        <span class="article-date">{{ $article->created_at->format('M d, Y') }}</span>
                        <span class="read-time">{{ ceil(str_word_count($article->content) / 200) }} min read</span>
                    </div>
                    <div class="article-actions">
                        <div></div>
                        @if($article->status->value === 'published')
                            <a href="{{ route('articles.show', $article) }}" class="read-more-btn">Read Article</a>
                        @else
                            <span style="font-size: 13px; color: #888;">{{ $article->status->label() }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="error-message">
                    <h3>No Articles Yet</h3>
                    <p>Start creating amazing content with Scriptoria CMS!</p>
                    @auth
                        <a href="{{ route('articles.create') }}" class="read-more-btn" style="margin-top: 15px; display: inline-block;">Create Your First Article</a>
                    @else
                        <a href="{{ route('register') }}" class="read-more-btn" style="margin-top: 15px; display: inline-block;">Join Scriptoria</a>
                    @endauth
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- External JavaScript --}}
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/components/breadcrumb.js') }}"></script>
</body>
</html>
