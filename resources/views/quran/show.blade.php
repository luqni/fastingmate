<x-app-layout :hideHeader="true" :hideBottomNav="true" :noContainer="true">
    @php
        // Pages are already grouped by 'page' column from controller
        // Convert to array-like for index access in Alpine if needed, or just iterate
        // We need to keep keys if they are page numbers
        $totalPages = $pages->count();
        $pageKeys = $pages->keys()->values(); // List of available page numbers
    @endphp

    <div x-data="quranReader({ 
            totalPages: {{ $totalPages }},
            pageNumbers: @json($pageKeys),
            surahName: '{{ addslashes($title) }}',
            initialPage: {{ session('jump_to_page') ? array_search(session('jump_to_page'), $pageKeys->toArray()) : 0 }},
            nextSurahUrl: '{{ $nextSurah ? route('quran.show', $nextSurah) : '' }}',
            prevSurahUrl: '{{ $prevSurah ? route('quran.show', $prevSurah) : '' }}'
         })" 
         @keydown.window.arrow-left="prevPage"
         @keydown.window.arrow-right="nextPage"
         class="fixed inset-0 bg-[#fffcf2] overflow-hidden flex flex-col z-[100]">
        
        <!-- Header -->
        <div class="flex-none bg-[#fffcf2]/95 backdrop-blur-sm border-b border-[#e5e0d0] px-4 py-3 flex items-center justify-between shadow-sm z-[101]">
            <a href="{{ route('quran.index') }}" class="p-2 -ml-2 rounded-full hover:bg-gray-100/50 text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            
            <div class="text-center" @click="saveBookmark" class="cursor-pointer group">
                <h1 class="text-lg font-bold text-gray-900 font-serif leading-none">{{ $title }}</h1>
                <p class="text-[10px] text-gray-500 mt-1 flex items-center justify-center gap-1">
                    <span>Halaman <span x-text="pageNumbers[currentPage]"></span> / 604</span>
                    <svg class="w-3 h-3 text-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                </p>
            </div>
            
            <div class="flex items-center gap-1">
                <button @click="saveBookmark" class="p-2 rounded-full hover:bg-emerald-50 text-emerald-600 transition-colors relative" title="Tandai Terakhir Dibaca">
                    <!-- Outline icon (when no bookmark) -->
                    <svg x-show="!hasBookmark" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                    <!-- Filled icon (when bookmark exists) -->
                    <svg x-show="hasBookmark" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17 3H7a2 2 0 00-2 2v16l7-3.5L19 21V5a2 2 0 00-2-2z"></path></svg>
                    <span x-show="showBookmarkTooltip" x-transition class="absolute top-full right-0 mt-2 bg-emerald-600 text-white text-[10px] px-2 py-1 rounded shadow-lg whitespace-nowrap z-50" x-text="bookmarkTooltipText"></span>
                </button>
                <button @click="showSettings = !showSettings" class="p-2 -mr-2 rounded-full hover:bg-gray-100/50 text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </button>
            </div>
        </div>

        <!-- Settings Panel -->
        <div x-show="showSettings" 
             x-transition
             @click.away="showSettings = false"
             class="absolute top-16 right-4 left-4 z-40 bg-[#fffcf2] border border-[#e5e0d0] p-4 shadow-xl rounded-2xl" 
             style="display: none;">
            
            <!-- Mode Toggle -->
            <div class="mb-4">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-2">Mode Tampilan</span>
                <div class="flex bg-gray-200 p-1 rounded-lg">
                    <button @click="mode = 'mushaf'" :class="mode === 'mushaf' ? 'bg-white shadow-sm text-emerald-700' : 'text-gray-500'" class="flex-1 py-2 rounded-md text-xs font-bold transition-all">Mushaf Only</button>
                    <button @click="mode = 'list'" :class="mode === 'list' ? 'bg-white shadow-sm text-emerald-700' : 'text-gray-500'" class="flex-1 py-2 rounded-md text-xs font-bold transition-all">Terjemahan</button>
                </div>
            </div>

            <div class="mb-2">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ukuran Font</span>
                    <span class="text-[10px] font-bold bg-white border border-gray-200 px-2 py-0.5 rounded" x-text="['Kecil', 'Sedang', 'Besar', 'Jumbo'][fontSize-1]"></span>
                </div>
                <input type="range" min="1" max="4" step="1" x-model="fontSize" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 relative overflow-hidden touch-pan-y"
             @touchstart="touchStart"
             @torchmove="touchMove"
             @touchend="touchEnd">
             
             <!-- ... existing content ... -->
            <div class="absolute inset-0 flex transition-transform duration-300 ease-out"
                 :style="'transform: translateX(-' + (currentPage * 100) + '%)'">
                
                @foreach($pages as $pageNumber => $verses)
                    <div class="w-full h-full flex-shrink-0 overflow-y-auto px-4 py-6 pb-24">
                        <div class="max-w-3xl mx-auto min-h-full">
                             <!-- Mushaf Frame -->
                            <div class="relative bg-white border-2 border-[#d4c5a9] rounded-[2px] shadow-sm p-1 md:p-2 min-h-[70vh]">
                                 <div class="border border-[#e5e0d0] rounded-[1px] p-4 md:p-8 min-h-[70vh] bg-[url('https://www.transparenttextures.com/patterns/cream-paper.png')] flex flex-col">
                                    
                                    <!-- Header for first page of Surah only -->
                                    @if($loop->first)
                                        <div class="border-b-2 border-double border-[#d4c5a9] pb-4 mb-6 text-center">
                                            <span class="inline-block px-6 py-1 border border-[#d4c5a9] rounded-full text-xs font-serif text-gray-500 uppercase tracking-widest bg-[#fffcf2]">
                                                {{ $title }}
                                            </span>
                                        </div>

                                        @if(!in_array($title, ['At-Taubah', 'Surah At-Taubah']))
                                            <div class="text-center mb-8 mt-2 relative">
                                                <div class="absolute left-1/2 -top-4 -translate-x-1/2 w-32 h-1 bg-[#d4c5a9]/20 rounded-full blur-sm"></div>
                                                <p class="font-mushaf text-2xl md:text-3xl text-gray-800 leading-loose" style="font-feature-settings: 'cv01', 'cv02'">
                                                    بِسۡمِ ٱللَّهِ ٱلرَّحۡمَٰنِ ٱلرَّحِيمِ
                                                </p>
                                            </div>
                                        @endif
                                    @endif

                                    <!-- Verses Content -->
                                    <!-- Mushaf Mode: Continuous justified text -->
                                    <div x-show="mode === 'mushaf'" class="text-justify font-mushaf leading-[2.6] text-gray-900 flex-1" dir="rtl"
                                         :class="{ 
                                           'text-xl': fontSize == 1, 
                                           'text-2xl': fontSize == 2, 
                                           'text-3xl': fontSize == 3, 
                                           'text-4xl': fontSize == 4
                                         }">
                                        @foreach($verses as $verse)
                                            <span class="hover:bg-emerald-50 rounded transition-colors cursor-pointer select-text relative" title="{{ $verse->ayah_translation }}">
                                                {{ $verse->ayah_text_arabic }} 
                                                <span class="inline-flex items-center justify-center w-[1em] h-[1em] mx-0.5 text-[0.6em] align-middle font-sans font-bold text-[#d4c5a9] relative top-[0.1em]">
                                                    ۝<span class="absolute inset-0 flex items-center justify-center text-[0.6em] text-gray-600 font-serif pt-0.5">{{ $verse->ayah_number }}</span>
                                                </span>
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- Translation Mode: List with translation below each verse -->
                                    <div x-show="mode === 'list'" class="space-y-6 flex-1">
                                        @foreach($verses as $verse)
                                            <div class="border-b border-[#e5e0d0] pb-4 last:border-0">
                                                <!-- Arabic Text -->
                                                <div class="text-right font-mushaf leading-relaxed text-gray-900 mb-3" dir="rtl"
                                                     :class="{ 
                                                       'text-xl': fontSize == 1, 
                                                       'text-2xl': fontSize == 2, 
                                                       'text-3xl': fontSize == 3, 
                                                       'text-4xl': fontSize == 4
                                                     }">
                                                    {{ $verse->ayah_text_arabic }}
                                                    <span class="inline-flex items-center justify-center w-[1em] h-[1em] mx-1 text-[0.6em] align-middle font-sans font-bold text-[#d4c5a9]">
                                                        ۝<span class="absolute inset-0 flex items-center justify-center text-[0.6em] text-gray-600 font-serif pt-0.5">{{ $verse->ayah_number }}</span>
                                                    </span>
                                                </div>
                                                <!-- Indonesian Translation -->
                                                <div class="text-left text-sm text-gray-700 leading-relaxed font-sans" dir="ltr">
                                                    <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-bold px-2 py-0.5 rounded mr-2">{{ $verse->ayah_number }}</span>
                                                    {{ $verse->ayah_translation }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    
                                    <!-- Page Number Footer -->
                                    <div class="mt-8 pt-4 border-t border-[#e5e0d0] flex justify-between text-[10px] text-gray-400 font-serif">
                                        <span>FastingMate</span>
                                        <span>{{ $pageNumber ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <!-- Navigation Controls (Bottom Bar) -->
        <div class="flex-none bg-white border-t border-gray-100 p-4 shadow-lg-up z-40">
            <div class="max-w-3xl mx-auto flex gap-3 items-center justify-between">
                
                <button @click="prevPage" class="p-3 rounded-xl bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200 transition-colors disabled:opacity-50" :disabled="currentPage === 0 && !prevSurahUrl">
                    <svg class="w-5 h-5 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>


                <div class="flex-1 flex flex-col items-center justify-center px-5 py-3 bg-emerald-50 rounded-xl border border-emerald-200">
                    <p class="text-[10px] text-emerald-600 font-medium uppercase tracking-wider">Surah</p>
                    <p class="text-sm font-bold text-emerald-900">{{ $title }}</p>
                    @if($nextSurah)
                        <p class="text-[10px] text-emerald-600 mt-0.5">Selanjutnya: {{ $nextSurah }}</p>
                    @endif
                </div>


                <button @click="nextPage" class="p-3 rounded-xl bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200 transition-colors disabled:opacity-50" :disabled="currentPage === totalPages - 1 && !nextSurahUrl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quranReader', (config) => ({
                currentPage: config.initialPage || 0,
                totalPages: config.totalPages,
                pageNumbers: config.pageNumbers,
                showSettings: false,
                fontSize: 2, // Default Smaller
                mode: 'mushaf', // Default to Mushaf view
                showBookmarkTooltip: false,
                bookmarkTooltipText: '',
                bookmarkedPage: {{ $bookmarkedPage ?? 'null' }},
                wakeLock: null,
                get hasBookmark() {
                    return this.bookmarkedPage == this.pageNumbers[this.currentPage];
                },
                touchStartX: 0,
                touchEndX: 0,
                nextSurahUrl: config.nextSurahUrl,
                prevSurahUrl: config.prevSurahUrl,
                minSwipeDistance: 50,
                
                nextPage() {
                    if (this.currentPage < this.totalPages - 1) {
                        this.currentPage++;
                    } else if (this.nextSurahUrl) {
                        window.location.href = this.nextSurahUrl;
                    }
                },
                
                prevPage() {
                    if (this.currentPage > 0) {
                        this.currentPage--;
                    } else if (this.prevSurahUrl) {
                        window.location.href = this.prevSurahUrl;
                    }
                },
                
                touchStart(e) {
                    this.touchStartX = e.changedTouches[0].screenX;
                },
                
                touchEnd(e) {
                    this.touchEndX = e.changedTouches[0].screenX;
                    this.handleSwipe();
                },
                
                handleSwipe() {
                    const distance = this.touchStartX - this.touchEndX;
                    
                    if (Math.abs(distance) > this.minSwipeDistance) {
                        if (distance > 0) {
                            // Swiped Left -> Next
                            this.nextPage();
                        } else {
                            // Swiped Right -> Prev
                            this.prevPage();
                        }
                    }
                },

                async saveBookmark() {
                    // Current Page Number
                    const realPageNumber = this.pageNumbers[this.currentPage];
                    
                    try {
                        const response = await fetch('{{ route('quran.bookmark') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                surah_name: config.surahName,
                                ayah_number: 1, // Only tracking page/surah level loosely for now
                                page: realPageNumber
                            })
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (data.action === 'saved') {
                                this.bookmarkedPage = realPageNumber;
                            } else {
                                this.bookmarkedPage = null;
                            }
                            this.bookmarkTooltipText = data.action === 'saved' ? 'Tersimpan!' : 'Dihapus!';
                            this.showBookmarkTooltip = true;
                            setTimeout(() => this.showBookmarkTooltip = false, 2000);
                        }
                    } catch (error) {
                        console.error('Failed to toggle bookmark', error);
                    }
                },
                
                async requestWakeLock() {
                    if ('wakeLock' in navigator) {
                        try {
                            this.wakeLock = await navigator.wakeLock.request('screen');
                            
                            this.wakeLock.addEventListener('release', () => {
                                console.log('Screen Wake Lock was released');
                            });
                            console.log('Screen Wake Lock is active');
                        } catch (err) {
                            console.error(`Wake Lock error: ${err.name}, ${err.message}`);
                        }
                    } else {
                        console.log('Wake Lock API not supported contextly.');
                    }
                },
                
                init() {
                    // Pre-request wake lock
                    this.requestWakeLock();
                    
                    // Re-request if visibility changes
                    document.addEventListener('visibilitychange', async () => {
                        if (this.wakeLock !== null && document.visibilityState === 'visible') {
                            this.requestWakeLock();
                        }
                    });
                }
            }));
        });
    </script>

    <style>
        @font-face {
            font-family: 'LPMQ IsepMisbah';
            src: url('https://cdn.jsdelivr.net/gh/lpmq-isepmisbah/lpmq-isepmisbah-webfont@master/fonts/lpmq.woff2') format('woff2');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        .font-mushaf {
            font-family: 'LPMQ IsepMisbah', 'Amiri', serif;
            line-height: 2.8; /* slightly larger line height for LPMQ readability */
            font-feature-settings: 'cv01', 'cv02', 'cv03', 'cv04', 'cv05', 'cv06', 'ss01', 'ss02', 'ss03', 'ss04', 'ss05', 'ss06', 'ss07', 'ss08';
        }
    </style>
</x-app-layout>
