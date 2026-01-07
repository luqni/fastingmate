<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vapid-key" content="{{ config('webpush.vapid.public_key') }}">

    <title>{{ config('app.name', 'FastingMate') }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="manifest" href="/build/manifest.webmanifest">
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" href="/pwa-192x192.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        window.flashMessages = {
            success: @json(session('success')),
            error: @json(session('error'))
        };
    </script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased pb-32">

    <div class="min-h-screen">
        
        <!-- Main Content -->
        <main class="min-h-screen transition-all bg-gray-50/50">
            <!-- Header -->
            <header class="sticky top-0 z-30 glass border-b border-gray-100/50 h-20 flex justify-center shadow-sm">
                <div class="w-full max-w-7xl mx-auto px-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-extrabold text-primary-600 tracking-tight">FastingMate</h1>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <button onclick="window.showInstallPrompt()" class="p-2 rounded-xl text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-all" title="Install App">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </button>
                        <a href="{{ route('profile.edit') }}" class="p-2 rounded-xl text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </a>
                    </div>
                </div>
            </header>

            <div class="w-full max-w-7xl mx-auto p-6 space-y-8">
                 @if (isset($header))
                    <div class="mb-8">
                        <h2 class="text-2xl font-extrabold tracking-tight">{{ $header }}</h2>
                    </div>
                @endif
                
                {{ $slot }}
            </div>
        </main>

        <!-- Bottom Navigation (Visible on ALL screens) -->
        <nav class="fixed bottom-0 left-0 right-0 glass border-t border-gray-100 z-40 flex justify-center items-center h-24 px-6 shadow-[0_-4px_30px_-4px_rgba(0,0,0,0.1)]">
            <div class="w-full max-w-md md:max-w-4xl flex justify-around items-center">
                 <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-full h-full space-y-2 group">
                    <div class="relative p-2 rounded-xl transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-600' : 'text-gray-500 group-hover:bg-gray-50 group-hover:text-gray-700' }}">
                        <svg class="w-7 h-7" fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold {{ request()->routeIs('dashboard') ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-700' }}">Home</span>
                </a>

                <a href="{{ route('fasting-debts.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-2 group">
                     <div class="relative p-2 rounded-xl transition-all duration-300 {{ request()->routeIs('fasting-debts.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-500 group-hover:bg-gray-50 group-hover:text-gray-700' }}">
                        <svg class="w-7 h-7" fill="{{ request()->routeIs('fasting-debts.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                     </div>
                    <span class="text-[11px] font-bold {{ request()->routeIs('fasting-debts.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-700' }}">Hutang</span>
                </a>

                <a href="{{ route('fidyah.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-2 group">
                     <div class="relative p-2 rounded-xl transition-all duration-300 {{ request()->routeIs('fidyah.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-500 group-hover:bg-gray-50 group-hover:text-gray-700' }}">
                        <svg class="w-7 h-7" fill="{{ request()->routeIs('fidyah.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                     </div>
                    <span class="text-[11px] font-bold {{ request()->routeIs('fidyah.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-700' }}">Fidyah</span>
                </a>

                <a href="{{ route('fasting-plans.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-2 group">
                     <div class="relative p-2 rounded-xl transition-all duration-300 {{ request()->routeIs('fasting-plans.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-500 group-hover:bg-gray-50 group-hover:text-gray-700' }}">
                        <svg class="w-7 h-7" fill="{{ request()->routeIs('fasting-plans.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                     </div>
                    <span class="text-[11px] font-bold {{ request()->routeIs('fasting-plans.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-700' }}">Kalender</span>
                </a>

                @if(Auth::user()->gender === 'female')
                <a href="{{ route('menstrual-cycles.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-2 group">
                     <div class="relative p-2 rounded-xl transition-all duration-300 {{ request()->routeIs('menstrual-cycles.*') ? 'bg-pink-50 text-pink-600' : 'text-gray-500 group-hover:bg-gray-50 group-hover:text-gray-700' }}">
                        <svg class="w-7 h-7" fill="{{ request()->routeIs('menstrual-cycles.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C12 2 5.05 10.95 5.05 15.3C5.05 19 8.15 22 12 22C15.85 22 18.95 19 18.95 15.3C18.95 10.95 12 2 12 2Z"></path></svg>
                     </div>
                    <span class="text-[11px] font-bold {{ request()->routeIs('menstrual-cycles.*') ? 'text-pink-600' : 'text-gray-500 group-hover:text-gray-700' }}">Siklus</span>
                </a>
                @endif

                

            </div>
        </nav>
    </div>
    <!-- Flash Messages -->

</body>
</html>
