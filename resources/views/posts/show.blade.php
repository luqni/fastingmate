<x-app-layout :no-container="true">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artikel & Blog') }}
        </h2>
    </x-slot>

    <div class="bg-gray-50 min-h-screen" x-data="{ 
        fontSize: 100,
        shareOpen: false,
        
        increaseFont() {
            if (this.fontSize < 150) this.fontSize += 10;
        },
        decreaseFont() {
            if (this.fontSize > 80) this.fontSize -= 10;
        },
        resetFont() {
            this.fontSize = 100;
        },
        
        async share(platform) {
            const url = window.location.href;
            const text = {{ json_encode($post->title) }};
            
            if (platform === 'native') {
                if (navigator.share) {
                    try {
                        await navigator.share({
                            title: text,
                            text: text,
                            url: url
                        });
                    } catch (error) {
                        console.log('Error sharing', error);
                    }
                    return;
                }
                // Fallback to copy if native share not supported
                platform = 'copy';
            }

            let shareUrl = '';
            
            switch(platform) {
                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=${encodeURIComponent(text)}%20${encodeURIComponent(url)}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
                    break;
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                    break;
                case 'copy':
                    this.copyToClipboard(url);
                    return;
            }
            
            if(shareUrl) window.open(shareUrl, '_blank');
        },

        copyToClipboard(text) {
            // Try modern API first
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    this.showSuccessAlert();
                }).catch(() => {
                    this.fallbackCopy(text);
                });
            } else {
                this.fallbackCopy(text);
            }
        },

        fallbackCopy(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-9999px';
            textArea.style.top = '0';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
                this.showSuccessAlert();
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
                window.Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyalin',
                    text: 'Browser anda tidak mendukung fitur ini.',
                    timer: 2000
                });
            }
            
            document.body.removeChild(textArea);
        },

        showSuccessAlert() {
            window.Swal.fire({
                icon: 'success',
                title: 'Link Disalin!',
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timer: 2000
            });
        }

    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6 text-sm text-gray-500 p-6" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('posts.index') }}" class="hover:text-indigo-600 transition-colors">Blog</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900 truncate max-w-xs">{{ $post->title }}</span>
            </nav>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <!-- Main Content -->
                <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex-1 w-full">
                    @if($post->thumbnail)
                        <div class="w-full aspect-video relative">
                             <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <img src="{{ Str::startsWith($post->thumbnail, ['http', 'https']) ? $post->thumbnail : asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            <div class="absolute bottom-0 left-0 p-8 text-white">
                                <div class="flex items-center gap-3 mb-2">
                                     <span class="bg-indigo-600/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Article</span>
                                     <span class="text-white/90 text-sm font-medium">{{ $post->published_at->format('d F Y') }}</span>
                                </div>
                                <h1 class="text-3xl md:text-4xl font-bold leading-tight shadow-black drop-shadow-md">
                                    {{ $post->title }}
                                </h1>
                            </div>
                        </div>
                    @else
                        <div class="p-8 pb-0">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Article</span>
                                <span class="text-gray-500 text-sm font-medium">{{ $post->published_at->format('d F Y') }}</span>
                            </div>
                             <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
                                {{ $post->title }}
                            </h1>
                            <div class="h-1 w-20 bg-indigo-500 rounded-full"></div>
                        </div>
                    @endif
                    
                    <div class="p-8 lg:p-12">
                         <!-- Control Bar (Mobile) -->
                        <div class="lg:hidden flex items-center justify-between p-4 bg-gray-50 rounded-xl mb-8 border border-gray-100 sticky top-4 z-10 shadow-sm">
                             <div class="flex items-center gap-2">
                                <button @click="decreaseFont()" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-white rounded-lg transition-all active:scale-95" title="Perkecil Text">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                </button>
                                <span class="text-xs font-mono font-medium text-gray-400 w-10 text-center" x-text="fontSize + '%'"></span>
                                <button @click="increaseFont()" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-white rounded-lg transition-all active:scale-95" title="Perbesar Text">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                            <button @click="share('native')" class="text-sm font-medium text-indigo-600 flex items-center gap-1 active:text-indigo-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                Share
                            </button>
                        </div>

                        <!-- Content with Dynamic Font Size -->
                        @if(!$post->is_locked)
                            <div class="prose prose-lg prose-indigo max-w-none text-gray-700 leading-relaxed text-justify transition-all duration-300"
                                 :style="`font-size: ${fontSize}%`">
                                {!! $post->content !!}
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-16 px-4 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 text-center">
                                <div class="bg-indigo-50 p-4 rounded-full mb-4">
                                     <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Konten Terkunci</h3>
                                <p class="text-gray-500 max-w-md mx-auto">
                                    Artikel ini saat ini sedang terkunci. Tunggu notifikasi dari admin saat artikel ini dibuka.
                                </p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Footer -->
                    <div class="bg-gray-50 px-8 py-6 border-t border-gray-100 flex items-center justify-between">
                         <div class="flex items-center gap-3">
                             <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">A</div>
                             <div>
                                 <p class="text-sm font-bold text-gray-900">Admin FastingMate</p>
                                 <p class="text-xs text-gray-500">Editor & Penulis</p>
                             </div>
                         </div>
                         <a href="{{ route('posts.index') }}" class="text-indigo-600 text-sm font-bold hover:underline">Baca Artikel Lainnya &rarr;</a>
                    </div>
                </article>

                <!-- Sidebar Controls (Desktop) -->
                <aside class="hidden lg:block w-16 sticky top-24">
                    <div class="flex flex-col gap-4">
                        <!-- Font Controls -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 flex flex-col items-center gap-2">
                            <button @click="increaseFont()" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Perbesar Ukuran Font">
                                <span class="text-lg font-bold">A+</span>
                            </button>
                            <button @click="resetFont()" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Reset Ukuran Font">
                                <span class="text-xs font-bold">100%</span>
                            </button>
                            <button @click="decreaseFont()" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Perkecil Ukuran Font">
                                <span class="text-sm font-bold">A-</span>
                            </button>
                        </div>

                        <!-- Share Controls -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 flex flex-col items-center gap-2">
                             <button @click="share('whatsapp')" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-xl transition-all" title="Share ke WhatsApp">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.6 1.984-.052 4.015.972 5.653l-.717 2.615 4.233-1.107z"/></svg>
                            </button>
                            <button @click="share('twitter')" class="p-2 text-gray-400 hover:text-black hover:bg-gray-50 rounded-xl transition-all" title="Share ke X / Twitter">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </button>
                            <button @click="share('facebook')" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Share ke Facebook">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.791-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </button>
                             <div class="w-full h-px bg-gray-100 my-1"></div>
                             <button @click="share('copy')" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Salin Link">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
            
             <!-- Related Articles (Placeholder) -->
             <div class="mt-12 bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                 <h3 class="text-xl font-bold text-gray-900 mb-6">Artikel Lainnya</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                     <!-- This could be dynamic later -->
                     <div class="text-gray-500 text-sm italic">Fitur rekomendasi artikel akan segera hadir.</div>
                 </div>
             </div>
        </div>
    </div>
</x-app-layout>
