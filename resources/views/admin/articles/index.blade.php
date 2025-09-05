<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin - Article Management') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.articles.stats') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-200 ease-in-out">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    View Stats
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" class="flex flex-wrap gap-4 items-end">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="show_deleted" class="block text-sm font-medium text-gray-700 mb-1">Show Deleted</label>
                            <select name="show_deleted" id="show_deleted" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="false" {{ request('show_deleted') === 'false' || !request('show_deleted') ? 'selected' : '' }}>Active Only</option>
                                <option value="true" {{ request('show_deleted') === 'true' ? 'selected' : '' }}>Deleted Only</option>
                                <option value="with" {{ request('show_deleted') === 'with' ? 'selected' : '' }}>Include Deleted</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                Filter
                            </button>
                            <a href="{{ route('admin.articles.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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

                    @if($articles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title & Author</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($articles as $article)
                                        <tr class="{{ $article->trashed() ? 'bg-red-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $article->title }}
                                                    @if($article->trashed())
                                                        <span class="text-red-500 text-xs">(Deleted)</span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">by {{ $article->user->name }}</div>
                                                <div class="text-sm text-gray-400">{{ Str::limit($article->excerpt, 80) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $article->status->badgeClass() }}">
                                                    {{ $article->status->value }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>Created: {{ $article->created_at->format('M d, Y') }}</div>
                                                @if($article->published_at)
                                                    <div>Published: {{ $article->published_at->format('M d, Y') }}</div>
                                                @endif
                                                @if($article->trashed())
                                                    <div class="text-red-500">Deleted: {{ $article->deleted_at->format('M d, Y') }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex flex-wrap gap-1">
                                                    @if($article->trashed())
                                                        <!-- Deleted article actions -->
                                                        <form action="{{ route('admin.articles.restore', $article) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center px-2 py-1 bg-green-700 hover:bg-green-800 text-white text-xs font-medium rounded transition duration-150">
                                                                Restore
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.articles.force-delete', $article) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-800 hover:bg-red-900 text-white text-xs font-medium rounded transition duration-150" onclick="return confirm('Permanently delete this article? This cannot be undone.')">
                                                                Delete Forever
                                                            </button>
                                                        </form>
                                                    @else
                                                        <!-- Active article actions -->
                                                        <a href="{{ route('admin.articles.show', $article) }}" class="inline-flex items-center px-2 py-1 bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium rounded transition duration-150">
                                                            View
                                                        </a>
                                                        <a href="{{ route('admin.articles.edit', $article) }}" class="inline-flex items-center px-2 py-1 bg-gray-700 hover:bg-gray-800 text-white text-xs font-medium rounded transition duration-150">
                                                            Edit
                                                        </a>
                                                        
                                                        @if($article->status === App\Enums\ArticleStatus::PENDING_REVIEW)
                                                            <form action="{{ route('admin.articles.approve', $article) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" class="inline-flex items-center px-2 py-1 bg-green-700 hover:bg-green-800 text-white text-xs font-medium rounded transition duration-150">
                                                                    Approve
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.articles.reject', $article) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-700 hover:bg-red-800 text-white text-xs font-medium rounded transition duration-150">
                                                                    Reject
                                                                </button>
                                                            </form>
                                                        @endif
                                                        
                                                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center px-2 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded transition duration-150" onclick="return confirm('Delete this article?')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $articles->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 text-lg">No articles found</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
