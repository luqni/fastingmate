<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Al-Quran') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Continue Reading Card -->
            @if($lastRead)
                <div class="mb-6">
                    <a href="{{ route('quran.continue') }}" class="bg-emerald-600 rounded-2xl p-6 flex items-center justify-between hover:bg-emerald-700 transition-all shadow-lg hover:shadow-xl group">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm font-medium mb-1">Lanjutkan Membaca</p>
                                <h3 class="font-bold text-white text-xl">{{ $lastRead->surah_name }}</h3>
                                <p class="text-white/90 text-sm mt-1">Halaman {{ $lastRead->page }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-white">
                            <span class="text-sm font-medium hidden sm:block">Buka</span>
                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('tadabbur.index') }}" class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 flex items-center justify-between hover:bg-indigo-100 transition group">
                    <div>
                        <h3 class="font-bold text-indigo-900 text-lg">Tadabbur Harian</h3>
                        <p class="text-indigo-600 text-sm">Lihat refleksi ayat hari ini</p>
                    </div>
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                </a>
            </div>

            <!-- Surah List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </span>
                        Daftar Surah
                    </h3>

                    @if($surahs->isEmpty())
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Data Al-Quran Belum Tersedia</h3>
                            <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Database Al-Quran kosong. Silakan jalankan seeder untuk mengisi data.</p>
                            <div class="mt-6">
                                <p class="text-xs text-gray-400 font-mono bg-gray-50 p-2 rounded inline-block">php artisan db:seed --class=QuranSourceSeeder</p>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($surahs as $index => $surah)
                                <a href="{{ route('quran.show', $surah->surah_name) }}" class="group block p-4 rounded-xl border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50 transition-all shadow-sm hover:shadow-md">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <span class="w-10 h-10 rounded-full bg-gray-50 text-gray-500 font-bold flex items-center justify-center group-hover:bg-white group-hover:text-emerald-600 transition-colors border border-gray-100 text-sm">
                                                {{ $loop->iteration }}
                                            </span>
                                            <div>
                                                <h4 class="font-bold text-gray-900 group-hover:text-emerald-800 text-lg">{{ $surah->surah_name }}</h4>
                                                <p class="text-xs text-gray-500 group-hover:text-emerald-600">Baca Surah</p>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
