@props(['alwaysVisible' => false])

@php
    $emotions = config('emotion_verses');
@endphp

<div x-data="healingIsland({{ json_encode($alwaysVisible ?? false) }})" 
     class="fixed bottom-6 inset-x-0 mx-auto z-[60] flex justify-center pointer-events-none"
     :class="{'z-[60]': expanded, 'z-[40]': !expanded}"
     x-cloak>
    
    <div class="pointer-events-auto bg-black text-white shadow-2xl transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] overflow-hidden"
         :class="expanded ? 'w-[90vw] max-w-lg rounded-[2rem] p-6' : (alwaysVisible ? 'w-auto rounded-full px-5 py-3 cursor-pointer hover:scale-105' : 'w-0 h-0 p-0 overflow-hidden opacity-0')"
         @click="(!expanded && alwaysVisible) && expand()">
        
        <!-- Collapsed State -->
        <div x-show="!expanded && alwaysVisible" class="flex items-center gap-2">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            <span class="font-bold text-sm whitespace-nowrap">Butuh Ketenangan?</span>
        </div>

        <!-- Expanded State -->
        <div x-show="expanded" class="w-full flex flex-col items-center">
            <!-- Header -->
            <div class="w-full flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                     <div class="w-8 h-8 rounded-full bg-emerald-900/50 flex items-center justify-center text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                     </div>
                    <span class="font-bold text-lg">Healing Corner</span>
                </div>
                <button @click.stop="collapse()" class="p-2 rounded-full bg-gray-800 hover:bg-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Mood Selection -->
            <div x-show="!selectedMood" class="w-full" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <p class="text-gray-400 text-center mb-4 text-sm">Apa yang sedang kamu rasakan?</p>
                <div class="grid grid-cols-3 gap-3">
                    @foreach($emotions as $key => $data)
                        <button @click="selectMood('{{ $key }}')" class="bg-gray-800 hover:bg-gray-700 p-3 rounded-xl flex flex-col items-center gap-1 transition-all hover:scale-105 active:scale-95 border border-gray-700">
                            <span class="text-2xl">{{ $data['emoji'] }}</span>
                            <span class="text-xs font-medium text-gray-300">{{ $data['label'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Verse Display -->
            <div x-show="selectedMood" class="w-full text-center" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mb-4 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-900/30 text-emerald-400 text-[10px] font-bold uppercase tracking-wider border border-emerald-900/50">
                    <span x-text="currentVerse.surah"></span> : <span x-text="currentVerse.ayah"></span>
                </div>

                <h3 class="font-amiri text-2xl text-white leading-[1.8] mb-4" x-text="currentVerse.arabic" style="direction: rtl;"></h3>
                
                <div class="space-y-2 mb-6 text-left bg-gray-900/50 p-4 rounded-xl border border-gray-800">
                     <p class="text-emerald-400 font-medium italic text-xs leading-relaxed" x-text="currentVerse.latin"></p>
                     <p class="text-gray-300 text-sm leading-relaxed" x-text="'&quot;' + currentVerse.translation + '&quot;'"></p>
                </div>

                <div class="flex gap-2">
                    <button @click="share()" class="flex-1 py-2.5 bg-white text-black rounded-lg font-bold text-sm hover:bg-gray-200 transition flex items-center justify-center gap-2">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        Share
                    </button>
                     <button @click="resetMood()" class="px-4 py-2.5 bg-gray-800 text-white rounded-lg font-semibold text-sm hover:bg-gray-700 transition">
                        Pilih Emosi Lain
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Global function to expand
    window.expandHealingIsland = function() {
        console.log('Dispatching expand-healing-island event');
        window.dispatchEvent(new CustomEvent('expand-healing-island'));
    }

    window.healingIsland = function(alwaysVisible) {
        return {
            expanded: false,
            alwaysVisible: alwaysVisible,
            emotions: @json($emotions),
            selectedMood: null,
            currentVerse: {},

            init() {
                console.log('Healing Island Component Initialized');
                window.addEventListener('expand-healing-island', () => {
                    console.log('Event received in component');
                    this.expand();
                });
            },

            expand() {
                console.log('Component expanding...');
                this.expanded = true;
                window.dispatchEvent(new CustomEvent('healing-island-expanded', { detail: true }));
            },

            collapse() {
                this.expanded = false;
                window.dispatchEvent(new CustomEvent('healing-island-expanded', { detail: false }));
                setTimeout(() => {
                    this.selectedMood = null;
                }, 300);
            },

            selectMood(key) {
                this.selectedMood = key;
                const verses = this.emotions[key].verses;
                const randomIndex = Math.floor(Math.random() * verses.length);
                this.currentVerse = verses[randomIndex];
            },

            resetMood() {
                this.selectedMood = null;
            },

            async share() {
                const text = `"${this.currentVerse.translation}" (QS. ${this.currentVerse.surah}: ${this.currentVerse.ayah})\n\nFastingMate - Teman Ibadah Muslimah`;
                
                if (navigator.share) {
                    try {
                        await navigator.share({
                            title: 'Mood Booster Ayat',
                            text: text,
                            url: window.location.origin,
                            
                        });
                    } catch (err) {
                        console.log('Error sharing:', err);
                    }
                } else {
                    navigator.clipboard.writeText(text).then(() => {
                        alert('Ayat berhasil disalin ke clipboard!');
                    });
                }
            }
        }
    }
</script>
