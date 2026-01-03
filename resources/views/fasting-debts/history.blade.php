<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-4">
            <a href="{{ route('fasting-debts.index') }}" class="p-2 rounded bg-white dark:bg-light shadow-sm border border-secondary dark:border-secondary text-secondary hover:text-gray-700 dark:text-muted dark:hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="fw-semibold fs-3 text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Riwayat Pembayaran Hutang Puasa Tahun') }} {{ $fastingDebt->year }}
            </h2>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-5 lg:px-5 space-y-6">
            
            <div class="bg-white/60 dark:bg-light/60 backdrop-blur-xl overflow-d-none shadow-xl rounded p-5 border border-white/20 dark:border-secondary d-flex d-flex-column md:d-flex-row align-items-center justify-content-between gap-8">
                <div>
                    <div class="small text-secondary dark:text-muted fw-medium">Total Hutang</div>
                    <div class="text-3xl fw-bold text-gray-800 dark:text-white">{{ $fastingDebt->total_days }} <span class="text-base font-normal text-muted">Hari</span></div>
                </div>
                <div>
                    <div class="small text-secondary dark:text-muted fw-medium">Sudah Dibayar</div>
                    <div class="text-3xl fw-bold text-teal-600 dark:text-teal-400">{{ $fastingDebt->paid_days }} <span class="text-base font-normal text-muted">Hari</span></div>
                </div>
                <div>
                    <div class="small text-secondary dark:text-muted fw-medium">Sisa</div>
                    <div class="text-3xl fw-bold text-danger dark:text-danger">{{ $fastingDebt->total_days - $fastingDebt->paid_days }} <span class="text-base font-normal text-muted">Hari</span></div>
                </div>
            </div>

            <div class="bg-white/80 dark:bg-light/80 backdrop-blur-xl overflow-d-none shadow-lg rounded-3xl border border-white/20 dark:border-secondary">
                <div class="p-5 border-b border-secondary dark:border-secondary d-flex align-items-center gap-2">
                    <span class="fs-3">📜</span>
                    <h3 class="fs-4 fw-bold text-gray-800 dark:text-gray-200">Log Pembayaran</h3>
                </div>
                <table class="w-100 small text-center text-secondary dark:text-muted">
                    <thead class="small text-muted text-gray-700 text-uppercase bg-light/50 dark:bg-light/50 dark:text-muted border-b border-secondary dark:border-secondary">
                        <tr>
                            <th scope="col" class="px-5 py-4 fw-bold tracking-wider text-left">Tanggal</th>
                            <th scope="col" class="px-5 py-4 fw-bold tracking-wider">Jumlah Hari</th>
                            <th scope="col" class="px-5 py-4 fw-bold tracking-wider text-left">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($repayments as $repayment)
                            <tr class="bg-transparent hover:bg-light/50 dark:hover:bg-light/50 transition-colors">
                                <td class="px-5 py-4 fw-medium text-gray-900 dark:text-white text-left">{{ $repayment->repayment_date->format('d M Y') }}</td>
                                <td class="px-5 py-4 fw-bold text-success dark:text-success">+ {{ $repayment->paid_days }}</td>
                                <td class="px-5 py-4 text-left">{{ $repayment->description ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-5 text-center text-muted">Belum ada riwayat pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
