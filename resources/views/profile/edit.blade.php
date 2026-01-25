<x-app-layout>
    
    <!-- Profile Header -->
    <div class="mb-10">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-emerald-500 to-teal-600 p-8 md:p-12 text-white shadow-xl shadow-emerald-500/20">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full blur-2xl -ml-12 -mb-12"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 md:gap-8">
                <div class="shrink-0">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white/30 shadow-lg object-cover">
                    @else
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-white/20 border-4 border-white/30 flex items-center justify-center text-4xl font-bold shadow-lg backdrop-blur-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">{{ Auth::user()->name }}</h1>
                    <p class="text-emerald-100 font-medium text-lg mb-4">{{ Auth::user()->email }}</p>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md border border-white/10 text-sm font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                        Member sejak {{ Auth::user()->created_at->format('M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
        
        <!-- Left Column: Settings -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Profile Info -->
            <div class="bg-white p-8 rounded-[2rem] shadow-soft border border-gray-100">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Password -->
            @if(!Auth::user()->google_id)
            <div class="bg-white p-8 rounded-[2rem] shadow-soft border border-gray-100">
                @include('profile.partials.update-password-form')
            </div>
            @endif

            <!-- Delete Account -->
            <div class="bg-white p-8 rounded-[2rem] shadow-soft border border-gray-100">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

        <!-- Right Column: Preferences & Actions -->
        <div class="space-y-8">
            <!-- Fasting Reminder -->
            <div class="bg-white p-8 rounded-[2rem] shadow-soft border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-50 rounded-full blur-3xl -mr-10 -mt-10"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center text-yellow-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Pengingat Puasa</h2>
                    <p class="text-sm text-gray-500 mb-6 leading-relaxed">
                        Dapatkan notifikasi pengingat untuk puasa sunnah (Senin, Kamis, Ayyamul Bidh) tepat waktu.
                    </p>
                    <div class="flex flex-col gap-3">
                        <button onclick="subscribeToPush()" class="w-full py-3 px-4 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-all shadow-lg shadow-gray-900/10 flex items-center justify-center gap-2">
                            <span>Aktifkan Notifikasi</span>
                        </button>
                        <!-- <button onclick="testNotification()" class="w-full py-2 px-4 bg-indigo-50 text-indigo-600 font-bold rounded-xl hover:bg-indigo-100 transition-all border border-indigo-200 text-sm flex items-center justify-center gap-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                             <span>Cirim Test Notifikasi</span>
                        </button> -->
                    </div>
                    <p id="push-status" class="text-xs text-center mt-3 text-gray-400 hidden">Status: Memeriksa...</p>
                </div>
            </div>

            <!-- Logout -->
            <div class="bg-gray-50 p-8 rounded-[2rem] border border-gray-100 text-center">
                 <h2 class="text-lg font-bold text-gray-900 mb-4">Sesi Login</h2>
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-4 bg-white text-red-600 font-bold rounded-2xl border border-gray-200 hover:bg-red-50 hover:border-red-100 hover:shadow-md transition-all group">
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar Aplikasi
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- Push Notification Script -->
    <script>
        async function subscribeToPush() {
            const statusEl = document.getElementById('push-status');
            const btn = document.querySelector('button[onclick="subscribeToPush()"]');

            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                alert('Push messaging is not supported in your browser.');
                return;
            }

            try {
                if (statusEl) {
                    statusEl.classList.remove('hidden');
                    statusEl.innerText = 'Meminta izin...';
                }
                
                // 1. Ask Permission
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    throw new Error('Izin notifikasi ditolak.');
                }

                if (statusEl) statusEl.innerText = 'Mencari Service Worker...';

                // 2. Get active registration or register new one
                let registration = await navigator.serviceWorker.getRegistration();

                if (!registration) {
                     if (statusEl) statusEl.innerText = 'Mendaftarkan Service Worker...';
                     registration = await navigator.serviceWorker.register('/sw.js');
                }
                
                // Wait for it to be ready/active
                if (!registration.active) {
                    if (statusEl) statusEl.innerText = 'Menunggu Service Worker aktif...';
                    await new Promise((resolve) => {
                         const check = () => {
                             if (registration.active) resolve();
                             else setTimeout(check, 500);
                         };
                         check();
                    });
                }
                
                // Double check ready state
                registration = await navigator.serviceWorker.ready;

                if (statusEl) statusEl.innerText = 'Memproses subscription...';
                
                // 3. Subscribe
                const vapidKey = document.querySelector('meta[name="vapid-key"]').content;
                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(vapidKey)
                });

                if (statusEl) statusEl.innerText = 'Menyimpan data...';

                // 4. Send to Backend
                await fetch('{{ route('push.subscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(subscription)
                });

                if (statusEl) {
                    statusEl.innerText = 'Berhasil aktif!';
                    statusEl.classList.add('text-green-500');
                    statusEl.classList.remove('text-gray-400');
                }
                
                btn.innerHTML = '<span>Notifikasi Aktif</span><svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                btn.classList.remove('bg-gray-900', 'hover:bg-black');
                btn.classList.add('bg-green-600', 'hover:bg-green-700');

            } catch (error) {
                console.error('Push Error:', error);
                if (statusEl) {
                    statusEl.innerText = 'Gagal: ' + error.message;
                    statusEl.classList.add('text-red-500');
                }
                alert('Gagal mengaktifkan notifikasi: ' + error.message);
            }
        }

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        async function testNotification() {
            try {
                const response = await fetch('{{ route('push.test') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Notifikasi terkirim! Silakan cek notifikasi Anda.');
                } else {
                    alert('Gagal mengirim notifikasi: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim notifikasi.');
            }
        }
    </script>
</x-app-layout>
