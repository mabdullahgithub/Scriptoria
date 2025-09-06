<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Edit - {{ $article->title }} - Scriptoria</title>
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
        {{-- Dashboard Header --}}
        <div class="dashboard-header">
            <div class="dashboard-title">
                <h1>Edit Article (Admin)</h1>
                <p>Administrative edit access for "{{ $article->title }}"</p>
            </div>
            <div class="dashboard-actions">
                <a href="{{ route('admin.articles.show', $article) }}" class="action-btn secondary">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                    View Article
                </a>
            </div>
        </div>

        {{-- Author Info Panel --}}
        <div class="author-info-panel">
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
                @if($article->updated_at != $article->created_at)
                    <div class="date-item">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10 10 10 0 0 0 10-10A10 10 0 0 0 12 2m3.5 6L12 10.5 8.5 8 12 5.5 15.5 8Z"/></svg>
                        Last updated {{ $article->updated_at->format('F j, Y \a\t g:i A') }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Form Container --}}
        <div class="form-container">
            <form action="{{ route('admin.articles.update', $article) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="title" class="form-label">Article Title</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $article->title) }}"
                           class="form-input @error('title') border-red-500 @enderror"
                           placeholder="Enter a compelling title for this article"
                           required>
                    @error('title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="excerpt" class="form-label">
                        Excerpt 
                        <span class="required-text">(required)</span>
                    </label>
                    <textarea name="excerpt" 
                              id="excerpt" 
                              rows="3"
                              class="form-textarea @error('excerpt') border-red-500 @enderror"
                              placeholder="Write a brief summary of the article..."
                              required>{{ old('excerpt', $article->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Article Status</label>
                    <select name="status" 
                            id="status"
                            class="form-select @error('status') border-red-500 @enderror"
                            required>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ old('status', $article->status->value) === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="form-help">
                        <strong>Note:</strong> Setting status to "Published" will automatically set the published date.
                    </p>
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">Article Content</label>
                    <textarea name="content" 
                              id="content" 
                              rows="20"
                              class="form-textarea @error('content') border-red-500 @enderror"
                              placeholder="Edit the article content here..."
                              required>{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.articles.show', $article) }}" class="action-btn secondary">
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
    <script src="{{ asset('js/form-handler.js') }}"></script>
</body>
</html>
