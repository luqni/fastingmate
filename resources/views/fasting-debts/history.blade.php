<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
             <a href="{{ route('fasting-debts.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Pembayaran') }} {{ $fastingDebt->year }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-8">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Total -->
            <div class="bg-white rounded-[2rem] p-6 shadow-soft border border-gray-100 flex items-center gap-5">
                 <div class="w-14 h-14 bg-gray-50 text-gray-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                 </div>
                 <div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Hutang</h3>
                    <p class="text-2xl font-extrabold text-gray-900">{{ $fastingDebt->total_days }} <span class="text-sm font-medium text-gray-400">Hari</span></p>
                 </div>
            </div>
            
            <!-- Paid -->
             <div class="bg-white rounded-[2rem] p-6 shadow-soft border border-gray-100 flex items-center gap-5">
                 <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center shrink-0">
                     <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                 </div>
                 <div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Sudah Dibayar</h3>
                    <p class="text-2xl font-extrabold text-teal-600">{{ $fastingDebt->paid_days }} <span class="text-sm font-medium text-gray-400">Hari</span></p>
                 </div>
            </div>

            <!-- Remaining -->
             <div class="bg-white rounded-[2rem] p-6 shadow-soft border border-gray-100 flex items-center gap-5">
                 <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center shrink-0">
                     <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                 </div>
                 <div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Sisa Hutang</h3>
                    <p class="text-2xl font-extrabold text-rose-600">{{ $fastingDebt->total_days - $fastingDebt->paid_days }} <span class="text-sm font-medium text-gray-400">Hari</span></p>
                 </div>
            </div>
        </div>

        <!-- History List -->
        <div class="bg-white rounded-[2rem] shadow-soft border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span>📜</span> Log Pembayaran
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-gray-400 uppercase text-xs font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Jumlah</th>
                            <th class="px-6 py-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($repayments as $repayment)
                            <tr class="hover:bg-gray-50/50 transition-colors cursor-default">
                                <td class="px-6 py-4 text-gray-900 font-bold">
                                    {{ $repayment->repayment_date->format('d M Y') }}
                                    <div class="text-[10px] text-gray-400 font-normal uppercase mt-0.5">{{ $repayment->repayment_date->isoFormat('dddd') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                     <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-teal-50 text-teal-700 text-xs font-bold">
                                         <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        +{{ $repayment->paid_days }} Hari
                                     </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 font-medium text-sm">
                                    {{ $repayment->description ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada riwayat pembayaran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
