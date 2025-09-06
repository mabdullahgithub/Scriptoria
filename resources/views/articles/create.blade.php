<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Article - Scriptoria</title>
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
                <h1>Create New Article</h1>
                <p>Share your thoughts and ideas with the world</p>
            </div>
        </div>

        {{-- Form Container --}}
        <div class="form-container">
            <form action="{{ route('articles.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="title" class="form-label">Article Title</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}"
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
                        <span class="required-text">(required)</span>
                    </label>
                    <textarea name="excerpt" 
                              id="excerpt" 
                              rows="3"
                              class="form-textarea @error('excerpt') border-red-500 @enderror"
                              placeholder="Write a brief summary of your article..."
                              required>{{ old('excerpt') }}</textarea>
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
                              required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('articles.index') }}" class="action-btn secondary">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.42-1.41L7.83 13H20v-2z"/></svg>
                        Cancel
                    </a>
                    
                    <div class="form-submit-actions">
                        <button type="submit" name="action" value="draft" class="action-btn draft">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                            </svg>
                            Save as Draft
                        </button>
                        
                        <button type="submit" name="action" value="submit" class="action-btn primary">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2,21L23,12L2,3V10L17,12L2,14V21Z"/>
                            </svg>
                            Submit for Review
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- External JavaScript --}}
    <script src="{{ asset('js/components/glow-cursor.js') }}"></script>
    <script src="{{ asset('js/form-handler.js') }}"></script>
</body>
</html>
