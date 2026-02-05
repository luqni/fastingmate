<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artikel & Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-8">
                <form method="GET" action="{{ route('posts.index') }}" class="relative">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Cari artikel berdasarkan judul..." 
                            class="w-full pl-12 pr-32 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center gap-2 pr-2">
                            @if(request('search'))
                                <a href="{{ route('posts.index') }}" class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 font-medium">
                                    Clear
                                </a>
                            @endif
                            <button type="submit" class="px-4 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                                Search
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Search Results Info -->
                @if(request('search'))
                    <div class="mt-3 text-sm text-gray-600">
                        Menampilkan <span class="font-semibold text-gray-900">{{ $posts->total() }}</span> artikel untuk "<span class="font-semibold text-indigo-600">{{ request('search') }}</span>"
                    </div>
                @else
                    <div class="mt-3 text-sm text-gray-600">
                        Menampilkan <span class="font-semibold text-gray-900">{{ $posts->total() }}</span> artikel
                    </div>
                @endif
            </div>

            <!-- Blog Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                <a href="{{ route('posts.show', $post) }}" class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full">
                    <div class="aspect-video w-full bg-gray-100 relative overflow-hidden">
                        @if($post->is_locked)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-10 backdrop-blur-[2px]">
                                <div class="bg-white/20 backdrop-blur-md border border-white/30 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                            </div>
                        @endif
                        @if($post->thumbnail)
                            <img src="{{ Str::startsWith($post->thumbnail, ['http', 'https']) ? $post->thumbnail : asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-3 flex-wrap">
                            <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">Article</span>
                            <span class="text-xs text-gray-400">{{ $post->published_at->format('d M Y') }}</span>
                            <span class="flex items-center gap-1 text-xs text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $post->reading_time }}
                            </span>
                            <span class="flex items-center gap-1 text-xs text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                {{ number_format($post->views_count) }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                            {{ $post->title }}
                        </h3>
                        <p class="text-sm text-gray-500 line-clamp-3 mb-4 flex-grow">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>
                        <div class="flex items-center text-sm font-medium text-indigo-600 group-hover:translate-x-1 transition-transform">
                            Baca Selengkapnya
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Empty State -->
            @if($posts->isEmpty())
                <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    @if(request('search'))
                        <h3 class="text-lg font-medium text-gray-900">Tidak ada artikel ditemukan</h3>
                        <p class="text-gray-500 mt-1">Coba kata kunci lain atau <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:underline">lihat semua artikel</a></p>
                    @else
                        <h3 class="text-lg font-medium text-gray-900">Belum ada artikel</h3>
                        <p class="text-gray-500 mt-1">Nantikan artikel menarik dari kami.</p>
                    @endif
                </div>
            @endif

            <!-- Pagination -->
            @if($posts->hasPages())
            <div class="mt-8">
                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 rounded-lg shadow-sm">
                    <div class="flex flex-1 justify-between sm:hidden">
                        @if ($posts->onFirstPage())
                            <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-400">Previous</span>
                        @else
                            <a href="{{ $posts->previousPageUrl() }}" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                        @endif

                        @if ($posts->hasMorePages())
                            <a href="{{ $posts->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                        @else
                            <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-400">Next</span>
                        @endif
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Menampilkan
                                <span class="font-medium">{{ $posts->firstItem() }}</span>
                                sampai
                                <span class="font-medium">{{ $posts->lastItem() }}</span>
                                dari
                                <span class="font-medium">{{ $posts->total() }}</span>
                                artikel
                            </p>
                        </div>
                        <div>
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
