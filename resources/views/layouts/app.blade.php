<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vapid-key" content="{{ config('webpush.vapid.public_key') }}">

    <title>{{ config('app.name', 'FastingMate') }}</title>
    <link rel="icon" type="image/png" href="/favicon.png">
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
    
    <!-- SweetAlert2 CDN Fallback -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
                        <!-- Notifications -->
                        @auth
                        <div class="relative" x-data="notificationHandler()">
                            <button @click="toggleNotifications()" class="p-2 rounded-xl text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-all relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                
                                <span x-show="unreadCount > 0" 
                                      x-text="unreadCount" 
                                      class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white"
                                      style="display: none;"></span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div x-show="isOpen" 
                                 @click.away="isOpen = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-2"
                                 class="fixed left-1/2 top-24 -translate-x-1/2 w-[92%] max-w-sm bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden md:absolute md:inset-auto md:right-0 md:top-full md:mt-2 md:w-80 md:translate-x-0" 
                                 style="display: none;">
                                
                                <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                                    <h3 class="font-bold text-gray-900 text-sm">Notifikasi</h3>
                                    <button @click="markAllRead()" class="text-primary-600 text-xs font-bold hover:underline" x-show="unreadCount > 0">
                                        Tandai sudah dibaca
                                    </button>
                                </div>

                                <div class="max-h-80 overflow-y-auto hide-scrollbar">
                                    <template x-if="notifications.length === 0">
                                        <div class="p-8 text-center text-gray-400">
                                            <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p class="text-xs">Belum ada notifikasi baru</p>
                                        </div>
                                    </template>
                                    
                                    <template x-for="item in notifications" :key="item.id">
                                        <div @click="handleItemClick(item)" 
                                             class="p-4 hover:bg-gray-50 transition-colors cursor-pointer border-b border-gray-50 last:border-0 relative"
                                             :class="{'bg-primary-50/30': !item.read_at}">
                                            
                                            <div class="flex gap-3">
                                                <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                                                     :class="{
                                                         'bg-indigo-100 text-indigo-600': item.data.icon === 'document-text',
                                                         'bg-emerald-100 text-emerald-600': item.data.icon === 'calendar' || item.data.icon === 'book-open',
                                                         'bg-gray-100 text-gray-600': !item.data.icon
                                                     }">
                                                    
                                                     <!-- Icons based on type -->
                                                     <template x-if="item.data.icon === 'document-text'">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                     </template>
                                                     <template x-if="item.data.icon === 'calendar'">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                     </template>
                                                     <template x-if="item.data.icon === 'book-open'">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                     </template>
                                                </div>
                                                <div>
                                                    <h4 class="text-xs font-bold text-gray-900 mb-0.5" x-text="item.data.title"></h4>
                                                    <p class="text-[11px] text-gray-500 leading-snug line-clamp-2" x-text="item.data.message || item.data.body"></p>
                                                    <span class="text-[10px] text-gray-400 mt-1 block" x-text="formatDate(item.created_at)"></span>
                                                </div>
                                            </div>
                                            <span x-show="!item.read_at" class="absolute top-4 right-4 w-2 h-2 bg-primary-500 rounded-full"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        @endauth
                        
                        <button onclick="window.showInstallPrompt()" class="p-2 rounded-xl text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-all" title="Install App">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </button>
                        
                        @auth
                        <a href="{{ route('profile.edit') }}" class="p-2 rounded-xl text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </a>
                        @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-bold text-gray-700 hover:text-primary-600 transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-primary-600/20">
                                Daftar Gratis
                            </a>
                        </div>
                        @endauth
                    </div>
                </div>
            </header>

            @if (isset($noContainer) && $noContainer)
                 @if (isset($header))
                    <div class="w-full max-w-7xl mx-auto px-6 pt-6">
                        <div class="mb-8">
                            <h2 class="text-2xl font-extrabold tracking-tight">{{ $header }}</h2>
                        </div>
                    </div>
                @endif
                
                {{ $slot }}
            @else
                <div class="w-full max-w-7xl mx-auto p-6 space-y-8">
                     @if (isset($header))
                        <div class="mb-8">
                            <h2 class="text-2xl font-extrabold tracking-tight">{{ $header }}</h2>
                        </div>
                    @endif
                    
                    {{ $slot }}
                </div>
            @endif
        </main>

        <!-- Bottom Navigation (Visible on ALL screens) -->
        @auth
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
        @endauth
    </div>
    <!-- Flash Messages -->

    <!-- True Dynamic Island (Draggable) -->
    <script>
        console.log('Tadabbur Variable:', @json($tadabbur ?? null));
    </script>
    @if(isset($tadabbur) && $tadabbur)
    <div id="di-container" 
         class="fixed z-50 flex flex-col items-center transition-all duration-300 ease-out w-auto touch-none"
         style="bottom: 8rem; right: 1.5rem;">
        
        <!-- Island Container -->
        <div id="di-wrapper" 
             class="bg-gray-950 text-white shadow-2xl shadow-indigo-900/30 overflow-hidden border border-white/10 rounded-full px-4 py-3 cursor-grab active:cursor-grabbing hover:scale-105 active:scale-95 ring-1 ring-white/10 backdrop-blur-3xl transition-all duration-300">
            
            <!-- Collapsed State Content -->
            <div id="di-collapsed" class="flex items-center gap-3 select-none pointer-events-none">
                 <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $tadabbur->status === 'completed' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-indigo-500/20 text-indigo-400' }}">
                    @if($tadabbur->status === 'completed')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    @endif
                </div>
                <div class="flex flex-col pr-1">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-0.5">Tadabbur</span>
                    <span class="text-xs font-bold text-white leading-none whitespace-nowrap">
                        {{ $tadabbur->status === 'completed' ? 'Selesai' : 'Buka Ayat' }}
                    </span>
                </div>
            </div>

            <!-- Expanded State Content -->
            <div id="di-expanded" class="hidden opacity-0 translate-y-4 transition-all duration-300 ease-out flex-col w-full">
                
                <!-- Header (Close Button) -->
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm">
                            <span class="text-xl">📖</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg leading-tight">Daily Tadabbur</h3>
                            <p class="text-xs text-gray-400">Renungkan ayat pilihan ini.</p>
                        </div>
                    </div>
                    <button id="di-close" type="button" class="p-2 bg-white/5 rounded-full hover:bg-white/10 transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Quran Content -->
                <div class="bg-white/5 rounded-3xl p-6 mb-6 border border-white/5 relative overflow-hidden group">
                     <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
                    
                    <div class="text-right mb-4 relative z-10 block">
                        <h3 class="text-2xl font-amiri leading-loose text-emerald-100" dir="rtl">
                            {{ $tadabbur->quranSource->ayah_text_arabic }}
                        </h3>
                    </div>
                    <div class="text-gray-300 italic text-sm mb-4 leading-relaxed relative z-10 block">
                        "{{ $tadabbur->quranSource->ayah_translation }}"
                    </div>
                    <div class="flex justify-start items-center gap-2 text-[10px] font-bold text-emerald-400 uppercase tracking-widest">
                        <span>{{ $tadabbur->quranSource->surah_name }}</span>
                        <span class="w-1 h-1 bg-emerald-500 rounded-full"></span>
                        <span>Ayat {{ $tadabbur->quranSource->ayah_number }}</span>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('daily-tadabbur.store', $tadabbur) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="relative">
                        <textarea id="reflection" name="reflection" rows="4" 
                                  class="block w-full rounded-2xl bg-white/5 border-transparent text-white placeholder-gray-500 shadow-inner focus:border-emerald-500 focus:bg-white/10 focus:ring-0 sm:text-sm transition-all resize-none p-4"
                                  placeholder="Apa yang Anda rasakan setelah membaca ayat ini?"
                                  required>{{ $tadabbur->reflection }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r {{ $tadabbur->status === 'completed' ? 'from-indigo-600 to-blue-600 shadow-indigo-900/50' : 'from-emerald-600 to-teal-600 shadow-emerald-900/50' }} text-white font-bold text-sm shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <span>{{ $tadabbur->status === 'completed' ? 'Update Refleksi' : 'Simpan Refleksi' }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                    
                    @if($tadabbur->status === 'completed')
                    <div class="text-center">
                        <p class="text-[10px] text-emerald-400 font-medium">✨ Anda sudah menyelesaikan tadabbur hari ini</p>
                    </div>
                    @endif
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('di-container');
            const wrapper = document.getElementById('di-wrapper');
            const collapsed = document.getElementById('di-collapsed');
            const expanded = document.getElementById('di-expanded');
            const closeBtn = document.getElementById('di-close');
            
            let isExpanded = false;
            let isDragging = false;
            let startX, startY, initialLeft, initialTop;
            
            // Initial Position Ref
            let lastPosition = {
                bottom: container.style.bottom,
                right: container.style.right,
                top: 'auto',
                left: 'auto'
            };

            function setupDrag() {
                const handleStart = (e) => {
                    if (isExpanded) return; // Disable drag when expanded
                    
                    isDragging = false;
                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    
                    startX = clientX;
                    startY = clientY;
                    
                    const rect = container.getBoundingClientRect();
                    initialLeft = rect.left;
                    initialTop = rect.top;
                    
                    // Switch to fixed left/top for dragging calculation
                    container.style.bottom = 'auto';
                    container.style.right = 'auto';
                    container.style.left = initialLeft + 'px';
                    container.style.top = initialTop + 'px';

                    document.addEventListener('mousemove', handleMove);
                    document.addEventListener('touchmove', handleMove);
                    document.addEventListener('mouseup', handleEnd);
                    document.addEventListener('touchend', handleEnd);
                };

                const handleMove = (e) => {
                    if (isExpanded) return;

                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    
                    // Threshold to detect drag vs click
                    if (!isDragging && Math.hypot(clientX - startX, clientY - startY) > 5) {
                        isDragging = true;
                        wrapper.classList.add('cursor-grabbing');
                        wrapper.classList.remove('cursor-grab');
                    }

                    if (isDragging) {
                        e.preventDefault();
                        const dx = clientX - startX;
                        const dy = clientY - startY;
                        
                        let newLeft = initialLeft + dx;
                        let newTop = initialTop + dy;
                        
                        // Boundary Checks
                        const maxLeft = window.innerWidth - container.offsetWidth;
                        const maxTop = window.innerHeight - container.offsetHeight;
                        
                        newLeft = Math.max(0, Math.min(newLeft, maxLeft));
                        newTop = Math.max(0, Math.min(newTop, maxTop));

                        container.style.left = newLeft + 'px';
                        container.style.top = newTop + 'px';
                    }
                };

                const handleEnd = () => {
                    document.removeEventListener('mousemove', handleMove);
                    document.removeEventListener('touchmove', handleMove);
                    document.removeEventListener('mouseup', handleEnd);
                    document.removeEventListener('touchend', handleEnd);
                    
                    wrapper.classList.remove('cursor-grabbing');
                    wrapper.classList.add('cursor-grab');

                    // Save position for collapse return
                    if (isDragging) {
                        lastPosition.left = container.style.left;
                        lastPosition.top = container.style.top;
                        lastPosition.bottom = 'auto';
                        lastPosition.right = 'auto';
                    }
                    
                    // Small timeout to prevent click trigger immediately after drag
                    setTimeout(() => {
                        isDragging = false;
                    }, 50);
                };

                wrapper.addEventListener('mousedown', handleStart);
                wrapper.addEventListener('touchstart', handleStart);
            }

            function expandIsland() {
                if (isDragging || isExpanded) return;
                isExpanded = true;

                // Save current position before animating
                lastPosition.left = container.style.left;
                lastPosition.top = container.style.top;
                if (!container.style.left) { // If using bottom/right
                    const rect = container.getBoundingClientRect();
                    lastPosition.left = rect.left + 'px';
                    lastPosition.top = rect.top + 'px';
                }

                // 1. Move to Center Screen
                container.style.transition = 'all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                container.style.left = '50%';
                container.style.top = '50%';
                container.style.bottom = 'auto';
                container.style.right = 'auto';
                container.style.transform = 'translate(-50%, -50%)';
                
                // 2. Expand Size
                container.classList.remove('w-auto');
                container.classList.add('w-[92%]', 'max-w-md');

                wrapper.classList.remove('rounded-full', 'px-4', 'py-3', 'cursor-grab', 'hover:scale-105', 'active:scale-95', 'overflow-hidden');
                wrapper.classList.add('rounded-[2rem]', 'p-6', 'w-full', 'cursor-default', 'max-h-[85vh]', 'overflow-y-auto');

                // 3. Show Content
                collapsed.style.display = 'none';
                expanded.classList.remove('hidden');
                
                setTimeout(() => {
                    expanded.classList.remove('opacity-0', 'translate-y-4');
                }, 50);
            }

            function collapseIsland(e) {
                if(e) e.stopPropagation();
                if (!isExpanded) return;
                isExpanded = false;

                // 1. Hide Content
                expanded.classList.add('opacity-0', 'translate-y-4');

                setTimeout(() => {
                    expanded.classList.add('hidden');
                    collapsed.style.display = 'flex';

                    // 2. Revert Wrapper
                    wrapper.classList.remove('rounded-[2rem]', 'p-6', 'w-full', 'cursor-default', 'max-h-[85vh]', 'overflow-y-auto');
                    wrapper.classList.add('rounded-full', 'px-4', 'py-3', 'cursor-grab', 'hover:scale-105', 'active:scale-95', 'overflow-hidden');
                    
                    // 3. Revert Container Properties
                    container.classList.remove('w-[92%]', 'max-w-md');
                    container.classList.add('w-auto');

                    // 4. Move back to last position
                    container.style.transform = 'translate(0, 0)';
                    
                    if (lastPosition.bottom !== 'auto') {
                         // Reset to initial CSS classes if never dragged
                        container.style.left = 'auto';
                        container.style.top = 'auto';
                        container.style.right = '1.5rem';
                        container.style.bottom = '8rem';
                    } else {
                        container.style.left = lastPosition.left;
                        container.style.top = lastPosition.top;
                        container.style.bottom = 'auto';
                        container.style.right = 'auto';
                    }

                }, 300);
            }

            setupDrag();
            
            // Expose function globally
            window.expandDynamicIsland = expandIsland;
            window.collapseDynamicIsland = collapseIsland;
            
            // Click Handler (only if not dragged)
            wrapper.addEventListener('click', (e) => {
                if (!isDragging) expandIsland();
            });
            
            closeBtn.addEventListener('click', collapseIsland);

            // Click Outside
            document.addEventListener('click', function(event) {
                if (isExpanded && !container.contains(event.target)) {
                    collapseIsland();
                }
            });
        });

        function notificationHandler() {
            return {
                isOpen: false,
                notifications: [],
                unreadCount: 0,
                
                init() {
                    this.fetchNotifications();
                    // Poll specific endpoint or listen to echo if available
                    // For now, simple poll every minute works for typical reminders
                    setInterval(() => {
                        this.fetchNotifications();
                    }, 60000);
                },

                toggleNotifications() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen) {
                        this.fetchNotifications();
                    }
                },

                async fetchNotifications() {
                    try {
                        const response = await fetch('{{ route('notifications.index') }}');
                        const data = await response.json();
                        this.notifications = data;
                        this.unreadCount = data.filter(n => !n.read_at).length;
                    } catch (error) {
                        console.error('Error loading notifications:', error);
                    }
                },

                async markAllRead() {
                    try {
                        await fetch('{{ route('notifications.mark-all-read') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        this.unreadCount = 0;
                        this.notifications.forEach(n => n.read_at = new Date().toISOString());
                    } catch (error) {
                        console.error('Error marking all read:', error);
                    }
                },

                async handleItemClick(item) {
                     // Mark as read first
                     if (!item.read_at) {
                         try {
                            await fetch(`/notifications/${item.id}/read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });
                            item.read_at = new Date().toISOString();
                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                         } catch (error) {
                             console.error('Error marking as read:', error);
                         }
                     }
                     
                     // Helper to handle actions
                     if (item.data.action_url) {
                        window.location.href = item.data.action_url;
                     }
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diff = Math.floor((now - date) / 1000); // seconds
                    
                    if (diff < 60) return 'Baru saja';
                    if (diff < 3600) return `${Math.floor(diff / 60)} menit yang lalu`;
                    if (diff < 86400) return `${Math.floor(diff / 3600)} jam yang lalu`;
                    return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short' }).format(date);
                }
            }
        }
    </script>
    @endif
    <div style="width: 245px; height: 40px;margin-left: 30px;">
        <script type='text/javascript' src='https://edge-cdn.trakteer.id/js/trbtn-overlay.min.js?v=14-05-2025'></script><script type='text/javascript' class='troverlay'>(function() {var trbtnId = trbtnOverlay.init('Dukung Saya di Trakteer','#be1e2d','https://trakteer.id/luqni/tip/embed/modal','https://trakteer.id/images/mix/coffee.png','40','inline');trbtnOverlay.draw(trbtnId);})();</script>
    </div>
</body>
</html>
