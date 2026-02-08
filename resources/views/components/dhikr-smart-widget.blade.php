@props(['type'])

@php
    $isMorning = $type === 'morning';
    $isGeneral = $type === 'general';
    
    if ($isGeneral) {
        $title = 'Al-Quran Digital';
        $subtitle = 'Tenangkan hati dengan ayat suci';
        $route = route('quran.index'); // Main action is Quran
        $icon = '<svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>';
        $bgClass = 'bg-emerald-50 border-emerald-100';
        $textClass = 'text-gray-900';
        $subtextClass = 'text-emerald-600';
        $buttonClass = 'bg-emerald-600 hover:bg-emerald-700 text-white';
    } else {
        $title = $isMorning ? 'Dzikir Pagi' : 'Dzikir Petang';
        $subtitle = $isMorning ? 'Awali harimu dengan mengingat Allah' : 'Tutup harimu dengan perlindungan Allah';
        $route = $isMorning ? route('ibadah.morning') : route('ibadah.evening');
        $icon = $isMorning 
            ? '<svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>'
            : '<svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>';
        $bgClass = $isMorning ? 'bg-orange-50 border-orange-100' : 'bg-indigo-900 border-indigo-800 text-white';
        $textClass = $isMorning ? 'text-gray-900' : 'text-white';
        $subtextClass = $isMorning ? 'text-gray-500' : 'text-indigo-200';
        $buttonClass = $isMorning ? 'bg-orange-500 hover:bg-orange-600 text-white' : 'bg-indigo-500 hover:bg-indigo-600 text-white';
    }
@endphp

<div class="{{ $bgClass }} rounded-[2rem] p-6 mb-8 border relative overflow-hidden group">
    <!-- Decor -->
    <div class="absolute right-0 top-0 w-32 h-32 {{ $isMorning ? 'bg-orange-200' : ($isGeneral ? 'bg-emerald-200' : 'bg-indigo-700') }} rounded-full blur-3xl -mr-10 -mt-10 opacity-20 transition-all group-hover:opacity-40"></div>
    
    <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4 w-full sm:w-auto">
            <div class="w-12 h-12 rounded-2xl {{ $isMorning || $isGeneral ? 'bg-white shadow-sm' : 'bg-white/10' }} flex items-center justify-center shrink-0">
                {!! $icon !!}
            </div>
            <div>
                <h3 class="text-lg font-bold {{ $textClass }}">{{ $title }}</h3>
                <p class="text-xs {{ $subtextClass }}">{{ $subtitle }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2 w-full sm:w-auto">
            @if(!$isGeneral)
                <a href="{{ route('quran.index') }}" class="{{ $isMorning ? 'bg-white text-orange-600 hover:bg-orange-50' : 'bg-indigo-800 text-indigo-100 hover:bg-indigo-700' }} border {{ $isMorning ? 'border-orange-200' : 'border-indigo-700' }} px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-transform hover:scale-105 active:scale-95 flex items-center gap-2 flex-1 sm:flex-none justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span>Al-Quran</span>
                </a>
            @endif

            <a href="{{ $route }}" class="{{ $buttonClass }} px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg transition-transform hover:scale-105 active:scale-95 flex items-center gap-2 flex-1 sm:flex-none justify-center">
                <span>{{ $isGeneral ? 'Baca Sekarang' : 'Mulai' }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>
</div>
