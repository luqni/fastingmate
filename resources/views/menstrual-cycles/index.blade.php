<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <svg class="w-8 h-8 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C12 2 5.05 10.95 5.05 15.3C5.05 19 8.15 22 12 22C15.85 22 18.95 19 18.95 15.3C18.95 10.95 12 2 12 2Z"></path></svg>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                {{ __('Menstrual Cycles') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-8">
        
        <!-- Active Cycle / Start New -->
        <div class="bg-white rounded-[2rem] shadow-soft border border-gray-100 p-8 transition hover:shadow-lg">
            @php
                $activeCycle = $cycles->whereNull('end_date')->first();
            @endphp

            @if($activeCycle)
                <div class="flex flex-col items-center text-center">
                    <div class="mb-6 flex items-center justify-center">
                        <svg class="w-32 h-32 text-red-600 drop-shadow-xl filter animate-pulse" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C12 2 5.05 10.95 5.05 15.3C5.05 19 8.15 22 12 22C15.85 22 18.95 19 18.95 15.3C18.95 10.95 12 2 12 2Z"></path></svg>
                    </div>
                    
                    <div class="text-3xl font-extrabold text-gray-900 mb-2">Sedang Haid</div>
                    <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-pink-50 text-pink-700 text-sm font-bold mb-8 border border-pink-100">
                        <span>Dimulai pada {{ $activeCycle->start_date->format('d M Y') }}</span>
                    </div>
                    
                    <form action="{{ route('menstrual-cycles.update', $activeCycle) }}" method="POST" class="w-full max-w-sm mx-auto">
                        @csrf
                        @method('PUT')
                        <div class="mb-6 text-left">
                            <x-input-label for="end_date" :value="__('Tanggal Selesai')" />
                            <x-text-input id="end_date" name="end_date" type="date" :value="date('Y-m-d')" required />
                        </div>
                        <button class="w-full justify-center px-6 py-4 bg-gradient-to-r from-pink-300 to-rose-400 hover:from-pink-400 hover:to-rose-500 text-gray-900 font-extrabold rounded-2xl shadow-xl shadow-pink-500/30 transition-all transform hover:-translate-y-1">
                            {{ __('Haid Selesai 🌸') }}
                        </button>
                    </form>
                </div>
            @else
                    <div class="mb-6 flex items-center justify-center">
                        <svg class="w-16 h-16 text-red-600 drop-shadow-xl filter animate-pulse" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C12 2 5.05 10.95 5.05 15.3C5.05 19 8.15 22 12 22C15.85 22 18.95 19 18.95 15.3C18.95 10.95 12 2 12 2Z"></path></svg>
                    </div>

                    <h3 class="text-2xl font-extrabold mb-8 text-gray-900">Catat Siklus Baru</h3>
                    
                    <form action="{{ route('menstrual-cycles.store') }}" method="POST" class="w-full max-w-sm mx-auto">
                        @csrf
                        <div class="mb-6 text-left">
                            <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                            <x-text-input id="start_date" name="start_date" type="date" :value="date('Y-m-d')" required />
                        </div>
                        <button class="w-full justify-center px-6 py-4 bg-gray-900 hover:bg-black text-white font-bold rounded-2xl shadow-lg transition-all">
                            {{ __('Mulai Haid') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- History -->
        <h3 class="text-2xl font-bold text-gray-900 px-2 mt-10 mb-6">Riwayat Haid</h3>
        
        <div class="space-y-4">
            @forelse($cycles as $cycle)
                <div class="bg-white rounded-[2rem] p-6 shadow-soft border border-gray-100 flex flex-col items-start gap-4 transition hover:shadow-md">
                    <div class="w-full flex items-start justify-between">
                         <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-500 flex items-center justify-center font-bold text-xl">
                                {{ $cycle->start_date->format('d') }}
                            </div>
                            <div>
                                <div class="font-extrabold text-lg text-gray-900">{{ $cycle->start_date->format('F Y') }}</div>
                                <div class="text-sm font-medium text-gray-500">
                                    @if($cycle->end_date)
                                        {{ $cycle->start_date->format('d') }} - {{ $cycle->end_date->format('d M') }} 
                                        <span class="text-gray-300 mx-1">•</span> 
                                        <span class="text-pink-600 font-bold">{{ $cycle->start_date->diffInDays($cycle->end_date) + 1 }} Hari</span>
                                    @else
                                        Sedang Berlangsung...
                                    @endif
                                </div>
                            </div>
                         </div>
                         
                         <form action="{{ route('menstrual-cycles.destroy', $cycle) }}" method="POST" onsubmit="return confirm('Hapus riwayat ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>

                    @if($cycle->end_date && !$cycle->converted_to_debt)
                        <div class="w-full p-4 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-between">
                            <span class="text-sm font-bold text-orange-700">Belum dicatat sebagai hutang</span>
                            <!-- Potentially add a quick action here later -->
                        </div>
                    @elseif($cycle->converted_to_debt)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-green-50 text-green-700 text-xs font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Selesai</span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-10 text-gray-400 font-medium">
                    Belum ada riwayat haid.
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
