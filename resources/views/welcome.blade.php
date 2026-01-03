<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FastingMate - Lunasi Hutang Puasa dengan Tenang</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .dark .glass {
            background: rgba(17, 24, 39, 0.7);
        }
    </style>
</head>
<body class="antialiased font-sans text-gray-900 dark:text-gray-100 bg-light dark:bg-light transition-colors duration-300">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/60 dark:bg-gray-900/60 backdrop-blur-xl border-b border-gray-100/50 dark:border-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24 items-center">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-emerald-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-primary-500/20 ring-4 ring-primary-50 dark:ring-gray-800">
                        F
                    </div>
                    <span class="font-bold text-2xl tracking-tight text-gray-800 dark:text-gray-100">FastingMate</span>
                </div>
                <div class="flex items-center gap-4">
                    <button id="theme-toggle" class="p-3 rounded-2xl hover:bg-gray-100/80 dark:hover:bg-gray-800/80 text-gray-500 transition-all duration-300 hover:scale-105 active:scale-95">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    </button>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 rounded-full bg-gray-50 text-gray-900 font-semibold hover:bg-gray-100 transition-all text-sm border border-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700">Dashboard</a>
                        @else
                            <a href="{{ url('/register') }}" class="px-6 py-2.5 rounded-full bg-gray-900 hover:bg-black text-white font-medium transition-all shadow-lg shadow-gray-900/20 text-sm hover:-translate-y-0.5 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100">
                                Mulai Sekarang
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-24 sm:pt-48 sm:pb-40 overflow-hidden">
        <!-- Softer Background Elements -->
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-green-50/50 via-white to-white dark:from-gray-900 dark:via-gray-900 dark:to-gray-900"></div>
        <div class="absolute -top-24 -right-24 w-[500px] h-[500px] bg-primary-200/20 rounded-full blur-[100px] animate-pulse-slow"></div>
        <div class="absolute top-48 -left-24 w-[400px] h-[400px] bg-emerald-100/30 rounded-full blur-[100px]"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
             <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border border-gray-200/50 dark:border-gray-700/50 shadow-sm mb-10 transition-transform hover:scale-105 cursor-default">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Web App Qadha Puasa #1</span>
            </div>
            
            <h1 class="text-5xl sm:text-7xl font-extrabold tracking-tight mb-8 text-gray-900 dark:text-white leading-[1.1]">
                Lunasi Hutang Puasa<br class="hidden sm:block"> dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Tenang & Terencana</span>
            </h1>
            
            <p class="text-xl text-gray-500 dark:text-gray-400 max-w-2xl mx-auto mb-12 leading-relaxed font-medium">
                Platform pintar untuk mencatat, menjadwalkan, dan melacak Qadha puasa Anda. Dilengkapi fitur tracking haid otomatis dan perhitungan fidyah.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
                <a href="{{ url('/register') }}" class="group relative w-full sm:w-auto px-8 py-4 rounded-full bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold text-lg shadow-xl shadow-gray-900/10 hover:shadow-2xl hover:shadow-gray-900/20 transition-all hover:-translate-y-1 overflow-hidden">
                    <span class="relative z-10">Mulai Gratis</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-800 to-black dark:from-gray-100 dark:to-white opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </a>
                <a href="#features" class="w-full sm:w-auto px-8 py-4 rounded-full bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-200 font-semibold border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all hover:-translate-y-0.5">
                    Pelajari Fitur
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-32 bg-white dark:bg-gray-900 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-6">Kenapa FastingMate?</h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto text-lg">Semua fitur yang Anda butuhkan untuk ibadah yang lebih tertata.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-10 rounded-[2.5rem] bg-gray-50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800 transition-all duration-500 group border border-transparent hover:border-gray-100 dark:hover:border-gray-700 hover:shadow-2xl hover:shadow-gray-200/50 dark:hover:shadow-none">
                    <div class="w-16 h-16 rounded-2xl bg-white dark:bg-gray-700 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex items-center justify-center text-3xl mb-8 group-hover:scale-110 transition-transform duration-500 rotate-3 group-hover:rotate-6">
                        📅
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Multi-Year Tracker</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Pisahkan hutang puasa berdasarkan tahun. Sistem kami membantu menghitung fidyah secara akurat.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-10 rounded-[2.5rem] bg-gray-50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800 transition-all duration-500 group border border-transparent hover:border-gray-100 dark:hover:border-gray-700 hover:shadow-2xl hover:shadow-gray-200/50 dark:hover:shadow-none">
                    <div class="w-16 h-16 rounded-2xl bg-white dark:bg-gray-700 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex items-center justify-center text-3xl mb-8 group-hover:scale-110 transition-transform duration-500 -rotate-3 group-hover:-rotate-6">
                        🤖
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Smart Auto-Scheduler</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Input hutang, kami buatkan jadwal puasa (Senin & Kamis) otomatis hingga lunas.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-10 rounded-[2.5rem] bg-gray-50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800 transition-all duration-500 group border border-transparent hover:border-gray-100 dark:hover:border-gray-700 hover:shadow-2xl hover:shadow-gray-200/50 dark:hover:shadow-none">
                    <div class="w-16 h-16 rounded-2xl bg-white dark:bg-gray-700 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex items-center justify-center text-3xl mb-8 group-hover:scale-110 transition-transform duration-500 rotate-3 group-hover:rotate-6">
                        🌸
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Menstrual Sync</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Catat periode haid, sistem otomatis konversi jadi hutang puasa. Simpel & Akurat.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-12 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-2.5 mb-2">
                     <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md">F</div>
                     <span class="font-bold text-lg text-gray-900 dark:text-white tracking-tight">FastingMate</span>
                </div>
                <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">Teman ibadah digital Anda.</p>
            </div>
            <div class="text-sm text-gray-400 dark:text-gray-500 font-medium">
                &copy; {{ date('Y') }} FastingMate.
            </div>
        </div>
    </footer>

    <script>
        // Dark Mode Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        // Check local storage or system preference
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            lightIcon.classList.remove('d-none');
        } else {
            document.documentElement.classList.remove('dark');
            darkIcon.classList.remove('d-none');
        }

        themeToggleBtn.addEventListener('click', function() {
            darkIcon.classList.toggle('d-none');
            lightIcon.classList.toggle('d-none');

            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    </script>
</body>
</html>
