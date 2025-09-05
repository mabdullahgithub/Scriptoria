<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin - {{ $article->title }}
            </h2>
            <div class="flex space-x-2">
                @if($article->status === App\Enums\ArticleStatus::PENDING_REVIEW)
                    <form action="{{ route('admin.articles.approve', $article) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Approve & Publish
                        </button>
                    </form>
                    <form action="{{ route('admin.articles.reject', $article) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Reject this article?')">
                            Reject
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.articles.edit', $article) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('admin.articles.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Article Meta Info -->
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $article->title }}</h1>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span>By {{ $article->user->name }} ({{ $article->user->email }})</span>
                                    <span>•</span>
                                    <span>Created {{ $article->created_at->format('F j, Y g:i A') }}</span>
                                    @if($article->published_at)
                                        <span>•</span>
                                        <span>Published {{ $article->published_at->format('F j, Y g:i A') }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $article->status->badgeClass() }}">
                                {{ $article->status->value }}
                            </span>
                        </div>
                    </div>

                    <!-- Article Excerpt -->
                    @if($article->excerpt)
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Excerpt</h3>
                            <p class="text-gray-700 italic">{{ $article->excerpt }}</p>
                        </div>
                    @endif

                    <!-- Article Content -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Content</h3>
                        <div class="prose max-w-none bg-gray-50 p-6 rounded-lg">
                            {!! nl2br(e($article->content)) !!}
                        </div>
                    </div>

                    <!-- Admin Actions Section -->
                    @if($article->status === App\Enums\ArticleStatus::PENDING_REVIEW)
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Review Actions</h3>
                            <div class="flex space-x-4">
                                <form action="{{ route('admin.articles.approve', $article) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                                        ✓ Approve & Publish
                                    </button>
                                </form>
                                <form action="{{ route('admin.articles.reject', $article) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg" onclick="return confirm('Are you sure you want to reject this article?')">
                                        ✗ Reject Article
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
