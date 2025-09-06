<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - {{ $article->title }} - Scriptoria</title>
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
        <a href="{{ route('articles.index') }}" class="nav-btn">My Articles</a>
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
        {{-- Dashboard Header --}}
        <div class="dashboard-header">
            <div class="dashboard-title">
                <h1>Edit Article</h1>
                <p>Make your content even better</p>
            </div>
            <div class="dashboard-actions">
                <a href="{{ route('articles.show', $article) }}" class="action-btn secondary">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                    View Article
                </a>
            </div>
        </div>

        {{-- Form Container --}}
        <div class="form-container">
            <form action="{{ route('articles.update', $article) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="title" class="form-label">Article Title</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $article->title) }}"
                           class="form-input @error('title') border-red-500 @enderror"
                           placeholder="Enter a compelling title for your article"
                           required>
                    @error('title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="excerpt" class="form-label">
                        Excerpt 
                        <span style="color: #888; font-weight: normal;">(optional - will be auto-generated if empty)</span>
                    </label>
                    <textarea name="excerpt" 
                              id="excerpt" 
                              rows="3"
                              class="form-textarea @error('excerpt') border-red-500 @enderror"
                              placeholder="Write a brief summary of your article...">{{ old('excerpt', $article->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">Article Content</label>
                    <textarea name="content" 
                              id="content" 
                              rows="20"
                              class="form-textarea @error('content') border-red-500 @enderror"
                              placeholder="Start writing your article content here..."
                              required>{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('articles.show', $article) }}" class="action-btn secondary">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.42-1.41L7.83 13H20v-2z"/></svg>
                        Cancel
                    </a>
                    <button type="submit" class="action-btn primary">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        Update Article
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- External JavaScript --}}
    <script src="{{ asset('js/components/glow-cursor.js') }}"></script>
    
    {{-- Auto-resize textarea --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('content');
            
            function autoResize() {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            }
            
            textarea.addEventListener('input', autoResize);
            autoResize(); // Initial resize
            
            // Focus on title input
            document.getElementById('title').focus();
        });
    </script>
</body>
</html>
