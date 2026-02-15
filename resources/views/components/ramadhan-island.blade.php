<div x-data="ramadhanIsland()" x-init="init()" x-cloak
     x-show="visible"
     class="fixed z-[60] bottom-24 left-4 right-4 md:left-auto md:right-8 md:bottom-24 md:w-auto flex justify-center pointer-events-none transition-all duration-300">
    
    <div class="pointer-events-auto bg-gray-900/90 backdrop-blur-xl border border-white/10 text-white rounded-full pl-1 pr-1 py-1 shadow-2xl shadow-emerald-900/40 flex items-center gap-2 transition-all duration-500 animate-slide-up hover:scale-105 group"
         :class="{'pr-4 pl-4': expanded, 'pr-2': !expanded}">
        
        <!-- Icon / Status -->
        <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center shrink-0 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-tr from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <span class="text-lg relative z-10">🌙</span>
            
            <!-- Pulse if close to time -->
            <div class="absolute inset-0 rounded-full border-2 border-emerald-400 opacity-0 animate-ping" x-show="isUrgent"></div>
        </div>

        <!-- Collapsed Content -->
        <div class="flex flex-col" x-show="!expanded" @click="toggleExpand()">
            <span class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider leading-none mb-0.5" x-text="statusLabel"></span>
            <span class="text-xs font-bold leading-none" x-text="timer"></span>
        </div>

        <!-- Expanded Content (Detail) -->
        <div x-show="expanded" class="flex items-center gap-4 transition-all duration-300 overflow-hidden" 
             style="display: none;">
            
            <div class="flex flex-col">
                <span class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">Ramadhan 1447 H</span>
                <span class="text-sm font-bold text-white whitespace-nowrap">
                    Hari ke-<span x-text="day"></span>
                </span>
            </div>

            <div class="h-8 w-px bg-white/10"></div>

            <div class="flex flex-col text-right">
                <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider mb-0.5" x-text="nextEventName"></span>
                <span class="text-sm font-mono text-white" x-text="timer"></span>
            </div>

            <a href="{{ route('ramadhan.poster') }}" class="ml-2 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors" title="Lihat Jadwal Lengkap">
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <!-- Toggle Button -->
        <button @click="toggleExpand()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition-colors ml-1" x-show="!expanded">
             <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
        <button @click="toggleExpand()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition-colors ml-1" x-show="expanded">
             <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>

<script>
function ramadhanIsland() {
    return {
        visible: false,
        expanded: false,
        isUrgent: false,
        data: null,
        timer: 'Loading...',
        statusLabel: 'Checking...',
        day: 1,
        nextEventName: 'Loading...',
        
        init() {
            this.fetchData();
            // Refresh every minute to check schedule changes
            setInterval(() => this.fetchData(), 60000);
            // Update timer every second
            setInterval(() => this.updateTimer(), 1000);
        },

        async fetchData() {
            try {
                const res = await fetch('{{ route('ramadhan.current') }}');
                const data = await res.json();
                
                if (data.active) {
                    this.visible = true;
                    this.data = data;
                    if (data.type === 'in_ramadhan') {
                        this.day = data.day;
                    }
                    this.updateTimer();
                } else {
                    this.visible = false;
                }
            } catch (e) {
                console.error('Ramadhan Widget Error', e);
            }
        },

        updateTimer() {
            if (!this.data) return;

            if (this.data.type === 'countdown_to_ramadhan') {
                this.statusLabel = 'Menuju Ramadhan';
                this.timer = this.data.days_left + ' Hari Lagi';
                this.nextEventName = '1 Ramadhan';
                return;
            }

            if (this.data.type === 'in_ramadhan') {
                const countdown = this.data.countdown;
                
                // Calculate real-time seconds (rough sync)
                // Actually, backend sends seconds relative to request time. 
                // Better to have target time timestamp from backend. 
                // But simplified: decrease seconds locally or re-parse times.
                
                // Backend sent 'seconds' snapshot. We need target timestamp to be accurate.
                // But PrayerTimeService returns seconds.
                
                // Let's rely on re-fetching or simple decrement?
                // Simple decrement is bad for drifting.
                // Let's parse 'timings' vs 'now' in JS.
                
                const now = new Date();
                const times = this.data.timings;
                
                // Find next event
                // Imsak, Maghrib
                const todayStr = new Date().toISOString().split('T')[0];
                const imsakTime = new Date(`${todayStr}T${times.Imsak}:00`);
                const maghribTime = new Date(`${todayStr}T${times.Maghrib}:00`);
                
                let target, label;
                
                if (now < imsakTime) {
                    target = imsakTime;
                    label = 'Imsak';
                } else if (now < maghribTime) {
                    target = maghribTime;
                    label = 'Berbuka';
                } else {
                    // Next day Imsak
                    target = new Date(imsakTime);
                    target.setDate(target.getDate() + 1);
                    label = 'Imsak';
                }
                
                this.nextEventName = label;
                this.statusLabel = 'Menuju ' + label;
                
                const diff = Math.floor((target - now) / 1000);
                
                if (diff < 3600) { // Less than 1 hour
                    this.isUrgent = true;
                } else {
                    this.isUrgent = false;
                }
                
                const h = Math.floor(diff / 3600).toString().padStart(2, '0');
                const m = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
                const s = Math.floor(diff % 60).toString().padStart(2, '0');
                
                this.timer = `-${h}:${m}:${s}`;
            }
        },

        toggleExpand() {
            this.expanded = !this.expanded;
        }
    }
}
</script>
