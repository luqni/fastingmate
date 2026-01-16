<x-app-layout>
    <x-slot name="header">
        Pengaturan
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Fitur Tadabbur</h3>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <label for="enable_ramadan_summary" class="block text-sm font-medium text-gray-700">Rangkuman Perjalanan Ramadhan</label>
                                    <p class="text-sm text-gray-500">Aktifkan fitur ini untuk menampilkan narasi rangkuman dari semua tadabbur pengguna di halaman history.</p>
                                </div>
                                <div class="flex items-center">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="enable_ramadan_summary" id="enable_ramadan_summary" class="sr-only peer" {{ isset($settings['enable_ramadan_summary']) && $settings['enable_ramadan_summary'] == '1' ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
