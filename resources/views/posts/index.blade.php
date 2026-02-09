<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artikel & Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Featured & Trending Section (Only visible when not searching) -->
            @if(!request('search'))
                <!-- Featured Hero Section -->
                @if(isset($featuredPost) && $featuredPost)
                <div class="mb-12">
                    <a href="{{ route('posts.show', $featuredPost) }}" class="group relative block w-full overflow-hidden rounded-[2rem] bg-gray-900 shadow-2xl transition-all hover:shadow-3xl hover:-translate-y-1">
                        <!-- Image & Overlay -->
                        <div class="absolute inset-0">
                            @if($featuredPost->thumbnail)
                                <img src="{{ Str::startsWith($featuredPost->thumbnail, ['http', 'https']) ? $featuredPost->thumbnail : asset('storage/' . $featuredPost->thumbnail) }}" alt="{{ $featuredPost->title }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-50 group-hover:opacity-40">
                            @else
                                <div class="h-full w-full bg-gradient-to-br from-indigo-900 to-purple-900 opacity-80"></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
                            
                            @if($featuredPost->is_locked)
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center z-10 backdrop-blur-[2px]">
                                    <div class="bg-white/20 backdrop-blur-md border border-white/30 p-4 rounded-full shadow-lg shadow-black/20">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="relative flex h-full min-h-[450px] flex-col justify-end p-6 sm:p-10 md:p-12">
                            <!-- Badges -->
                            <div class="mb-6 flex flex-wrap gap-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-600 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-red-600/20">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.45-.412-1.725a1 1 0 00-2 0 10.08 10.08 0 002.77 8.041 9.94 9.94 0 004.814 2.65 9.98 9.98 0 001.35.093c4.78 0 8.814-3.511 9.274-8.067a1 1 0 00-1.871-.7 8.028 8.028 0 01-3.6 5.517 7.962 7.962 0 01-3.626 1.258 7.968 7.968 0 01-1.396.118c-.805 0-1.58-.1-2.316-.279C14.18 13.91 14.71 11.58 14.71 9.08c0-2.454-.536-4.707-1.49-6.527zM7.184 16.518A7.957 7.957 0 016.035 12.6c.099.522.253 1.03.46 1.516.29.684.773 1.284 1.396 1.708.203.14.417.262.642.366-.462.115-.929.204-1.399.328h.05z" clip-rule="evenodd" /></svg>
                                    Paling Banyak Dibaca
                                </span>
                                <span class="rounded-full bg-white/20 px-4 py-1.5 text-xs font-bold text-white backdrop-blur-md border border-white/10">
                                    {{ $featuredPost->published_at->format('d M Y') }}
                                </span>
                            </div>

                            <h2 class="mb-6 text-3xl font-extrabold leading-tight text-white sm:text-4xl md:text-5xl lg:text-6xl drop-shadow-sm">
                                {{ $featuredPost->title }}
                            </h2>

                            <p class="mb-8 max-w-3xl text-lg sm:text-xl text-gray-200 line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($featuredPost->content), 150) }}
                            </p>

                            <!-- Footer Info -->
                            <div class="flex flex-wrap items-center gap-6 text-sm font-medium text-gray-300">
                                <div class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm border border-white/5">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    {{ number_format($featuredPost->views_count) }} views
                                </div>
                                 <div class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm border border-white/5">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $featuredPost->reading_time }}
                                </div>
                                <div class="flex items-center gap-1 group-hover:translate-x-1 transition-transform text-white font-bold ml-auto sm:ml-0">
                                    Baca Selengkapnya
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                <!-- Trending Carousel -->
                @if(isset($trendingPosts) && $trendingPosts->count() > 0)
                <div class="mb-14">
                    <div class="flex items-center justify-between mb-6 px-1">
                        <h3 class="text-2xl font-extrabold text-gray-900 flex items-center gap-2">
                            <span class="text-2xl animate-pulse">⚡</span> Sedang Hangat
                        </h3>
                    </div>
                    
                    <div class="flex overflow-x-auto gap-5 pb-8 -mx-4 px-4 sm:mx-0 sm:px-0 snap-x hide-scrollbar scroll-smooth">
                        @foreach($trendingPosts as $index => $post)
                        <a href="{{ route('posts.show', $post) }}" class="snap-center shrink-0 w-[280px] sm:w-[320px] group relative flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                           <!-- Thumbnail -->
                           <div class="aspect-[16/10] w-full bg-gray-100 relative overflow-hidden">
                                @if($post->is_locked)
                                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-20 backdrop-blur-[2px]">
                                        <div class="bg-white/20 backdrop-blur-md border border-white/30 p-2.5 rounded-full shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                    </div>
                                @endif
                                @if($post->thumbnail)
                                    <img src="{{ Str::startsWith($post->thumbnail, ['http', 'https']) ? $post->thumbnail : asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3 z-10 px-2.5 py-1 bg-white/90 backdrop-blur-md rounded-lg text-xs font-extrabold shadow-sm border border-gray-100/50 flex items-center gap-1">
                                    <span class="text-indigo-600">#{{ $index + 2 }}</span> Popular
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                           </div>
                           
                           <!-- Content -->
                           <div class="p-5 flex flex-col flex-1">
                               <div class="flex items-center gap-2 mb-3 text-xs font-medium text-gray-500">
                                   <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600">{{ $post->published_at->format('d M') }}</span>
                                   <span>•</span>
                                   <span class="flex items-center gap-1">
                                       <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                       {{ number_format($post->views_count) }}
                                   </span>
                               </div>
                               <h4 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 leading-snug group-hover:text-indigo-600 transition-colors">
                                   {{ $post->title }}
                               </h4>
                           </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            @endif

            <!-- Search Bar & List Header -->
            <div class="mb-8" id="latest-articles">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">
                            @if(request('search'))
                                Hasil Pencarian
                            @else
                                Artikel Terbaru
                            @endif
                        </h3>
                        <p class="text-gray-500 text-sm">Temukan wawasan islami bermanfaat untukmu</p>
                    </div>
                    
                    <form method="GET" action="{{ route('posts.index') }}" class="relative w-full md:w-96">
                         <!-- Maintain anchor to latest articles when searching or clearing -->
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
                                placeholder="Cari artikel..." 
                                class="w-full pl-11 pr-24 py-3 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center gap-1 pr-1.5">
                                @if(request('search'))
                                    <a href="{{ route('posts.index') }}" class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </a>
                                @endif
                                <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Search Results Info (Only if searching) -->
                @if(request('search'))
                    <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100 flex items-center justify-between">
                        <span class="text-sm text-indigo-800">
                            Ditemukan <span class="font-bold">{{ $posts->total() }}</span> artikel untuk "<span class="font-bold">{{ request('search') }}</span>"
                        </span>
                        <a href="{{ route('posts.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Reset Pencarian</a>
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
