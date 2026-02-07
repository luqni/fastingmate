<div id="sticky-prayer-bar" class="fixed top-0 left-0 right-0 z-50 transform -translate-y-full transition-transform duration-300 hidden">
    <!-- Bar Content -->
    <div class="h-10 w-full px-4 flex items-center justify-center relative overflow-hidden shadow-md" id="spb-container">
        <!-- Background Gradients (switched via JS) -->
        <div id="spb-bg-sahur" class="absolute inset-0 bg-primary-600 hidden"></div>
        <div id="spb-bg-iftar" class="absolute inset-0 bg-gradient-to-r from-orange-500 via-pink-600 to-orange-500 hidden"></div>
        <div id="spb-bg-default" class="absolute inset-0 bg-primary-600"></div>

        <!-- Content -->
        <div class="relative z-10 flex items-center justify-center gap-2 text-white text-xs md:text-sm font-bold tracking-wide">
            <!-- Icon -->
            <span class="text-lg filter drop-shadow-sm" id="spb-icon">🌙</span>
            
            <!-- Text Content -->
            <div class="flex items-center gap-2">
                <span id="spb-label">Subuh 04:30</span>
                <span class="text-white/50">•</span>
                <span id="spb-countdown-label" class="opacity-90">Menuju Imsak</span>
                <span id="spb-timer" class="bg-white/20 px-2 py-0.5 rounded-lg text-white font-mono">00:00:00</span>
            </div>
        </div>
        
        <!-- Dismiss / Toggle (Optional, maybe just a close X that minimizes it?) -->
        <!-- For now, let's keep it permanent as requested "agar user ternotice" -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bar = document.getElementById('sticky-prayer-bar');
    const container = document.getElementById('spb-container');
    const bgSahur = document.getElementById('spb-bg-sahur');
    const bgIftar = document.getElementById('spb-bg-iftar');
    const bgDefault = document.getElementById('spb-bg-default');
    
    let stickyInterval = null;

    initStickyBar();

    async function initStickyBar() {
        try {
            // Re-use the existing endpoint
            const response = await fetch('{{ route("prayer-times.get") }}');
            const data = await response.json();
            
            if (!data.error) {
                updateStickyBar(data);
                
                // Show the bar
                bar.classList.remove('hidden');
                // Small delay to allow transition
                setTimeout(() => {
                    bar.classList.remove('-translate-y-full');
                    // Add padding to body so bar doesn't cover header content initially
                    document.body.style.paddingTop = '2.5rem'; 
                    
                    // Fix overlap with Sticky Header
                    // The header is 'sticky top-0', we need to push its stickiness down by the bar height (2.5rem)
                    const header = document.querySelector('header');
                    if (header) {
                        header.style.top = '2.5rem';
                    }
                }, 100);

                // Refresh every minute to sync data
                setInterval(() => initStickyBar, 60000);
            }
        } catch (e) {
            console.error('Sticky Bar Error:', e);
        }
    }

    function updateStickyBar(data) {
        // Update Next Prayer Info
        if (data.next_prayer) {
            const prayers = {
                'Fajr': { name: 'Subuh', icon: '🌅' },
                'Dhuhr': { name: 'Dzuhur', icon: '☀️' },
                'Asr': { name: 'Ashar', icon: '🌤️' },
                'Maghrib': { name: 'Maghrib', icon: '🌆' },
                'Isha': { name: 'Isya', icon: '🌙' },
            };
            
            const next = prayers[data.next_prayer.name] || { name: data.next_prayer.name, icon: '🕌' };
            document.getElementById('spb-icon').textContent = next.icon;
            document.getElementById('spb-label').textContent = `${next.name} ${data.next_prayer.time}`;
        }

        // Update Countdown & Styling
        const countdown = data.countdown;
        if (countdown && countdown.is_active) {
            // Styling based on type
            if (countdown.type === 'sahur' || countdown.type === 'imsak_passed') {
                bgSahur.classList.remove('hidden');
                bgIftar.classList.add('hidden');
                bgDefault.classList.add('hidden');
            } else if (countdown.type === 'iftar') {
                bgSahur.classList.add('hidden');
                bgIftar.classList.remove('hidden');
                bgDefault.classList.add('hidden');
            }

            // Labels
            document.getElementById('spb-countdown-label').textContent = 
                countdown.type === 'imsak_passed' ? 'Imsak Lewat!' : `Menuju ${countdown.target_label}`;

            // Start Timer
            if (stickyInterval) clearInterval(stickyInterval);
            let secondsLeft = Math.floor(countdown.seconds);
            updateStickyTimer(secondsLeft);

            stickyInterval = setInterval(() => {
                secondsLeft--;
                if (secondsLeft <= 0) {
                    clearInterval(stickyInterval);
                    initStickyBar(); // Refresh data
                } else {
                    updateStickyTimer(secondsLeft);
                }
            }, 1000);
        }
    }

    function updateStickyTimer(seconds) {
        if (seconds < 0) seconds = 0;
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);
        document.getElementById('spb-timer').textContent = 
            `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    }
});
</script>
