<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-500 hover:text-primary-600 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Tadabbur') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($histories as $history)
                    <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-primary-50 rounded-full blur-2xl -mr-8 -mt-8 transition-all group-hover:bg-primary-100"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $history->date->translatedFormat('d M Y') }}</span>
                                <span class="px-2 py-1 bg-primary-50 text-primary-600 text-[10px] font-bold rounded-lg">{{ $history->quranSource->surah_name }}:{{ $history->quranSource->ayah_number }}</span>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3 italic">
                                "{{ $history->quranSource->ayah_translation }}"
                            </p>
                            
                            <div class="pl-3 border-l-2 border-primary-200">
                                 <p class="text-gray-800 font-medium text-sm">
                                    {{ $history->reflection }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 flex flex-col items-center justify-center text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                             <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada catatan</h3>
                        <p class="text-gray-500 max-w-sm mx-auto">Mulai tadabbur harian Anda untuk melihat riwayat catatan hati di sini.</p>
                        <a href="{{ route('dashboard') }}" class="mt-6 px-6 py-2 bg-primary-600 text-white font-bold rounded-xl shadow-lg shadow-primary-500/20 hover:bg-primary-700 transition">Kembali ke Dashboard</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
