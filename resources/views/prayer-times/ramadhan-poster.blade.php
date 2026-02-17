<x-app-layout>
    <x-slot name="header">
        Jadwal Imsakiyah
    </x-slot>

    <div class="max-w-4xl mx-auto pb-20" x-data="citySearch()">
        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 mb-6 no-print">
            <button @click="openModal()" class="flex items-center gap-2 px-4 py-2 bg-white text-emerald-600 border border-emerald-200 rounded-xl hover:bg-emerald-50 transition-all font-bold shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Ubah Lokasi
            </button>
            
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
        
        <!-- Location Modal -->
        <div x-show="showModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     @click="showModal = false" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Ubah Lokasi
                                </h3>
                                <div class="mt-2 text-sm text-gray-500">
                                    <p class="mb-4">Cari kota Anda untuk menyesuaikan jadwal imsakiyah.</p>
                                    
                                    <div class="relative">
                                        <input type="text" 
                                               x-model="query" 
                                               @input.debounce.300ms="search()"
                                               placeholder="Ketik nama kota (min. 3 karakter)..."
                                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                        
                                        <!-- Loading Indicator -->
                                        <div x-show="loading" class="absolute right-3 top-3">
                                            <svg class="animate-spin h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Results -->
                                    <ul x-show="results.length > 0" class="mt-2 border border-gray-200 rounded-xl overflow-hidden divide-y divide-gray-100 max-h-60 overflow-y-auto">
                                        <template x-for="city in results" :key="city.id">
                                            <li @click="selectCity(city)" 
                                                class="px-4 py-3 hover:bg-emerald-50 cursor-pointer flex justify-between items-center transition-colors group">
                                                <span class="font-medium text-gray-700 group-hover:text-emerald-700" x-text="city.name"></span>
                                                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full group-hover:bg-emerald-100 group-hover:text-emerald-600">Indonesia</span>
                                            </li>
                                        </template>
                                    </ul>
                                    
                                    <div x-show="query.length >= 3 && results.length === 0 && !loading" class="mt-4 text-center text-gray-400 py-4">
                                        Tidak ditemukan kota dengan nama tersebut.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" 
                                @click="showModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
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
        showModal: false,
        loading: false,
        
        openModal() {
            this.showModal = true;
            this.query = '';
            this.results = [];
            // Focus input next tick
            setTimeout(() => {
                this.$el.querySelector('input')?.focus();
            }, 100);
        },
        
        async search() {
            if (this.query.length < 3) {
                this.results = [];
                return;
            }
            
            this.loading = true;
            try {
                const res = await fetch(`{{ route('api.cities.search') }}?q=${this.query}`);
                this.results = await res.json();
            } catch (e) {
                console.error('Search failed', e);
            } finally {
                this.loading = false;
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
