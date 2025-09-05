<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">
                        @if(Auth::user()->isAdmin())
                            You're logged in as an Administrator. Manage articles and oversee the platform.
                        @else
                            You're logged in as a Writer. Create and manage your articles.
                        @endif
                    </p>
                </div>
            </div>

            @if(Auth::user()->isWriter())
                <!-- Writer Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h4>
                            <div class="space-y-3">
                                <a href="{{ route('articles.create') }}" class="block w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-200 ease-in-out transform hover:scale-105 shadow-lg">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Create New Article
                                </a>
                                <a href="{{ route('articles.index') }}" class="block w-full bg-slate-700 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-200 shadow-lg">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Manage My Articles
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Article Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">My Articles</h4>
                            @php
                                $userArticles = Auth::user()->articles;
                                $drafts = $userArticles->where('status', App\Enums\ArticleStatus::DRAFT)->count();
                                $pending = $userArticles->where('status', App\Enums\ArticleStatus::PENDING_REVIEW)->count();
                                $published = $userArticles->where('status', App\Enums\ArticleStatus::PUBLISHED)->count();
                                $rejected = $userArticles->where('status', App\Enums\ArticleStatus::REJECTED)->count();
                            @endphp
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total:</span>
                                    <span class="font-semibold">{{ $userArticles->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Drafts:</span>
                                    <span class="font-semibold text-gray-800">{{ $drafts }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-yellow-600">Pending:</span>
                                    <span class="font-semibold text-yellow-800">{{ $pending }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-green-600">Published:</span>
                                    <span class="font-semibold text-green-800">{{ $published }}</span>
                                </div>
                                @if($rejected > 0)
                                <div class="flex justify-between">
                                    <span class="text-red-600">Rejected:</span>
                                    <span class="font-semibold text-red-800">{{ $rejected }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Recent Articles -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Recent Articles</h4>
                            @php
                                $recentArticles = Auth::user()->articles()->latest()->take(3)->get();
                            @endphp
                            @if($recentArticles->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentArticles as $article)
                                        <div class="border-l-4 border-blue-500 pl-3">
                                            <div class="text-sm font-medium">
                                                <a href="{{ route('articles.show', $article) }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ Str::limit($article->title, 30) }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $article->status->value }} • {{ $article->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No articles yet. 
                                    <a href="{{ route('articles.create') }}" class="text-green-600 hover:text-green-800 font-medium">Create your first article!</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if(Auth::user()->isAdmin())
                <!-- Admin Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Admin Actions</h4>
                            <div class="space-y-3">
                                <a href="{{ route('admin.articles.index') }}" class="block w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-200 shadow-lg">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Manage All Articles
                                </a>
                                <a href="{{ route('admin.articles.index', ['status' => 'pending_review']) }}" class="block w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-200 shadow-lg">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Review Pending
                                </a>
                                <a href="{{ route('admin.articles.index', ['show_deleted' => 'true']) }}" class="block w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-200 shadow-lg">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Manage Deleted
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Platform Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Platform Stats</h4>
                            @php
                                $totalArticles = App\Models\Article::withTrashed()->count();
                                $activeArticles = App\Models\Article::count();
                                $totalUsers = App\Models\User::count();
                                $pendingReview = App\Models\Article::where('status', App\Enums\ArticleStatus::PENDING_REVIEW)->count();
                            @endphp
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Articles:</span>
                                    <span class="font-semibold text-gray-800">{{ $totalArticles }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Active Articles:</span>
                                    <span class="font-semibold text-blue-800">{{ $activeArticles }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Users:</span>
                                    <span class="font-semibold text-purple-800">{{ $totalUsers }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-yellow-600">Pending Review:</span>
                                    <span class="font-semibold text-yellow-800">{{ $pendingReview }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Recent Articles</h4>
                            @php
                                $recentArticles = App\Models\Article::with('user')->latest()->take(3)->get();
                            @endphp
                            @if($recentArticles->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentArticles as $article)
                                        <div class="border-l-4 border-purple-500 pl-3">
                                            <div class="text-sm font-medium">
                                                <a href="{{ route('admin.articles.show', $article) }}" class="text-purple-600 hover:text-purple-800">
                                                    {{ Str::limit($article->title, 25) }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                by {{ $article->user->name }} • {{ $article->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No articles in the system yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- System Health -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">System Status</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Database:</span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Cache:</span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Running
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Storage:</span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Available
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
