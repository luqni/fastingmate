<x-app-layout>
    <x-slot name="header">
        {{ __('Kalkulator Fidyah') }}
    </x-slot>

    <div class="space-y-6">
        
        <div class="bg-white rounded-[2rem] p-8 shadow-soft border border-gray-100" x-data="{ 
            days: {{ $totalDays }},
            rate: {{ $defaultRate }}, 
            customRate: false,
            formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
            }
        }">
            
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                    🌾
                </div>
                <div>
                    <h3 class="text-2xl font-extrabold text-gray-900">Zakat Fidyah</h3>
                    <p class="text-sm text-gray-500 font-medium">Berdasarkan total hutang puasa yang belum lunas.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Calculator Inputs -->
                <div class="space-y-8">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-4">Rincian Hutang</h4>
                        <div class="space-y-3">
                            @foreach($breakdown as $item)
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center font-bold text-gray-700 shadow-sm border border-gray-100">
                                            {{ $item['year'] }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900">Tahun {{ $item['year'] }}</div>
                                            <div class="text-xs text-gray-500 font-medium">{{ $item['days'] }} hari belum lunas</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($item['multiplier'] > 1)
                                            <span class="inline-block px-2 py-1 rounded-md bg-red-50 text-red-600 text-[10px] font-bold uppercase mb-1">
                                                Denda x{{ $item['multiplier'] }}
                                            </span>
                                        @endif
                                        <div class="font-mono font-bold text-gray-700" 
                                              x-text="formatCurrency({{ $item['days'] }} * rate * {{ $item['multiplier'] }})"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Nominal Fidyah Per Hari" class="mb-3"/>
                        
                        <div class="flex flex-col gap-3 mb-4">
                            <label class="flex items-center p-3 rounded-xl border-2 cursor-pointer transition-all" :class="!customRate ? 'border-emerald-500 bg-emerald-50/50' : 'border-gray-100 hover:border-gray-200'">
                                <input type="radio" name="rate_type" class="text-emerald-600 focus:ring-emerald-500 w-5 h-5" @click="customRate = false; rate = {{ $defaultRate }}" checked>
                                <span class="ml-3 font-bold text-gray-700">Standar ({{ number_format($defaultRate, 0, ',', '.') }})</span>
                            </label>
                            
                            <label class="flex items-center p-3 rounded-xl border-2 cursor-pointer transition-all" :class="customRate ? 'border-emerald-500 bg-emerald-50/50' : 'border-gray-100 hover:border-gray-200'">
                                <input type="radio" name="rate_type" class="text-emerald-600 focus:ring-emerald-500 w-5 h-5" @click="customRate = true">
                                <span class="ml-3 font-bold text-gray-700">Kustom</span>
                            </label>
                        </div>

                        <div x-show="customRate" x-transition class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-bold">Rp</span>
                            </div>
                            <x-text-input type="number" x-model="rate" class="w-full pl-12 py-4 text-xl font-bold" min="0"/>
                        </div>
                    </div>
                </div>

                <!-- Result Card -->
                <div class="flex flex-col">
                     <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-emerald-500/20 flex flex-col justify-center items-center text-center h-full relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -mr-20 -mt-20"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-black opacity-5 rounded-full blur-2xl -ml-10 -mb-10"></div>
                        
                        <div class="relative z-10">
                            <div class="text-emerald-100 font-bold uppercase tracking-wider mb-4 text-sm">Total Estimasi</div>
                            <div class="text-5xl lg:text-6xl font-extrabold mb-4 tracking-tight" x-text="formatCurrency(
                                 {{ json_encode($breakdown) }}.reduce((acc, item) => acc + (item.days * rate * item.multiplier), 0)
                            )"></div>
                             <div class="text-emerald-100 text-sm font-medium px-8 leading-relaxed">
                                Nominal ini mencakup total hari hutang dikalikan dengan tarif fidyah, termasuk denda pelipatgandaan jika ada tahun yang terlewat.
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-100">
                <div class="flex gap-4">
                    <div class="shrink-0 text-2xl">💡</div>
                    <div>
                         <h4 class="font-bold text-gray-900 mb-2">Info Fidyah</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Fidyah wajib dibayarkan oleh orang yang tidak mampu berpuasa secara permanen (sakit tua/menahun) atau bagi wanita hamil/menyusui yang khawatir akan bayinya (menurut sebagian ulama).
                            Besaran fidyah adalah 1 mud (sekitar 6-7 ons) makanan pokok per hari, atau bisa dikonversi ke nilai uang seharga makanan tersebut.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
