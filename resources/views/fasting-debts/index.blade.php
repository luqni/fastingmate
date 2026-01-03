<x-app-layout>
    <x-slot name="header">
        {{ __('Fasting Debts') }}
    </x-slot>

    <div class="space-y-8">
        
        <!-- Hijri Info Card -->
        <div class="bg-white rounded-[2rem] p-8 shadow-soft border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
            <div class="relative z-10 flex items-center gap-5 w-full md:w-auto">
                <div class="w-16 h-16 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Hari Ini</h3>
                    <p class="text-xl font-extrabold text-primary-600">{{ \App\Helpers\HijriDate::format(now()) }}</p>
                    <p class="text-sm font-medium text-gray-400">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
            </div>

            @php
                $todaySchedule = $schedules->get(now()->format('Y-m-d'));
            @endphp

            @if($todaySchedule)
                <div class="w-full md:w-auto flex items-center gap-3 bg-primary-600 text-white px-6 py-4 rounded-2xl shadow-lg shadow-primary-500/30">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                    </span>
                    <span class="font-bold">Jadwal Puasa Hari Ini</span>
                </div>
            @else
                <div class="w-full md:w-auto px-6 py-4 rounded-2xl bg-gray-50 border border-gray-100 text-gray-500 font-medium text-sm text-center md:text-left">
                    Tidak ada jadwal puasa hari ini
                </div>
            @endif
        </div>

        <!-- Add New Debt Form -->
        <div  x-data="{ expanded: false }" class="bg-white rounded-[2rem] shadow-soft border border-gray-100 overflow-hidden transition-all duration-300" :class="expanded ? 'p-8' : 'p-6 cursor-pointer hover:bg-gray-50'" @click="expanded = true">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Tambah Hutang Puasa</h3>
                </div>
                <button x-show="expanded" @click.stop="expanded = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>

            <div x-show="expanded" x-collapse class="mt-8">
                <form action="{{ route('fasting-debts.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-8 items-end">
                    @csrf
                    <div>
                        <x-input-label for="year" :value="__('Tahun')" />
                        <x-text-input id="year" name="year" type="number" :value="old('year', date('Y')-1)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('year')" />
                    </div>
                    <div>
                        <x-input-label for="total_days" :value="__('Jumlah Hari')" />
                        <x-text-input id="total_days" name="total_days" type="number" required />
                        <x-input-error class="mt-2" :messages="$errors->get('total_days')" />
                    </div>
                    <div>
                        <x-input-label for="target_finish_date" :value="__('Target Lunas')" />
                        <x-text-input id="target_finish_date" name="target_finish_date" type="date" />
                        <x-input-error class="mt-2" :messages="$errors->get('target_finish_date')" />
                    </div>
                    <div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl shadow-lg shadow-teal-500/30 transition-all transform hover:-translate-y-0.5">
                            {{ __('Simpan Data') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Debt List (Cards) -->
        <div class="grid grid-cols-1 gap-5">
            @forelse($debts as $debt)
                <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-soft border border-gray-100 grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-8 items-center transition hover:shadow-lg">
                    <!-- Info -->
                    <div class="md:col-span-7 flex items-center gap-5">
                        <div class="flex flex-col items-center justify-center w-16 h-16 bg-gray-50 rounded-2xl border border-gray-100 shrink-0">
                            <span class="text-xs font-bold text-gray-400 uppercase">Tahun</span>
                            <span class="text-xl font-extrabold text-gray-900">{{ $debt->year }}</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-2xl font-extrabold text-gray-900">{{ $debt->total_days - $debt->paid_days }} <span class="text-sm text-gray-400 font-medium">Hari</span></span>
                                @if($debt->paid_days > 0)
                                    <span class="px-2 py-1 rounded-lg bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wide">Dibayar {{ $debt->paid_days }}</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500 font-medium flex items-center gap-2">
                                <span>Target:</span>
                                @if($debt->target_finish_date)
                                    <span class="text-teal-600 font-bold">{{ $debt->target_finish_date->format('d M Y') }}</span>
                                @else
                                    <span class="text-gray-400 italic">Belum diset</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="md:col-span-5 flex items-center gap-3 flex-wrap md:flex-nowrap justify-start md:justify-end">
                         
                         <!-- Pay Action -->
                         <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-50 text-teal-600 hover:bg-teal-100 font-bold text-sm transition-colors ring-1 ring-teal-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Bayar
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 bottom-full mb-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 p-4 z-20" style="display: none;">
                                <form action="{{ route('fasting-debts.update-progress', $debt) }}" method="POST">
                                    @csrf
                                    <h4 class="text-sm font-bold text-gray-900 mb-3">Input Pembayaran</h4>
                                    <div class="mb-3">
                                        <input name="paid_days_add" type="number" min="1" max="{{ $debt->total_days - $debt->paid_days }}" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:ring-teal-500 focus:border-teal-500" placeholder="Jumlah hari" required />
                                    </div>
                                    <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white text-sm font-bold rounded-xl hover:bg-teal-700">Simpan</button>
                                </form>
                            </div>
                        </div>

                        <!-- Schedule Action -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-bold text-sm transition-colors ring-1 ring-indigo-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Jadwal
                            </button>
                             <div x-show="open" @click.away="open = false" class="absolute right-0 bottom-full mb-2 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 p-4 z-20" style="display: none;">
                                <form action="{{ route('fasting-debts.generate-schedule', $debt) }}" method="POST">
                                    @csrf
                                    <h4 class="text-sm font-bold text-gray-900 mb-3">Auto Schedule</h4>
                                    <div class="mb-3">
                                        <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Target Lunas</label>
                                        <input type="date" name="target_date" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:ring-indigo-500 focus:border-indigo-500" min="{{ date('Y-m-d') }}" />
                                    </div>
                                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-sm font-bold rounded-xl hover:bg-indigo-700">Generate</button>
                                </form>
                            </div>
                        </div>

                         <a href="{{ route('fasting-debts.history', $debt) }}" class="inline-flex items-center justify-center p-2.5 rounded-xl bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors border border-gray-100">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </a>

                        <form action="{{ route('fasting-debts.destroy', $debt) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center p-2.5 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition-colors border border-red-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-12 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                         <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Belum ada data</h3>
                    <p class="text-gray-500 font-medium">Tambah hutang puasa baru untuk mulai mencatat.</p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
