<x-app-layout>
    <x-slot name="header">
        {{ __('Hello, ' . Auth::user()->name . ' 👋') }}
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- Progress Card -->
        <div class="bg-white rounded-[2rem] p-8 shadow-soft border border-gray-100 flex items-center justify-between relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-primary-50 rounded-full blur-3xl -mr-10 -mt-10 transition-all group-hover:bg-primary-100"></div>
            
            <div class="relative z-10">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Misi Selesai</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-5xl font-extrabold text-gray-900 tracking-tight">{{ $progressPercentage }}%</span>
                </div>
                <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 text-red-600 text-xs font-bold border border-red-100 shadow-sm">
                    <span>Hutang: {{ $remainingDebt }} Hari</span>
                </div>
            </div>
            
             <div class="relative z-10 shrink-0 ml-4">
                 <!-- Simple Circle SVG -->
                 <svg class="w-24 h-24 -rotate-90 transform text-gray-100" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="8" fill="none" />
                    <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="8" fill="none" class="text-primary-600" stroke-dasharray="{{ $progressPercentage * 2.51 }} 251" stroke-linecap="round" />
                 </svg>
            </div>
        </div>

        <!-- Next Schedule Card -->
        <div class="bg-white rounded-[2rem] p-8 shadow-soft border border-gray-100 relative overflow-hidden flex flex-col justify-between min-h-[160px] group">
            <div class="absolute top-0 right-0 p-8 opacity-5 transform scale-125 translate-x-4 -translate-y-4 transition-transform group-hover:scale-110">
                <svg class="w-24 h-24 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
            </div>
            <p class="text-gray-500 font-bold text-sm tracking-wide uppercase mb-2">Jadwal Berikutnya</p>
            <div>
                <h3 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight">
                     @if($nextFasting)
                        {{ $nextFasting->scheduled_date->format('d M') }}
                    @else
                        Selesai!
                    @endif
                </h3>
                <p class="mt-2 text-primary-600 font-bold text-lg">
                     @if($nextFasting)
                        {{ $nextFasting->scheduled_date->format('l') }}
                    @else
                        Tidak ada jadwal
                    @endif
                </p>
            </div>
        </div>

        @if(Auth::user()->gender === 'female')
        <!-- Cycle Card -->
        <div class="bg-white rounded-[2rem] p-8 shadow-soft border border-gray-100 flex items-center gap-6">
             <div class="mb-6 flex items-center justify-center">
                        <svg class="w-16 h-16 text-red-600 drop-shadow-xl filter animate-pulse" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C12 2 5.05 10.95 5.05 15.3C5.05 19 8.15 22 12 22C15.85 22 18.95 19 18.95 15.3C18.95 10.95 12 2 12 2Z"></path></svg>
                    </div>
             <div>
                 <p class="text-sm font-bold text-gray-600 uppercase tracking-widest mb-1">Status Haid</p>
                 <h3 class="text-3xl font-extrabold text-gray-900">{{ $activeCycle ? 'Haid' : 'Suci' }}</h3>
                 @if($activeCycle)
                    <p class="text-sm font-medium text-gray-600 mt-1">Hari ke-{{ $activeCycle->start_date->diffInDays(now()) + 1 }}</p>
                 @else
                    <p class="text-sm font-medium text-gray-600 mt-1">Tidak ada siklus aktif</p>
                 @endif
             </div>
        </div>
        @endif

        <!-- Ramadan Countdown Card -->
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-[2rem] p-8 shadow-lg shadow-emerald-500/20 text-white relative overflow-hidden flex flex-col justify-between min-h-[160px] group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10 transition-all group-hover:bg-white/20"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full blur-xl -ml-6 -mb-6"></div>
            
            <div class="relative z-10 flex justify-between items-start">
                <div>
                     <p class="text-emerald-100 font-bold text-sm tracking-wide uppercase mb-1">Menuju Ramadhan {{ $nextRamadan['hijri_year'] }}</p>
                    <h3 class="text-4xl font-extrabold tracking-tight">
                        @if($daysToRamadan > 0)
                            {{ $daysToRamadan }} <span class="text-2xl font-bold text-emerald-100">Hari</span>
                        @elseif($daysToRamadan == 0)
                            Hari Ini!
                        @else
                            -
                        @endif
                    </h3>
                </div>
                <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm">
                    <svg class="w-8 h-8 text-emerald-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </div>
            </div>

            <div class="relative z-10 mt-4">
               <p class="text-emerald-100 font-medium text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Estimasi: {{ $nextRamadan['date']->translatedFormat('d F Y') }}
               </p>
            </div>
        </div>

        <!-- Blog Card -->
        <a href="{{ route('posts.index') }}" class="bg-white rounded-[2rem] p-8 shadow-soft border border-gray-100 relative overflow-hidden flex flex-col justify-between min-h-[160px] group transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-50 rounded-full blur-2xl -mr-10 -mt-10 transition-all group-hover:bg-indigo-100"></div>
            
            <div class="relative z-10 flex justify-between items-start">
                <div>
                     <p class="text-indigo-600 font-bold text-sm tracking-wide uppercase mb-1">Artikel & Blog</p>
                    <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                        Wawasan Islami
                    </h3>
                </div>
                <div class="bg-indigo-50 p-3 rounded-2xl group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
            </div>

            <div class="relative z-10 mt-4">
               <p class="text-gray-500 font-medium text-sm flex items-center gap-2 group-hover:text-indigo-600 transition-colors">
                    Baca Artikel Terbaru
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
               </p>
            </div>
        </a>

    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-soft border border-gray-100 mt-10">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-bold text-gray-900">Jadwal Bulan Ini</h3>
            <a href="{{ route('fasting-debts.index') }}" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-7 gap-4">
             @forelse($schedules as $schedule)
                <div class="rounded-3xl p-4 text-center border-2 transition-all duration-300 {{ $schedule->status === 'completed' ? 'border-transparent bg-gradient-to-b from-emerald-50 to-white' : 'border-gray-50 bg-white hover:border-primary-100 hover:shadow-lg hover:shadow-primary-500/10' }}">
                    <div class="text-xs font-bold text-gray-400 uppercase mb-2">{{ $schedule->scheduled_date->format('D') }}</div>
                    <div class="text-2xl font-extrabold text-gray-900 mb-3">{{ $schedule->scheduled_date->format('d') }}</div>
                    
                    @if($schedule->status === 'completed')
                        <div class="mx-auto w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    @else
                         <div class="mx-auto w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center">
                             <!-- Clock or Status -->
                             <span class="w-2.5 h-2.5 bg-yellow-400 rounded-full"></span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full py-10 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                         <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada jadwal bulan ini</p>
                </div>
            @endforelse
        </div>
    </div>

</x-app-layout>
