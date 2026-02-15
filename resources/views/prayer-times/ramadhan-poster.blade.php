<x-app-layout>
    <x-slot name="header">
        Jadwal Imsakiyah
    </x-slot>

    <div class="max-w-4xl mx-auto pb-20">
        <!-- Print Button -->
        <div class="flex justify-end mb-6 no-print">
            <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all font-bold shadow-lg shadow-emerald-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Jadwal
            </button>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 relative overflow-hidden print:shadow-none print:border-0 print:p-0 print:w-full">
            <!-- Decoration -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-20 -mt-20 opacity-50 pointer-events-none print:hidden"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-teal-50 rounded-full blur-3xl -ml-20 -mb-20 opacity-50 pointer-events-none print:hidden"></div>

            <!-- Header -->
            <div class="text-center mb-8 relative z-10">
                <h1 class="font-amiri text-4xl text-emerald-700 font-bold mb-2">Jadwal Imsakiyah Ramadhan 1447 H</h1>
                <p class="text-gray-500 font-medium">Untuk Wilayah <span class="text-emerald-600 font-bold text-lg">{{ $city }}, {{ $country }}</span> dan Sekitarnya</p>
                <p class="text-sm text-gray-400 mt-1">*Desclaimer: Jadwal dapat berubah sewaktu-waktu mengikuti ketetapan pemerintah setempat.</p>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-emerald-600 text-white">
                            <th class="px-4 py-3 rounded-tl-xl text-center">Ramadhan</th>
                            <th class="px-4 py-3 text-center">Tanggal</th>
                            <th class="px-4 py-3 text-center bg-emerald-700 font-bold border-r border-emerald-500">Imsak</th>
                            <th class="px-4 py-3 text-center font-bold">Subuh</th>
                            <th class="px-4 py-3 text-center hidden md:table-cell print:table-cell">Terbit</th>
                            <th class="px-4 py-3 text-center">Dzuhur</th>
                            <th class="px-4 py-3 text-center">Ashar</th>
                            <th class="px-4 py-3 text-center bg-emerald-700 font-bold border-l border-emerald-500">Maghrib</th>
                            <th class="px-4 py-3 rounded-tr-xl text-center">Isya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($schedule as $day)
                            <tr class="hover:bg-emerald-50/50 transition-colors {{ $loop->even ? 'bg-gray-50/30' : '' }}">
                                <td class="px-4 py-3 text-center font-bold text-emerald-700">
                                    {{ $day['hijri']['day'] ?? $loop->iteration }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600">
                                    {{ \Carbon\Carbon::parse($day['date']['gregorian']['date'] ?? $day['date'])->locale('id')->isoFormat('D MMMM Y') }}
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-emerald-700 bg-emerald-50/20 border-r border-gray-100">
                                    {{ $day['timings']['Imsak'] }}
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-gray-700">
                                    {{ $day['timings']['Fajr'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-500 hidden md:table-cell print:table-cell">
                                    {{ $day['timings']['Sunrise'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-700">
                                    {{ $day['timings']['Dhuhr'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-700">
                                    {{ $day['timings']['Asr'] }}
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-emerald-700 bg-emerald-50/20 border-l border-gray-100">
                                    {{ $day['timings']['Maghrib'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-700">
                                    {{ $day['timings']['Isya'] ?? $day['timings']['Isha'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                    Data jadwal belum tersedia untuk saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <span class="font-bold text-xl text-emerald-700">FastingMate</span>
                    <span class="w-px h-6 bg-gray-300"></span>
                    <span class="text-sm text-gray-500">Teman Ibadahmu</span>
                </div>
                <div class="text-center md:text-right">
                    <p class="font-amiri text-lg text-emerald-600">Marhaban Ya Ramadhan</p>
                    <p class="text-xs text-gray-400">Di-generate pada {{ now()->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            @page { margin: 0; size: auto; }
            body * { visibility: hidden; }
            .no-print { display: none !important; }
            .max-w-4xl { max-width: 100% !important; margin: 0 !important; padding: 20px !important; }
            .max-w-4xl * { visibility: visible; }
            .max-w-4xl { position: absolute; left: 0; top: 0; width: 100%; }
            header, nav, footer, .sticky-prayer-bar { display: none !important; }
        }
    </style>
</x-app-layout>

<script>
function citySearch() {
    return {
        query: '',
        results: [],
        
        async search() {
            if (this.query.length < 3) {
                this.results = [];
                return;
            }
            
            try {
                const res = await fetch(`{{ route('api.cities.search') }}?q=${this.query}`);
                this.results = await res.json();
            } catch (e) {
                console.error('Search failed', e);
            }
        },
        
        selectCity(city) {
            // Redirect with new city param
            const url = new URL(window.location.href);
            url.searchParams.set('city', city.name);
            url.searchParams.set('country', 'Indonesia');
            window.location.href = url.toString();
        }
    }
}
</script>
