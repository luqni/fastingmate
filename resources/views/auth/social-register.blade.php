<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-900 pb-2">Lengkapi Profil</h2>
        <p class="text-sm text-gray-600">
            Halo, <span class="font-bold text-gray-900">{{ $socialUser['name'] }}</span>! 👋 <br>
            Satu langkah lagi untuk mempersonalisasi pengalaman ibadahmu.
        </p>
    </div>

    <form method="POST" action="{{ route('auth.social.store') }}">
        @csrf

        <!-- Gender -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Jenis Kelamin')" />
            <p class="text-xs text-slate-500 mb-2">Pilih jenis kelamin untuk fitur siklus haid yang akurat.</p>
            
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="gender" value="male" class="peer sr-only" required>
                    <div class="rounded-xl border-2 border-slate-200 p-4 text-center hover:bg-slate-50 peer-checked:border-primary-600 peer-checked:bg-primary-50 peer-checked:text-primary-700 transition-all">
                        <div class="text-2xl mb-1">👨</div>
                        <div class="text-sm font-semibold">Ikhwan</div>
                    </div>
                </label>
                
                <label class="cursor-pointer">
                    <input type="radio" name="gender" value="female" class="peer sr-only">
                    <div class="rounded-xl border-2 border-slate-200 p-4 text-center hover:bg-slate-50 peer-checked:border-primary-600 peer-checked:bg-primary-50 peer-checked:text-primary-700 transition-all">
                        <div class="text-2xl mb-1">🧕</div>
                        <div class="text-sm font-semibold">Akhwat</div>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full justify-center py-3">
                {{ __('Selesai & Masuk Dashboard') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
