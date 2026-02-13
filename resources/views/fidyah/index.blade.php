<x-app-layout>
    <x-slot name="header">
        {{ __('Kalkulator Fidyah') }}
    </x-slot>

    <div class="space-y-6">
        
        <div class="bg-white rounded-[2rem] p-8 shadow-soft border border-gray-100">
            
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
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 fidyah-item"
                                     data-days="{{ $item['days'] }}"
                                     data-multiplier="{{ $item['multiplier'] }}">
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
                                        @elseif($item['multiplier'] == 0)
                                            <span class="inline-block px-2 py-1 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase mb-1">
                                                Qadha Only
                                            </span>
                                        @endif
                                        
                                        <div class="font-mono font-bold text-gray-700 fidyah-cost-display">
                                            Rp {{ number_format($item['days'] * $defaultRate * $item['multiplier'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <x-input-label value="Nominal Fidyah Per Hari" class="mb-0"/>
                            <button type="button" onclick="showFidyahSourceInfo()" class="text-gray-400 hover:text-emerald-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </button>
                        </div>
                        
                        <form action="{{ route('fidyah.update-rate') }}" method="POST">
                            @csrf
                            <div class="flex flex-col gap-3 mb-4">
                                <label class="flex items-center p-3 rounded-xl border-2 cursor-pointer transition-all" id="label-standard">
                                    <input type="radio" name="rate_type" value="standard" class="text-emerald-600 focus:ring-emerald-500 w-5 h-5" 
                                           id="radio-standard"
                                           {{ !Auth::user()->fidyah_cost ? 'checked' : '' }}>
                                    <span class="ml-3 font-bold text-gray-700">Standar ({{ number_format(\App\Models\FidyahRate::first()?->price_per_day ?? 15000, 0, ',', '.') }})</span>
                                </label>
                                
                                <label class="flex items-center p-3 rounded-xl border-2 cursor-pointer transition-all" id="label-custom">
                                    <input type="radio" name="rate_type" value="custom" class="text-emerald-600 focus:ring-emerald-500 w-5 h-5" 
                                           id="radio-custom"
                                           {{ Auth::user()->fidyah_cost ? 'checked' : '' }}>
                                    <span class="ml-3 font-bold text-gray-700">Kustom</span>
                                </label>
                            </div>

                            <div id="custom-rate-container" class="space-y-3 transition-all duration-300 {{ !Auth::user()->fidyah_cost ? 'hidden' : '' }}">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-400 font-bold">Rp</span>
                                    </div>
                                    <x-text-input type="text" inputmode="numeric" name="rate" id="rate-input" class="w-full pl-12 py-3 text-lg font-bold" 
                                                  value="{{ number_format(Auth::user()->fidyah_cost ?? (\App\Models\FidyahRate::first()?->price_per_day ?? 15000), 0, ',', '.') }}"/>
                                </div>
                                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-lg shadow-emerald-200">
                                    Simpan Tarif
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Result Card -->
                <div class="flex flex-col">
                     <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-emerald-500/20 flex flex-col justify-center items-center text-center h-full relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -mr-20 -mt-20"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-black opacity-5 rounded-full blur-2xl -ml-10 -mb-10"></div>
                        
                        <div class="relative z-10">
                            <div class="text-emerald-100 font-bold uppercase tracking-wider mb-4 text-sm">Total Estimasi</div>
                            <div class="text-5xl lg:text-6xl font-extrabold mb-4 tracking-tight" id="total-estimasi-display">
                                Rp {{ number_format($totalFidyahCost, 0, ',', '.') }}
                            </div>
                             <div class="text-emerald-100 text-sm font-medium px-8 leading-relaxed">
                                Nominal ini mencakup total hari hutang dikalikan dengan tarif fidyah, termasuk denda pelipatgandaan jika ada tahun yang terlewat.
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="mt-8 pt-8 border-t border-gray-100">
                <h4 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span>💳</span> Bayar Fidyah
                </h4>
                
                <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100">
                    <form action="{{ route('fidyah.pay') }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="rate" id="payment-rate-hidden" value="{{ $defaultRate }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nominal Pembayaran (Rp)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-bold">Rp</span>
                                    <input type="text" inputmode="numeric" name="amount" id="payment-amount" 
                                        class="w-full pl-12 pr-4 py-3 border-2 border-indigo-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 font-bold text-lg text-gray-800 transition-colors"
                                        placeholder="0" required>
                                </div>
                                <p class="text-xs text-gray-500 mt-2" id="payment-hint">Masukkan nominal kelipatan tarif fidyah.</p>
                            </div>
                            
                            <div class="bg-white rounded-xl p-4 border border-indigo-100 flex flex-col justify-center">
                                <div class="text-xs text-uppercase font-bold text-indigo-500 mb-1">HASIL KONVERSI</div>
                                <div class="text-3xl font-extrabold text-gray-900 mb-1">
                                    <span id="payment-days">0</span> <span class="text-lg font-medium text-gray-500">Hari</span>
                                </div>
                                
                                <div id="payment-warning" class="hidden mt-2 text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded">
                                    ⚠️ Hutang Anda hanya <span id="max-days-text">0</span> hari.
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" id="btn-pay" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                                Bayar Sekarang
                            </button>
                        </div>
                    </form>
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

    <!-- Vanilla JS Implementation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... existing variables ...
            const radioStandard = document.getElementById('radio-standard');
            const radioCustom = document.getElementById('radio-custom');
            const labelStandard = document.getElementById('label-standard');
            const labelCustom = document.getElementById('label-custom');
            const customRateContainer = document.getElementById('custom-rate-container');
            const rateInput = document.getElementById('rate-input');
            const totalDisplay = document.getElementById('total-estimasi-display');
            const items = document.querySelectorAll('.fidyah-item');

            // Payment Logic Variables
            const paymentAmountInput = document.getElementById('payment-amount');
            const paymentDaysDisplay = document.getElementById('payment-days');
            const paymentRateHidden = document.getElementById('payment-rate-hidden');
            const paymentWarning = document.getElementById('payment-warning');
            const maxDaysText = document.getElementById('max-days-text');
            const btnPay = document.getElementById('btn-pay');
            
            const totalUnpaidDays = {{ $totalDays }}; // Passed from controller

            const standardRate = {{ \App\Models\FidyahRate::first()?->price_per_day ?? 15000 }};
            
            function formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
            }

            // Currency Formatting Functions
            function formatNumber(n) {
                // format number 1000000 to 1,234,567
                return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function unformatNumber(n) {
                return n.replace(/\./g, "");
            }

            function formatCurrencyInput(input) {
                let val = input.value;
                val = unformatNumber(val);
                input.value = val ? formatNumber(val) : '';
            }

            // Apply formatting to inputs
            if (rateInput) {
                rateInput.type = 'text'; // Change to text to allow dots
                rateInput.addEventListener('keyup', function() {
                    formatCurrencyInput(this);
                    updateCalculations();
                });
                // Format initial value
                formatCurrencyInput(rateInput);
            }

            if (paymentAmountInput) {
                paymentAmountInput.type = 'text';
                paymentAmountInput.addEventListener('keyup', function() {
                    formatCurrencyInput(this);
                    calculatePaymentDays();
                });
            }

            // Modified calculatePaymentDays to handle text input
            function calculatePaymentDays() {
                if (!paymentAmountInput) return;
                
                const amount = parseInt(unformatNumber(paymentAmountInput.value)) || 0;
                // Get current active rate
                let currentRate = standardRate;
                if (radioCustom && radioCustom.checked) {
                    currentRate = parseInt(unformatNumber(rateInput.value)) || standardRate;
                }

                if (currentRate <= 0) return;

                const days = Math.floor(amount / currentRate);
                
                if (paymentDaysDisplay) paymentDaysDisplay.textContent = days;

                if (days > totalUnpaidDays) {
                    if(paymentWarning) paymentWarning.classList.remove('hidden');
                    if(maxDaysText) maxDaysText.textContent = totalUnpaidDays;
                    if(btnPay) {
                        btnPay.disabled = true;
                        btnPay.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                } else {
                    if(paymentWarning) paymentWarning.classList.add('hidden');
                    if(btnPay) {
                        btnPay.disabled = false;
                        btnPay.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
            }

            function updateCalculations() {
                let currentRate = standardRate;
                if (radioCustom.checked) {
                    currentRate = parseInt(unformatNumber(rateInput.value)) || 0;
                }
                
                // Update hidden rate for payment form
                if (paymentRateHidden) paymentRateHidden.value = currentRate;

                let total = 0;

                items.forEach(item => {
                    const days = parseInt(item.dataset.days);
                    const multiplier = parseInt(item.dataset.multiplier);
                    const cost = days * currentRate * multiplier;
                    total += cost;

                    const costDisplay = item.querySelector('.fidyah-cost-display');
                    if (costDisplay) {
                        costDisplay.textContent = formatCurrency(cost);
                    }
                });

                if(totalDisplay) totalDisplay.textContent = formatCurrency(total);
                
                // Trigger payment calc based on new rate
                calculatePaymentDays();
            }

            function updateUI() {
                if(!radioStandard) return; // Guard clause

                if (radioStandard.checked) {
                    labelStandard.classList.add('border-emerald-500', 'bg-emerald-50/50');
                    labelStandard.classList.remove('border-gray-100');
                    
                    labelCustom.classList.remove('border-emerald-500', 'bg-emerald-50/50');
                    labelCustom.classList.add('border-gray-100');

                    customRateContainer.classList.add('hidden');
                } else {
                    labelCustom.classList.add('border-emerald-500', 'bg-emerald-50/50');
                    labelCustom.classList.remove('border-gray-100');
                    
                    labelStandard.classList.remove('border-emerald-500', 'bg-emerald-50/50');
                    labelStandard.classList.add('border-gray-100');

                    customRateContainer.classList.remove('hidden');
                }
                updateCalculations();
            }

            if(radioStandard) radioStandard.addEventListener('change', updateUI);
            if(radioCustom) radioCustom.addEventListener('change', updateUI);
            if(rateInput) rateInput.addEventListener('input', updateCalculations);
            if(paymentAmountInput) paymentAmountInput.addEventListener('input', calculatePaymentDays);

            // Initial UI update to set correct state
            updateUI();
        });

        function showFidyahSourceInfo() {
            const tableHtml = `
                <div class="text-left text-sm">
                    <p class="mb-3 text-gray-600">Berikut adalah pedoman nominal fidyah berdasarkan SK Ketua BAZNAS No. 10 Tahun 2024:</p>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 font-bold text-gray-700">Wilayah / Lembaga</th>
                                    <th class="py-3 px-4 font-bold text-gray-700 text-right">Nominal / Hari</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="py-3 px-4 text-gray-600">Baznas Pusat (Jabodetabek)</td>
                                    <td class="py-3 px-4 text-gray-900 font-bold text-right">Rp 60.000</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-600">Baznas Jawa Barat</td>
                                    <td class="py-3 px-4 text-gray-900 font-bold text-right">Rp 45.000</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-600">Baznas Jawa Timur</td>
                                    <td class="py-3 px-4 text-gray-900 font-bold text-right">Rp 50.000</td>
                                </tr>
                                <tr class="bg-emerald-50">
                                    <td class="py-3 px-4 text-gray-800 font-medium">Baznas Kab. Sleman (2025)</td>
                                    <td class="py-3 px-4 text-emerald-700 font-bold text-right">Rp 10.500</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-3 text-xs text-gray-500 italic">
                        *Nominal dapat berbeda di setiap daerah sesuai kebijakan BAZNAS setempat (SK Ketua BAZNAS Daerah).
                    </p>
                </div>
            `;

            Swal.fire({
                title: 'Informasi Sumber Data',
                html: tableHtml,
                icon: 'info',
                width: '600px',
                confirmButtonColor: '#10b981', // emerald-500
                confirmButtonText: 'Mengerti'
            });
        }
    </script>
</x-app-layout>
