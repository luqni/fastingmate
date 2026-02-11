<div id="global-preloader" style="position: fixed; inset: 0; z-index: 9999; background-color: white; display: flex;" class="flex-col items-center justify-center transition-opacity duration-500 ease-out">
    <div class="relative flex flex-col items-center">
        <!-- Logo Animation Container -->
        <div class="w-24 h-24 mb-4 relative">
             <!-- Pulsing Background -->
             <div class="absolute inset-0 bg-primary-50 rounded-full animate-ping opacity-75"></div>
             
             <!-- Logo Icon (Moon/Lantern style) -->
             <div class="relative bg-white p-4 rounded-full shadow-xl border-2 border-primary-50">
                 <svg class="w-16 h-16 text-primary-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                 </svg>
             </div>
        </div>

        <!-- Brand Name -->
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-1">
            Fasting<span class="text-primary-600">Mate</span>
        </h1>
        
        <!-- Loading Text/Dots -->
        <div class="mt-3 flex items-center justify-center gap-1">
            <span class="w-2 h-2 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0s"></span>
            <span class="w-2 h-2 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
            <span class="w-2 h-2 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const minLoadTime = 100; // Increased to 1.2s for better visibility
        const startTime = Date.now();

        const fadeOutPreloader = () => {
            const elapsedTime = Date.now() - startTime;
            const remainingTime = Math.max(0, minLoadTime - elapsedTime);

            setTimeout(() => {
                const preloader = document.getElementById('global-preloader');
                if (preloader) {
                    preloader.style.opacity = '0';
                    setTimeout(() => {
                        preloader.remove();
                    }, 500); // Remove from DOM after fade out
                }
            }, remainingTime);
        };

        if (document.readyState === 'complete') {
            fadeOutPreloader();
        } else {
            window.addEventListener('load', fadeOutPreloader);
        }

        // Safety fallback
        setTimeout(() => {
            const preloader = document.getElementById('global-preloader');
            if (preloader && preloader.style.opacity !== '0') {
                 preloader.style.opacity = '0';
                 setTimeout(() => preloader.remove(), 100);
            }
        }, 5000);
    });
</script>
