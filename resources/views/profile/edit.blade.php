<x-app-layout>

    <div class="space-y-6">
        <div class="p-6 md:p-8 bg-white shadow-soft rounded-[2rem] border border-gray-100">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 md:p-8 bg-white shadow-soft rounded-[2rem] border border-gray-100">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 md:p-8 bg-white shadow-soft rounded-[2rem] border border-gray-100">
            <div class="max-w-xl">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Pengingat Puasa') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Aktifkan notifikasi untuk mendapatkan pengingat puasa sunnah (Senin, Kamis, Ayyamul Bidh).') }}
                        </p>
                    </header>
                    <div class="mt-4">
                        <button onclick="subscribeToPush()" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Aktifkan Pengingat
                        </button>
                    </div>
                </section>
            </div>
        </div>

        <div class="p-6 md:p-8 bg-white shadow-soft rounded-[2rem] border border-gray-100">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

        <!-- Logout Section (Since sidebar is gone) -->
        <div class="p-6 md:p-8 bg-white shadow-soft rounded-[2rem] border border-gray-100 mb-24">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Sesi Login</h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-4 bg-gray-50 text-gray-700 font-bold rounded-2xl border border-gray-200 hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar dari Aplikasi
                </button>
            </form>
            <br/>
        </div>
    </div>
</x-app-layout>
