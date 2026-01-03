<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 leading-tight">
                {{ __('Kalender Puasa') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('fasting-plans.index', ['month' => $month == 1 ? 12 : $month - 1, 'year' => $month == 1 ? $year - 1 : $year]) }}" class="p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <span class="text-lg font-semibold">{{ Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</span>
                <a href="{{ route('fasting-plans.index', ['month' => $month == 12 ? 1 : $month + 1, 'year' => $month == 12 ? $year + 1 : $year]) }}" class="p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Legend -->
        <div class="flex flex-wrap gap-3 text-xs justify-center">
            <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-blue-100 border border-blue-200"></div> <span>Senin/Kamis</span></div>
            <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-purple-100 border border-purple-200"></div> <span>Ayyamul Bidh</span></div>
            <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-amber-100 border border-amber-200"></div> <span>Khusus (Arafah/Ashura)</span></div>
            <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-red-100 border border-red-200"></div> <span>Dilarang</span></div>
            <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-green-500"></div> <span>Direncanakan</span></div>
            <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-green-700"></div> <span>Selesai</span></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-7 border-b border-gray-100 bg-gray-50">
                @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                    <div class="py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wide">{{ $day }}</div>
                @endforeach
            </div>
            
            <div class="grid grid-cols-7 auto-rows-fr">
                @foreach($calendar as $day)
                    @if($day)
                        @php
                            $isToday = $day['date']->isToday();
                            $bgColor = 'bg-white';
                            $textColor = 'text-gray-700';
                            $borderColor = 'border-transparent';
                            
                            // Sunnah Highlighting
                            if ($day['sunnah_type'] == 'haram') {
                                $bgColor = 'bg-red-50';
                                $textColor = 'text-red-400';
                            } elseif (in_array($day['sunnah_type'], ['arafah', 'ashura', 'tasua'])) {
                                $bgColor = 'bg-amber-50';
                                $borderColor = 'border-amber-200';
                            } elseif ($day['sunnah_type'] == 'ayyamul_bidh') {
                                $bgColor = 'bg-purple-50';
                                $borderColor = 'border-purple-200';
                            } elseif (in_array($day['sunnah_type'], ['senin', 'kamis'])) {
                                $bgColor = 'bg-blue-50';
                                $borderColor = 'border-blue-100';
                            }

                            // Plan Status Overrides
                            $planStatus = $day['plan'] ? $day['plan']->status : null;
                        @endphp

                        <div class="min-h-[100px] relative border border-gray-50 p-2 {{ $bgColor }} transition hover:bg-gray-50 flex flex-col justify-between group">
                            <!-- Date Info -->
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-bold {{ $isToday ? 'bg-primary-600 text-white w-6 h-6 rounded-full flex items-center justify-center' : $textColor }}">
                                    {{ $day['date']->day }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-medium text-right leading-tight">
                                    {{ $day['hijri']['day'] }}<br>
                                    {{ Str::limit(config('hijri.months')[$day['hijri']['month']] ?? $day['hijri']['month'], 3, '') }}
                                </span>
                            </div>

                            <!-- Sunnah Badge -->
                            @if($day['sunnah_type'] && $day['sunnah_type'] !== 'haram' && !in_array($day['sunnah_type'], ['senin', 'kamis']))
                                <div class="mt-1">
                                    <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-white/70 border border-gray-200 text-gray-600 font-medium inline-block truncate w-full">
                                        {{ ucfirst(str_replace('_', ' ', $day['sunnah_type'])) }}
                                    </span>
                                </div>
                            @endif

                            <!-- Interaction Area -->
                            <div class="mt-2 flex justify-end">
                                @if($planStatus)
                                    <form action="{{ route('fasting-plans.update', $day['plan']->id) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $planStatus == 'planned' ? 'completed' : 'planned' }}">
                                        <button class="w-full py-1 rounded text-[10px] font-bold text-white transition {{ $planStatus == 'completed' ? 'bg-green-600 hover:bg-green-700' : 'bg-green-400 hover:bg-green-500' }}">
                                            {{ $planStatus == 'completed' ? 'Selesai' : 'Terjadwal' }}
                                        </button>
                                    </form>
                                @elseif($day['sunnah_type'] !== 'haram')
                                    <form action="{{ route('fasting-plans.store') }}" method="POST" class="w-full opacity-0 group-hover:opacity-100 transition focus-within:opacity-100">
                                        @csrf
                                        <input type="hidden" name="date" value="{{ $day['date']->format('Y-m-d') }}">
                                        <input type="hidden" name="type" value="{{ $day['sunnah_type'] }}">
                                        <button class="w-full py-1 rounded text-[10px] font-bold bg-white border border-gray-200 text-gray-400 hover:border-green-400 hover:text-green-500 transition">
                                            + Rencana
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-50 min-h-[100px]"></div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
