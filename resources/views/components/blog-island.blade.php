<div x-data="blogIsland()" x-init="init()" x-cloak
     x-show="visible && !tadabburExpanded"
     @tadabbur-expanded.window="tadabburExpanded = true"
     @tadabbur-collapsed.window="tadabburExpanded = false"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     class="fixed z-[60] bottom-[6.5rem] right-4 md:bottom-24 md:right-8 flex justify-center pointer-events-none transition-all duration-300">
    
    <div class="pointer-events-auto bg-white/90 backdrop-blur-xl border border-blue-100 text-gray-800 rounded-full pl-1 pr-1 py-1 shadow-xl shadow-blue-900/10 flex items-center gap-1.5 sm:gap-2 transition-all duration-500 hover:scale-105 group max-w-[calc(100vw-2rem)]"
         :class="{'pr-2.5 pl-2.5 sm:pr-4 sm:pl-4': expanded, 'pr-2': !expanded}">
        
        <!-- Icon -->
        <a href="{{ route('posts.index') }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-600 flex items-center justify-center shrink-0 relative overflow-hidden transition-all duration-300 hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-tr from-blue-400 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </a>

        <!-- Collapsed Content -->
        <div class="flex flex-col cursor-pointer" x-show="!expanded" @click="toggleExpand()">
            <span class="text-[10px] text-blue-600 font-bold uppercase tracking-wider leading-none mb-0.5">Wawasan</span>
            <span class="text-xs font-bold leading-none text-gray-700">Islami</span>
        </div>

        <!-- Expanded Content (Detail) -->
        <div x-show="expanded" class="flex items-center gap-2 sm:gap-4 transition-all duration-300 overflow-hidden" 
             style="display: none;">
            
            <div class="flex flex-col shrink-0">
                <span class="hidden sm:block text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Artikel & Info</span>
                <span class="text-xs sm:text-sm font-bold text-gray-800 whitespace-nowrap">
                    Wawasan Islami
                </span>
            </div>

            <div class="h-6 sm:h-8 w-px bg-gray-200 shrink-0"></div>

            <a href="{{ route('posts.index') }}" class="py-1 px-3 sm:py-1.5 sm:px-4 shrink-0 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[10px] sm:text-xs font-bold rounded-full hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md shadow-blue-600/30 whitespace-nowrap active:scale-95">
                Baca Sekarang
            </a>
        </div>

        <!-- Toggle & Dismiss Buttons -->
        <button @click.stop="toggleExpand()" class="w-6 h-6 sm:w-8 sm:h-8 shrink-0 rounded-full bg-black/5 hover:bg-black/10 flex items-center justify-center transition-colors ml-0.5 sm:ml-1" x-show="!expanded" aria-label="Expand">
             <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
        <button @click.stop="toggleExpand()" class="w-6 h-6 sm:w-8 sm:h-8 shrink-0 rounded-full bg-black/5 hover:bg-black/10 flex items-center justify-center transition-colors ml-0.5 sm:ml-1" x-show="expanded" aria-label="Collapse">
             <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <!-- Dismiss Button -->
        <button @click.stop="dismiss()" class="w-5 h-5 sm:w-6 sm:h-6 shrink-0 rounded-full bg-red-50 hover:bg-red-100 flex items-center justify-center transition-colors ml-0.5" aria-label="Dismiss">
             <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>

<script>
function blogIsland() {
    return {
        visible: false,
        expanded: false,
        tadabburExpanded: false,
        
        init() {
            // Only show if not on the blog page 
            const path = window.location.pathname;
            if (!path.startsWith('/blog')) {
                // Short delay to orchestrate entrance animation
                setTimeout(() => {
                    this.visible = true;
                }, 800);
            }
        },

        toggleExpand() {
            this.expanded = !this.expanded;
        },

        dismiss() {
            this.visible = false;
        }
    }
}
</script>
