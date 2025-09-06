<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - Scriptoria</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- External CSS --}}
    <link rel="stylesheet" href="{{ asset('css/article-show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/breadcrumb.css') }}">
</head>
<body>
    {{-- Breadcrumb Navigation --}}
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            [
                'title' => 'Home',
                'url' => route('home'),
                'icon' => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M10 19v-5h4v5c0 .55.45 1 1 1h3c.55 0 1-.45 1-1v-7h1.7c.46 0 .68-.57.33-.87L12.67 3.6c-.38-.34-.96-.34-1.34 0l-8.36 7.53c-.34.3-.13.87.33.87H5v7c0 .55.45 1 1 1h3c.55 0 1-.45 1-1z"/></svg>'
            ],
            [
                'title' => Str::limit($article->title, 50, '...'),
                'icon' => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,19H5V5H19V19Z"/></svg>'
            ]
        ]
    ])

    <main class="article-main">
        <div class="article-container">
            {{-- Article Header --}}
            <header class="article-header">
                <div class="article-meta">
                    <span class="status-badge status-{{ $article->status->value }}">
                        {{ $article->status->label() }}
                    </span>
                    <div class="article-date">
                        {{ $article->created_at->format('F j, Y') }}
                        @if($article->published_at)
                            • Published {{ $article->published_at->format('F j, Y') }}
                        @endif
                    </div>
                </div>
                
                <h1 class="article-title">{{ $article->title }}</h1>
                
                <div class="article-author">
                    <div class="author-info">
                        <div class="author-avatar">{{ substr($article->user->name, 0, 1) }}</div>
                        <div class="author-details">
                            <div class="author-name">{{ $article->user->name }}</div>
                            <div class="reading-time">{{ ceil(str_word_count($article->content) / 200) }} min read</div>
                        </div>
                    </div>
                </div>

                @if($article->excerpt)
                    <div class="article-excerpt">
                        {{ $article->excerpt }}
                    </div>
                @endif
            </header>

            {{-- Article Content --}}
            <article class="article-content">
                <div class="content-body">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </article>

            {{-- Article Actions --}}
            <div class="article-actions">
                <a href="{{ route('home') }}" class="action-btn back-btn">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.42-1.41L7.83 13H20v-2z"/></svg>
                    Back to Home
                </a>
            </div>
        </div>
    </main>

    {{-- External JavaScript --}}
    <script src="{{ asset('js/components/breadcrumb.js') }}"></script>
</body>
</html>
