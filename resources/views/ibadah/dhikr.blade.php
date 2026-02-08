<x-app-layout :hideHeader="true" :hideBottomNav="true" :noContainer="true">
    <div x-data="dhikrApp({{ json_encode($dhikrs) }})" class="h-screen flex flex-col bg-gray-50 overflow-hidden">
        <!-- Header -->
        <div class="bg-white sticky top-0 z-30 border-b border-gray-100 px-6 h-16 flex items-center justify-between shadow-sm shrink-0">
            <a href="{{ route('dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-gray-100 text-gray-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">{{ $title }}</h1>
            <div class="w-10"></div> <!-- Spacer for centering -->
        </div>

        <!-- Progress Bar -->
        <div class="h-1 bg-gray-200 shrink-0">
            <div class="h-full bg-primary-600 transition-all duration-300" :style="'width: ' + progress + '%'"></div>
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
                         class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden relative flex flex-col h-full absolute inset-0">
                        
                        <!-- Card Header -->
                        <div class="bg-gray-50/50 px-5 py-3 border-b border-gray-100 flex justify-between items-center shrink-0">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest truncate max-w-[200px]" x-text="item.source"></span>
                            <span class="px-2 py-1 bg-primary-50 text-primary-700 text-[10px] font-bold rounded-full border border-primary-100 shrink-0">
                                <span x-text="index + 1"></span> / <span x-text="dhikrs.length"></span>
                            </span>
                        </div>

                        <!-- Card Scrollable Content -->
                        <div class="p-6 flex-1 overflow-y-auto no-scrollbar" @click="handleTap()">
                             <!-- Title & Repeat Badge -->
                             <div class="flex items-start justify-between mb-4">
                                <h2 class="text-lg font-bold text-gray-800" x-text="item.title"></h2>
                                
                                <div class="flex items-center gap-1 shrink-0">
                                    <span class="text-[10px] font-medium text-gray-400">Target:</span>
                                    <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 text-xs font-bold flex items-center justify-center" x-text="item.repeat"></span>
                                </div>
                             </div>


                             <!-- Arabic -->
                             <div class="text-justify mb-6 px-4 bg-[#fffcf2] py-6 rounded-2xl border border-[#e5e0d0]">
                                <p class="text-3xl md:text-4xl font-mushaf leading-[2.6] text-gray-900" 
                                   dir="rtl" 
                                   style="font-feature-settings: 'cv01', 'cv02'; text-rendering: optimizeLegibility; -webkit-font-smoothing: antialiased;"
                                   x-text="item.arabic"></p>
                             </div>

                             <!-- Latin & Translation -->
                             <div class="space-y-3 pb-4">
                                <p class="text-sm font-medium text-primary-600 italic leading-relaxed" x-text="item.latin"></p>
                                <p class="text-sm text-gray-600 leading-relaxed" x-text="item.translation"></p>
                                
                                <!-- Dalil / Info -->
                                <div class="mt-4 p-3 bg-gray-50 rounded-xl border border-gray-100 flex gap-3 items-start">
                                    <svg class="w-4 h-4 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-[10px] text-gray-500 leading-snug" x-text="item.dalil"></p>
                                </div>
                             </div>
                        </div>

                        <!-- Tap Area / Counter (Fixed at bottom of card) -->
                        <div class="bg-gray-50 border-t border-gray-100 p-4 shrink-0 z-10">
                             <button @click="handleTap()" 
                                     class="w-full h-20 rounded-xl flex items-center justify-center gap-4 transition-all duration-100 transform active:scale-95 touch-manipulation select-none"
                                     :class="isCompleted ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-white border-2 border-primary-100 text-primary-600 hover:border-primary-300 hover:bg-primary-50'">
                                
                                <div x-show="!isCompleted" class="text-center">
                                    <span class="block text-[10px] font-bold uppercase tracking-widest opacity-60 mb-0.5">Ketuk Layar</span>
                                    <div class="text-3xl font-black">
                                        <span x-text="currentCount"></span> <span class="text-lg font-medium opacity-50 mx-0.5">/</span> <span class="text-xl opacity-50" x-text="item.repeat"></span>
                                    </div>
                                </div>

                                <div x-show="isCompleted" class="flex items-center gap-2">
                                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                     <span class="text-lg font-bold">Selesai</span>
                                </div>
                             </button>
                             <p class="text-center text-[10px] text-gray-400 mt-2 font-medium">Ketuk tombol atau area kartu untuk menghitung</p>
                        </div>
                    </div>
                </template>
                
                <!-- Completed All State -->
                <div x-show="finishedAll" 
                     class="bg-white rounded-[2rem] p-8 shadow-xl text-center border border-gray-100 flex flex-col items-center justify-center absolute inset-0 h-full"
                     style="display: none;">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mb-6 mx-auto animate-bounce">
                        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Alhamdulillah!</h2>
                    <p class="text-sm text-gray-500 mb-8">Anda telah menyelesaikan dzikir {{ $type === 'morning' ? 'pagi' : 'petang' }} hari ini.</p>
                    <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-primary-600 text-white font-bold rounded-xl shadow-lg shadow-primary-600/30 hover:bg-primary-700 transition w-full">Kembali ke Dashboard</a>
                </div>

            </div>
        </div>

        <!-- Navigation Controls -->
        <div class="bg-white border-t border-gray-100 p-4 pb-6 glass z-40 shrink-0" x-show="!finishedAll">
            <div class="max-w-lg mx-auto flex gap-4">
                <button @click="prev()" 
                        class="px-5 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition flex-1 border border-transparent text-sm"
                        :class="{ 'opacity-50 cursor-not-allowed': currentIndex === 0 }"
                        :disabled="currentIndex === 0">
                    Sebelumnya
                </button>

                <!-- Next Button -->
                <button @click="next()" 
                        class="px-5 py-3 rounded-xl font-bold transition flex-[2] shadow-lg flex items-center justify-center gap-2 text-sm"
                        :class="isCompleted ? 'bg-primary-600 text-white hover:bg-primary-700 shadow-primary-600/30' : 'bg-white border-2 border-gray-100 text-gray-400 hover:bg-gray-50'">
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
