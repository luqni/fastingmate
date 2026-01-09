@props(['tadabbur'])

<div x-data="{ open: false, reflection: '{{ $tadabbur->reflection ?? '' }}' }" 
     @open-tadabbur.window="open = true"
     class="relative z-50"
     aria-labelledby="slide-over-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Background backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-in-out duration-500" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in-out duration-500" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-filter backdrop-blur-sm"
         @click="open = false"></div>

    <div class="fixed inset-0 overflow-hidden" style="pointer-events: none;">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                
                <!-- Slide-over panel -->
                <div x-show="open" 
                     x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
                     x-transition:enter-start="translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="translate-x-full"
                     class="pointer-events-auto relative w-screen max-w-md"
                     style="pointer-events: auto;">
                    
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-6 sm:px-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-white text-lg font-semibold" id="slide-over-title">
                                    Daily Tadabbur
                                </h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button @click="open = false" type="button" class="relative rounded-md text-emerald-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                        <span class="absolute -inset-2.5"></span>
                                        <span class="sr-only">Close panel</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-1 text-emerald-100 text-sm">Renungkan ayat pilihan hari ini.</p>
                        </div>

                        <!-- Content -->
                        <div class="relative mt-6 flex-1 px-4 sm:px-6">
                            <!-- Quran Content -->
                            <div class="bg-emerald-50 rounded-2xl p-6 mb-6 border border-emerald-100">
                                <div class="text-right mb-4">
                                    <h3 class="text-2xl font-amiri leading-loose text-gray-800" dir="rtl">
                                        {{ $tadabbur->quranSource->ayah_text_arabic }}
                                    </h3>
                                </div>
                                <div class="text-gray-600 italic text-sm mb-2">
                                    "{{ $tadabbur->quranSource->ayah_translation }}"
                                </div>
                                <div class="flex justify-end items-center gap-2 mt-4 text-xs font-semibold text-emerald-700 uppercase tracking-wide">
                                    <span>{{ $tadabbur->quranSource->surah_name }}</span>
                                    <span>•</span>
                                    <span>Ayat {{ $tadabbur->quranSource->ayah_number }}</span>
                                </div>
                            </div>

                            <!-- Reflection Form -->
                            <form action="{{ route('daily-tadabbur.store', $tadabbur) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="reflection" class="block text-sm font-medium text-gray-700">Refleksi Anda</label>
                                    <div class="mt-1">
                                        <textarea id="reflection" name="reflection" rows="8" 
                                                  class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm placeholder-gray-400"
                                                  placeholder="Tuliskan apa yang Anda rasakan atau pelajari dari ayat ini..."
                                                  :disabled="{{ $tadabbur->status === 'completed' ? 'true' : 'false' }}"
                                                  required>{{ $tadabbur->reflection }}</textarea>
                                    </div>
                                    @error('reflection')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="pt-4 pb-6">
                                    @if($tadabbur->status !== 'completed')
                                        <button type="submit" class="flex w-full justify-center rounded-xl bg-emerald-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-colors">
                                            Simpan Tadabbur
                                        </button>
                                    @else
                                        <div class="flex items-center justify-center gap-2 text-emerald-600 bg-emerald-50 p-3 rounded-xl">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span class="font-medium text-sm">Tadabbur Selesai</span>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
