<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FastingMate - Teman Ibadah Muslimah Modern</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        amiri: ['Amiri', 'serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        rose: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#f43f5e',
                            600: '#e11d48', // femine accent
                        },
                        teal: {
                            50: '#f0fdfa',
                            100: '#ccfbf1', // soft teal
                            500: '#14b8a6',
                            600: '#0d9488',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
    </style>
</head>
<body class="antialiased font-sans text-gray-900 bg-[#FAFAFA] overflow-x-hidden selection:bg-emerald-100 selection:text-emerald-900">
    
    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 glass border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-emerald-500/20 transform hover:scale-110 transition-transform">
                        FM
                    </div>
                    <span class="font-bold text-xl tracking-tight text-gray-800">FastingMate</span>
                </div>
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 rounded-full bg-white text-gray-700 font-semibold hover:bg-gray-50 transition-all text-sm border border-gray-200 shadow-sm hover:shadow-md">Dashboard</a>
                        @else
                            <a href="{{ url('/login') }}" class="px-6 py-2.5 rounded-full bg-gray-900 hover:bg-black text-white font-medium transition-all shadow-lg shadow-gray-900/20 text-sm hover:-translate-y-0.5 transform">
                                Masuk
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-24 sm:pt-48 sm:pb-32 overflow-hidden">
        <!-- Floating Elements -->
        <div class="absolute -top-24 -right-24 w-[600px] h-[600px] bg-teal-100/40 rounded-full blur-[100px] animate-pulse-slow mix-blend-multiply"></div>
        <div class="absolute top-48 -left-24 w-[400px] h-[400px] bg-rose-100/40 rounded-full blur-[80px] mix-blend-multiply"></div>
        <div class="absolute bottom-0 right-1/3 w-[300px] h-[300px] bg-emerald-50/60 rounded-full blur-[60px]"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center z-10">
             <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-emerald-100 shadow-sm mb-8 animate-fade-in-up">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[11px] font-bold uppercase tracking-widest text-emerald-600">Platform Muslimah Modern</span>
            </div>
            
            <h1 class="text-5xl sm:text-7xl font-extrabold tracking-tight mb-8 text-gray-900 leading-[1.15]">
                Lunasi Hutang Puasa<br class="hidden sm:block"> dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500 relative">
                    Hati Tenang
                    <svg class="absolute w-full h-3 -bottom-1 left-0 text-emerald-200/50 -z-10" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 5 L 100 10 L 0 10 Z" fill="currentColor"/></svg>
                </span>
            </h1>
            
            <p class="text-lg sm:text-xl text-gray-500 max-w-2xl mx-auto mb-12 leading-relaxed font-medium">
                Teman ibadah digitalmu yang mengerti kebutuhan wanita. Catat qadha, pantau siklus haid, dan nikmati fitur islami lainnya dalam satu aplikasi.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ url('/register') }}" class="group relative w-full sm:w-auto px-8 py-4 rounded-2xl bg-gray-900 text-white font-bold text-lg shadow-xl shadow-gray-900/10 hover:shadow-2xl hover:shadow-gray-900/20 transition-all hover:-translate-y-1 overflow-hidden">
                    <span class="relative z-10">Mulai Perjalananmu</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-800 to-black opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </a>
                <a href="#features" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white text-gray-600 font-semibold border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all hover:-translate-y-0.5">
                    Pelajari Selengkapnya
                </a>
            </div>
        </div>

        <!-- Visual Interest -->
        <div class="mt-20 relative max-w-6xl mx-auto px-4 animate-float">
            <div class="bg-white/80 backdrop-blur-xl rounded-t-[3rem] shadow-2xl border border-white/20 p-4 sm:p-8 mx-auto max-w-4xl relative z-10">
                <div class="absolute inset-0 bg-gradient-to-b from-white/50 to-transparent rounded-t-[3rem]"></div>
                <!-- Grid of features preview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                     <!-- Card 1 -->
                     <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-50 flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-1">Tracking Haid</h3>
                        <p class="text-xs text-gray-500">Otomatis konversi hari haid ke hutang puasa.</p>
                     </div>
                     <!-- Card 2 -->
                     <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-[2rem] p-6 shadow-lg text-white flex flex-col items-center text-center transform scale-110">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-white mb-4 backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="font-bold mb-1">Jadwal Otomatis</h3>
                        <p class="text-xs text-emerald-100">Rencana puasa Senin-Kamis yang terstruktur.</p>
                     </div>
                     <!-- Card 3 -->
                     <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-50 flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-1">Kalkulator Fidyah</h3>
                        <p class="text-xs text-gray-500">Data resmi Baznas 2024/2025.</p>
                     </div>
                </div>
            </div>
            <!-- Decorative Back layers -->
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-100/40 to-transparent blur-3xl -z-10 transform translate-y-20"></div>
        </div>
    </div>

    <!-- Features Grid -->
    <div id="features" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="text-emerald-600 font-bold tracking-wider uppercase text-xs mb-3 block">Semua Fitur</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6">Lengkap Untuk Kebutuhan Ibadahmu</h2>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">Dari mencatat hutang hingga memperkaya wawasan, semua ada di sini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Smart Qadha Tracker -->
                 <div class="group p-8 rounded-[2rem] bg-gray-50 hover:bg-emerald-50/50 transition-all duration-300 border border-transparent hover:border-emerald-100">
                    <div class="w-14 h-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform text-emerald-600 border border-gray-100">
                        �
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pencatat Hutang Puasa</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Tak perlu lagi menebak-nebak sisa hutangmu. Catat detail hutang per tahun Ramadhan dengan rapi dan terorganisir.
                    </p>
                </div>

                <!-- Auto Scheduler -->
                <div class="group p-8 rounded-[2rem] bg-gray-50 hover:bg-teal-50/50 transition-all duration-300 border border-transparent hover:border-teal-100">
                    <div class="w-14 h-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform text-teal-600 border border-gray-100">
                        🗓️
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Penjadwalan Otomatis</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Kami buatkan rencana puasa Senin-Kamis atau Ayyamul Bidh secara otomatis agar hutangmu lunas tepat waktu sebelum Ramadhan berikutnya.
                    </p>
                </div>

                <!-- Menstrual Tracker -->
                <div class="group p-8 rounded-[2rem] bg-gray-50 hover:bg-rose-50/50 transition-all duration-300 border border-transparent hover:border-rose-100">
                    <div class="w-14 h-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform text-rose-500 border border-gray-100">
                        🌸
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Integrasi Siklus Haid</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Tandai hari berhalanganmu dengan mudah. Sistem akan otomatis mengkonversinya menjadi catatan hutang puasa baru. Praktis!
                    </p>
                </div>

                <!-- Fidyah -->
                <div class="group p-8 rounded-[2rem] bg-gray-50 hover:bg-orange-50/50 transition-all duration-300 border border-transparent hover:border-orange-100">
                    <div class="w-14 h-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform text-orange-500 border border-gray-100">
                        🍚
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Kalkulator Fidyah</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Bingung hitung fidyah? Kami bantu hitungkan nominalnya sesuai tarif resmi Baznas terbaru di berbagai wilayah Indonesia.
                    </p>
                </div>

                <!-- Tadabbur -->
                <div class="group p-8 rounded-[2rem] bg-gray-50 hover:bg-indigo-50/50 transition-all duration-300 border border-transparent hover:border-indigo-100">
                    <div class="w-14 h-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform text-indigo-600 border border-gray-100">
                        ✨
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Tadabbur Harian & Notifikasi</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Suntikan semangat lewat notifikasi pengingat puasa yang lembut dan ayat-ayat pilihan yang muncul setiap hari untuk direnungkan.
                    </p>
                </div>

                 <!-- Wawasan -->
                 <div class="group p-8 rounded-[2rem] bg-gray-50 hover:bg-purple-50/50 transition-all duration-300 border border-transparent hover:border-purple-100">
                    <div class="w-14 h-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform text-purple-600 border border-gray-100">
                        �
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Wawasan & Artikel Islami</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Jawaban atas keraguanmu. Akses kumpulan artikel tentang fiqih wanita, kesehatan, dan motivasi ibadah yang terpercaya.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-gray-900 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden">
                <!-- Background blobs within card -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-gray-800 rounded-full blur-[80px] -mr-20 -mt-20 opacity-50"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-900/40 rounded-full blur-[80px] -ml-20 -mb-20"></div>
                
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-8 tracking-tight relative z-10">
                    Siap untuk Melunasi Hutang Puasa?
                </h2>
                <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto relative z-10">
                    Bergabunglah dengan ribuan muslimah lainnya yang sudah merasakan ketenangan dalam mengatur ibadah qadha mereka.
                </p>
                <div class="relative z-10">
                    <a href="{{ url('/register') }}" class="inline-block px-10 py-5 rounded-full bg-white text-gray-900 font-bold text-lg shadow-xl hover:bg-gray-50 hover:scale-105 transition-all">
                        Buat Akun Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-12 border-t border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-2.5 mb-2">
                     <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-md">FM</div>
                     <span class="font-bold text-lg text-gray-900 tracking-tight">FastingMate</span>
                </div>
                <p class="text-sm text-gray-500 font-medium">Teman ibadah digital untuk muslimah produktif.</p>
            </div>
            <!-- <div class="flex gap-6 text-sm text-gray-400 font-medium">
                <a href="#" class="hover:text-emerald-600 transition-colors">Tentang Kami</a>
                <a href="#" class="hover:text-emerald-600 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-emerald-600 transition-colors">Bantuan</a>
            </div> -->
            <div class="text-sm text-gray-400 font-medium">
                &copy; {{ date('Y') }} FastingMate.
            </div>
        </div>
    </footer>
</body>
</html>
