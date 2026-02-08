<x-app-layout :hideHeader="true" :hideBottomNav="true" :noContainer="true">
    <div x-data="dhikrApp({{ json_encode($dhikrs) }})" class="h-screen flex flex-col bg-gradient-to-br from-emerald-50 via-white to-amber-50 overflow-hidden">
        <!-- Header with Gradient -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 sticky top-0 z-30 px-6 h-16 flex items-center justify-between shadow-lg shrink-0">
            <a href="{{ route('dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-white/20 text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-white drop-shadow-sm">{{ $title }}</h1>
            <div class="w-10"></div> <!-- Spacer for centering -->
        </div>

        <!-- Progress Bar with Gradient -->
        <div class="h-1.5 bg-emerald-100 shrink-0">
            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 transition-all duration-300 shadow-sm" :style="'width: ' + progress + '%'"></div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col items-center justify-center p-4 relative overflow-hidden">
            
            <!-- Dhikr Card -->
            <div class="w-full max-w-lg relative h-full max-h-[calc(100vh-180px)]">
                <template x-for="(item, index) in dhikrs" :key="item.id">
                    <div x-show="currentIndex === index" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-x-10"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-10"
                         class="bg-white rounded-3xl shadow-2xl shadow-emerald-500/10 border-2 border-emerald-100/50 overflow-hidden relative flex flex-col h-full absolute inset-0 backdrop-blur-sm">
                        
                        <!-- Card Header with Gradient -->
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-5 py-3.5 border-b-2 border-emerald-100 flex justify-between items-center shrink-0">
                            <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest truncate max-w-[200px]" x-text="item.source"></span>
                            <span class="px-3 py-1.5 bg-white text-emerald-700 text-[10px] font-bold rounded-full border-2 border-emerald-200 shrink-0 shadow-sm">
                                <span x-text="index + 1"></span> / <span x-text="dhikrs.length"></span>
                            </span>
                        </div>

                        <!-- Card Scrollable Content -->
                        <div class="p-6 flex-1 overflow-y-auto no-scrollbar" @click="handleTap()">
                             <!-- Title & Repeat Badge -->
                             <div class="flex items-start justify-between mb-6">
                                <h2 class="text-xl font-bold bg-gradient-to-r from-emerald-700 to-teal-700 bg-clip-text text-transparent" x-text="item.title"></h2>
                                
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-[10px] font-semibold text-emerald-600">Target:</span>
                                    <span class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 text-white text-xs font-bold flex items-center justify-center shadow-md" x-text="item.repeat"></span>
                                </div>
                             </div>


                             <!-- Arabic with Decorative Border -->
                             <div class="relative mb-6">
                                <div class="absolute inset-0 bg-gradient-to-br from-amber-100/50 to-emerald-100/50 rounded-2xl blur-sm"></div>
                                <div class="relative text-justify px-6 py-8 bg-gradient-to-br from-[#fffcf2] to-[#fef8e8] rounded-2xl border-2 border-[#d4c5a9] shadow-lg">
                                    <div class="absolute top-2 left-2 w-4 h-4 border-t-2 border-l-2 border-[#d4c5a9] rounded-tl-lg"></div>
                                    <div class="absolute top-2 right-2 w-4 h-4 border-t-2 border-r-2 border-[#d4c5a9] rounded-tr-lg"></div>
                                    <div class="absolute bottom-2 left-2 w-4 h-4 border-b-2 border-l-2 border-[#d4c5a9] rounded-bl-lg"></div>
                                    <div class="absolute bottom-2 right-2 w-4 h-4 border-b-2 border-r-2 border-[#d4c5a9] rounded-br-lg"></div>
                                    <p class="text-3xl md:text-4xl font-mushaf leading-[2.6] text-gray-900" 
                                       dir="rtl" 
                                       style="font-feature-settings: 'cv01', 'cv02'; text-rendering: optimizeLegibility; -webkit-font-smoothing: antialiased;"
                                       x-text="item.arabic"></p>
                                </div>
                             </div>

                             <!-- Latin & Translation with Enhanced Styling -->
                             <div class="space-y-4 pb-4">
                                <p class="text-sm font-semibold text-teal-700 italic leading-relaxed bg-teal-50/50 px-4 py-3 rounded-xl border border-teal-100" x-text="item.latin"></p>
                                <p class="text-sm text-gray-700 leading-relaxed px-2" x-text="item.translation"></p>
                                
                                <!-- Dalil / Info with Gradient -->
                                <div class="mt-4 p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border-2 border-amber-200/50 flex gap-3 items-start shadow-sm">
                                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-xs text-amber-900 leading-relaxed font-medium" x-text="item.dalil"></p>
                                </div>
                             </div>
                        </div>

                        <!-- Tap Area / Counter with Enhanced Design -->
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border-t-2 border-emerald-100 p-5 shrink-0 z-10">
                             <button @click="handleTap()" 
                                     class="w-full h-24 rounded-2xl flex items-center justify-center gap-4 transition-all duration-200 transform active:scale-95 touch-manipulation select-none relative overflow-hidden"
                                     :class="isCompleted ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-2xl shadow-emerald-500/40' : 'bg-white border-3 border-emerald-200 text-emerald-700 hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-500/20'">
                                
                                <!-- Ripple effect background -->
                                <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-active:translate-x-[100%] transition-transform duration-700"></div>
                                
                                <div x-show="!isCompleted" class="text-center relative z-10">
                                    <span class="block text-xs font-bold uppercase tracking-widest opacity-70 mb-1">Ketuk Layar</span>
                                    <div class="text-4xl font-black">
                                        <span x-text="currentCount"></span> <span class="text-xl font-medium opacity-60 mx-1">/</span> <span class="text-2xl opacity-60" x-text="item.repeat"></span>
                                    </div>
                                </div>

                                <div x-show="isCompleted" class="flex items-center gap-3 relative z-10">
                                     <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                     <span class="text-xl font-bold">Selesai!</span>
                                </div>
                             </button>
                             <p class="text-center text-xs text-emerald-700 mt-3 font-medium">Ketuk tombol atau area kartu untuk menghitung</p>
                        </div>
                    </div>
                </template>
                
                <!-- Completed All State with Enhanced Design -->
                <div x-show="finishedAll" 
                     class="bg-gradient-to-br from-white via-emerald-50 to-teal-50 rounded-3xl p-10 shadow-2xl text-center border-2 border-emerald-200 flex flex-col items-center justify-center absolute inset-0 h-full"
                     style="display: none;">
                    <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center mb-6 mx-auto animate-bounce shadow-2xl shadow-emerald-500/40">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-emerald-700 to-teal-700 bg-clip-text text-transparent mb-3">Alhamdulillah!</h2>
                    <p class="text-sm text-gray-600 mb-8 max-w-xs">Anda telah menyelesaikan dzikir {{ $type === 'morning' ? 'pagi' : 'petang' }} hari ini. Semoga menjadi amal yang diterima.</p>
                    <a href="{{ route('dashboard') }}" class="px-10 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-2xl shadow-2xl shadow-emerald-600/40 hover:shadow-emerald-600/60 hover:scale-105 transition-all w-full max-w-xs">Kembali ke Dashboard</a>
                </div>

            </div>
        </div>

        <!-- Navigation Controls with Enhanced Design -->
        <div class="bg-white/80 backdrop-blur-md border-t-2 border-emerald-100 p-5 pb-6 z-40 shrink-0 shadow-lg" x-show="!finishedAll">
            <div class="max-w-lg mx-auto flex gap-4">
                <button @click="prev()" 
                        class="px-6 py-3.5 rounded-xl font-bold text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all flex-1 border-2 border-transparent text-sm disabled:opacity-40 disabled:cursor-not-allowed"
                        :class="{ 'opacity-50 cursor-not-allowed': currentIndex === 0 }"
                        :disabled="currentIndex === 0">
                    ← Sebelumnya
                </button>

                <!-- Next Button with Gradient -->
                <button @click="next()" 
                        class="px-6 py-3.5 rounded-xl font-bold transition-all flex-[2] shadow-lg flex items-center justify-center gap-2 text-sm"
                        :class="isCompleted ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white hover:shadow-2xl hover:shadow-emerald-600/40 hover:scale-105' : 'bg-white border-2 border-gray-200 text-gray-400 hover:bg-gray-50'">
                    <span x-text="isCompleted ? 'Selanjutnya' : 'Lewati'"></span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
             // ... existing script is updated below in full replacement
            Alpine.data('dhikrApp', (data) => ({
                dhikrs: data,
                currentIndex: 0,
                currentCount: 0,
                finishedAll: false,

                get currentItem() {
                    return this.dhikrs[this.currentIndex];
                },

                get isCompleted() {
                    return this.currentCount >= this.currentItem.repeat;
                },

                get progress() {
                    if (this.finishedAll) return 100;
                    return ((this.currentIndex) / this.dhikrs.length) * 100;
                },

                handleTap() {
                    if (this.isCompleted) return;
                    
                    if (navigator.vibrate) navigator.vibrate(50);
                    
                    this.currentCount++;
                    
                    if (this.currentCount === this.currentItem.repeat) {
                        if (navigator.vibrate) navigator.vibrate([50, 50, 50]);
                    }
                },

                next() {
                    if (this.currentIndex < this.dhikrs.length - 1) {
                        this.currentIndex++;
                        this.currentCount = 0;
                        // Scroll card content to top, but card container stays fixed
                        // Actually no need to scroll window, just reset card scroll
                        const cardContent = document.querySelector('.overflow-y-auto');
                        if(cardContent) cardContent.scrollTop = 0;
                    } else {
                        this.finishedAll = true;
                        if (navigator.vibrate) navigator.vibrate([100, 50, 100, 50, 200]);
                    }
                },

                prev() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                        // Reset to max count to avoid re-tapping
                        this.currentCount = this.dhikrs[this.currentIndex].repeat;
                        this.finishedAll = false;
                    }
                }
            }));
        });
    </script>
</x-app-layout>
