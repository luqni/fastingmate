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
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(isset($summary) && $summary)
                <div class="mb-8 bg-gradient-to-br from-indigo-900 to-indigo-800 rounded-2xl p-8 shadow-xl relative overflow-hidden animate-fade-in-down"
                     x-data="{
                        text: `{{ addslashes($summary) }}`, 
                        copyText() {
                            navigator.clipboard.writeText(this.text).then(() => {
                                alert('Rangkuman berhasil disalin!');
                            });
                        },
                        share(platform) {
                            const url = window.location.href; // Optional: include link to app
                            const shareText = this.text;
                            let shareUrl = '';

                            switch(platform) {
                                case 'whatsapp':
                                    shareUrl = `https://wa.me/?text=${encodeURIComponent(shareText)}`;
                                    break;
                                case 'twitter':
                                    shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareText)}`;
                                    break;
                                case 'facebook':
                                    // Facebook only allows sharing URLs, not custom text pre-fill usually
                                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                                    break;
                            }
                            if(shareUrl) window.open(shareUrl, '_blank');
                        }
                     }">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">✨</span>
                                <h3 class="text-xl font-bold text-white">Rangkuman Perjalanan Ramadhan</h3>
                            </div>
                            
                            <!-- Share Actions -->
                            <div class="flex items-center gap-2 bg-white/10 p-1.5 rounded-xl backdrop-blur-sm self-start md:self-auto">
                                <button @click="share('whatsapp')" class="p-2 text-white/80 hover:text-green-400 hover:bg-white/10 rounded-lg transition-all" title="Share WhatsApp">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.6 1.984-.052 4.015.972 5.653l-.717 2.615 4.233-1.107z"/></svg>
                                </button>
                                <button @click="share('twitter')" class="p-2 text-white/80 hover:text-sky-400 hover:bg-white/10 rounded-lg transition-all" title="Share X (Twitter)">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </button>
                                <div class="w-px h-5 bg-white/20"></div>
                                <button @click="copyText()" class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-all" title="Salin Teks">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                </button>
                            </div>
                        </div>
                        <div class="prose prose-invert prose-p:leading-relaxed prose-lg max-w-none text-indigo-100/90 font-serif italic text-justify">
                            {!! nl2br(e($summary)) !!}
                        </div>
                        <div class="mt-6 flex justify-end">
                            <p class="text-xs text-indigo-300 font-sans not-italic">~ FastingMate AI</p>
                        </div>
                    </div>
                </div>
            @elseif(isset($enableRamadanSummary) && $enableRamadanSummary)
                <div class="mb-8 p-6 bg-indigo-50 border border-indigo-100 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-indigo-900">Buat Rangkuman Perjalananmu</h3>
                        <p class="text-sm text-indigo-600">Gabungkan seluruh catatan tadabburmu menjadi sebuah cerita perjalanan yang indah.</p>
                    </div>
                    <form action="{{ route('daily-tadabbur.generate-summary') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex items-center gap-2">
                            <span>✨ Buat Rangkuman</span>
                        </button>
                    </form>
                </div>
            @endif

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
