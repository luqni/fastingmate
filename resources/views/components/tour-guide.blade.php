<!-- Intro.js Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css">
<style>
    /* Custom Theme for FastingMate */
    .introjs-tooltip {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.2);
        color: #1f2937;
        padding: 1rem;
        border: 1px solid #e5e7eb;
    }
    .introjs-tooltipTitle {
        color: #0284c7; /* Primary 600 */
        font-weight: 700;
        font-size: 1.1rem;
    }
    .introjs-button {
        background: #f3f4f6;
        border: none;
        border-radius: 0.5rem;
        color: #4b5563;
        padding: 0.5rem 1rem;
        font-weight: 600;
        text-shadow: none;
        transition: all 0.2s;
    }
    .introjs-button:hover {
        background: #e5e7eb;
        color: #111827;
        box-shadow: none;
        border: none;
    }
    .introjs-nextbutton, .introjs-donebutton {
        background: #0ea5e9 !important; /* Primary 500 */
        color: white !important;
    }
    .introjs-nextbutton:hover, .introjs-donebutton:hover {
        background: #0284c7 !important; /* Primary 600 */
    }
    .introjs-bullets ul li a.active {
        background: #0284c7;
    }
    .introjs-helperLayer {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(2px);
    }
</style>

<!-- Intro.js Script -->
<script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/intro.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    window.startTour = function() {
        const path = window.location.pathname;
        let steps = [];
        let tourKey = '';

        if (path === '/dashboard' || path === '/' || path.includes('/home')) {
            tourKey = 'tour_completed_dashboard';
            steps = [
                {
                    intro: "👋 Selamat datang di <b>FastingMate</b>! <br>Aplikasi teman ibadah puasa Anda. <br>Mari kita lihat fitur-fiturnya."
                },
                {
                    element: document.querySelector('#sticky-prayer-bar'),
                    title: 'Info Cepat & Notifikasi',
                    intro: 'Lihat waktu sholat berikutnya dan hitung mundur Imsak/Iftar selalu di sini. Bar ini akan berubah warna sesuai waktu (Sahur/Buka).',
                    position: 'bottom'
                },
                {
                    element: document.querySelector('#prayer-widget'),
                    title: 'Jadwal Sholat & Lokasi',
                    intro: 'Jadwal sholat lengkap hari ini. <br><b>Tips:</b> Klik nama Kota di sini untuk mengubah lokasi Anda.',
                },
                {
                    element: document.querySelector('#di-wrapper'), // Dynamic Island
                    title: 'Tadabbur & Quran',
                    intro: 'Renungan harian dan ayat pilihan muncul di sini. Klik untuk membuka dan menyimpan refleksi Anda.',
                },
                {
                    element: document.querySelector('#profile-icon'),
                    title: 'Profil & Pengaturan',
                    intro: 'Akses pengaturan akun, reset panduan aplikasi, dan kelola data Anda dari sini.',
                    position: 'bottom'
                },
                {
                    element: document.querySelector('nav.fixed.bottom-0'), // Bottom Nav
                    title: 'Navigasi Utama',
                    intro: 'Akses cepat ke fitur Hutang Puasa, Zakat/Fidyah, dan Kalender Puasa/Haid dari sini.',
                    position: 'top'
                },
                {
                    element: document.querySelector('button[onclick="window.showInstallPrompt()"]'), // Install Button in Header
                    title: 'Install Aplikasi',
                    intro: 'Klik di sini untuk menginstall aplikasi ke HP Anda agar lebih mudah diakses.',
                }
            ];
        } else if (path.includes('/fasting-debts')) {
            tourKey = 'tour_completed_debts';
            steps = [
                {
                     intro: "📋 Ini adalah halaman <b>Hutang Puasa</b>. <br>Catat, pantau, dan lunasi qadha puasa Anda dengan teratur di sini."
                },
                {
                    element: document.querySelector('#add-debt-card'),
                    title: 'Tambah Data Hutang',
                    intro: 'Mulai dengan klik di sini untuk menambahkan hutang puasa baru (per tahun). Anda bisa set target lunasnya juga.',
                    position: 'bottom'
                }
            ];

            // Check if there are debts displayed
            const debtCard = document.querySelector('.grid-cols-1 > .bg-white.shadow-soft');
            // Ensure it's not the "Belum ada data" placeholder (which usually has svg icon centrally)
            if (debtCard && debtCard.querySelector('.font-extrabold')) {
                steps.push({
                    element: debtCard,
                    title: 'Kartu Hutang',
                    intro: 'Pantau sisa hutang dan progres pembayaran Anda di sini.',
                    position: 'top'
                });
                
                const payBtn = debtCard.querySelector('button.text-teal-600'); // Bayar button
                if (payBtn) {
                     steps.push({
                        element: payBtn,
                        title: 'Bayar Hutang',
                        intro: 'Setiap kali Anda selesai berpuasa qadha, klik tombol ini untuk mencatatnya.',
                    });
                }
                
                const scheduleBtn = debtCard.querySelector('button.text-indigo-600'); // Jadwal button
                if (scheduleBtn) {
                     steps.push({
                        element: scheduleBtn,
                        title: 'Auto Jadwal',
                        intro: 'Bingung kapan harus puasa? Gunakan fitur ini untuk membuat jadwal puasa otomatis sesuai target tanggal lunas.',
                    });
                }
            }
        } else if (path.includes('/fidyah')) {
            tourKey = 'tour_completed_fidyah';
            steps = [
                {
                    intro: "🌾 Selamat datang di <b>Kalkulator Fidyah</b>. <br>Fitur ini membantu menghitung kewajiban fidyah Anda secara otomatis."
                },
                {
                    element: document.querySelector('h4.text-lg.font-bold') ? document.querySelector('h4.text-lg.font-bold').parentNode : null, // Rincian Hutang Container
                    title: 'Rincian Hutang',
                    intro: 'Daftar hutang puasa yang belum lunas akan muncul di sini. <br>Sistem otomatis menghitung <b>denda</b> jika puasa belum diganti hingga Ramadhan tahun berikutnya.',
                    position: 'right'
                },
                {
                    element: document.querySelector('form[action*="update-rate"]'),
                    title: 'Atur Tarif',
                    intro: 'Pilih tarif standar atau masukkan tarif kustom sesuai kemampuan atau kebijakan Baznas daerah Anda.',
                    position: 'left'
                },
                {
                    element: document.querySelector('.bg-gradient-to-br.from-emerald-500'),
                    title: 'Total Estimasi',
                    intro: 'Total biaya fidyah yang harus dibayarkan akan dihitung otomatis di sini.',
                    position: 'top'
                }
            ];
        } else if (path.includes('/fasting-plans')) {
            tourKey = 'tour_completed_plans';
            steps = [
                {
                    intro: "📅 Selamat datang di <b>Kalender Puasa</b>. <br>Rencanakan ibadah puasa sunnah dan pantau progres qadha Anda di sini."
                },
                {
                    element: document.querySelector('.flex.flex-wrap.gap-3'), // Legend
                    title: 'Legenda Warna',
                    intro: 'Pahami arti warna pada kalender: <br>🔵 Senin/Kamis <br>🟣 Ayyamul Bidh <br>🔴 Dilarang Puasa',
                    position: 'bottom'
                },
                {
                    element: document.querySelector('.grid.grid-cols-7.auto-rows-fr'), // Calendar Grid
                    title: 'Interaksi Kalender',
                    intro: 'Setiap kotak tanggal menampilkan tanggal Masehi & Hijriyah. <br><b>Arahkan kursor (Hover)</b> pada tanggal untuk merencanakan puasa atau menandainya sebagai selesai.',
                    position: 'top'
                }
            ];
            
            const firstActionableCell = document.querySelector('button[aria-label="Tambah Rencana Puasa"]');
            
            if(firstActionableCell) {
                steps.push({
                   element: firstActionableCell.closest('.group'), // The cell container
                   title: 'Tambah Rencana',
                   intro: 'Klik tombol <b>Rencana (+)</b> pada tanggal yang diinginkan untuk menjadwal puasa.',
                   position: 'top'
                });
            }
        } else if (path.includes('/menstrual-cycles')) {
            tourKey = 'tour_completed_menstrual';
            steps = [
                {
                    intro: "🌸 Selamat datang di <b>Pencatat Haid</b>. <br>Fitur khusus untuk mencatat periode haid selama bulan Ramadhan."
                },
                {
                    element: document.querySelector('.bg-white.rounded-\\[2rem\\]'), // Main Card
                    title: 'Status Siklus',
                    intro: 'Di sini Anda bisa memulai pencatatan haid baru atau menyelesaikan siklus yang sedang berjalan.',
                    position: 'bottom'
                }
            ];

            // Conditional Step for Action Button
            const startBtn = Array.from(document.querySelectorAll('button')).find(b => b.textContent.includes('Mulai Haid'));
            const endBtn = Array.from(document.querySelectorAll('button')).find(b => b.textContent.includes('Haid Selesai'));

            if (startBtn) {
                steps.push({
                    element: startBtn,
                    title: 'Mulai Pencatatan',
                    intro: 'Klik tombol ini saat haid dimulai. <br>Sistem akan otomatis menghitung hari-hari yang Anda tinggalkan.',
                    position: 'top'
                });
            } else if (endBtn) {
                steps.push({
                    element: endBtn,
                    title: 'Selesai Haid',
                    intro: 'Klik tombol ini segera setelah haid selesai (bersuci). <br>Data akan disimpan dan dikonversi menjadi hutang puasa.',
                    position: 'top'
                });
            }

            // History
            const historyContainer = document.querySelector('.space-y-4');
            if (historyContainer && historyContainer.children.length > 0) {
                 steps.push({
                    element: historyContainer,
                    title: 'Riwayat Haid',
                    intro: 'Daftar riwayat haid Anda tersimpan di sini.',
                    position: 'top'
                 });
            }
        } else if (path.includes('/quran')) {
            tourKey = 'tour_completed_quran';
            steps = [
                {
                    intro: "📖 Selamat datang di <b>Al-Quran</b>!<br>Baca dan renungkan Al-Quran dengan tampilan Mushaf Madinah yang indah."
                },
                {
                    element: document.querySelector('.grid-cols-1 > a'), // First surah link
                    title: 'Daftar Surah',
                    intro: 'Pilih surah yang ingin Anda baca dari daftar 114 surah. Klik salah satu untuk membuka halaman baca.',
                    position: 'bottom'
                }
            ];
            
            // If on surah detail page
            const mushafToggle = document.querySelector('button[x-text*="mode"]');
            if (mushafToggle) {
                steps.push({
                    element: mushafToggle.closest('.flex.gap-2'),
                    title: 'Mode Tampilan',
                    intro: 'Toggle antara <b>Mushaf</b> (tampilan seperti Al-Quran fisik) dan <b>List</b> (dengan terjemahan per ayat).',
                    position: 'bottom'
                });
                
                const bookmarkBtn = document.querySelector('#profile-icon') ? document.querySelector('button[title*="Terakhir"]') : null;
                if (bookmarkBtn) {
                    steps.push({
                        element: bookmarkBtn,
                        title: 'Bookmark',
                        intro: 'Tandai halaman terakhir dibaca. Icon akan terisi penuh jika sudah ada bookmark tersimpan.',
                        position: 'bottom'
                    });
                }
                
                steps.push({
                    element: document.querySelector('nav.fixed.bottom-0') || document.querySelector('.flex.gap-3.items-center'),
                    title: 'Navigasi Halaman',
                    intro: 'Gunakan tombol panah atau slider untuk berpindah halaman. Swipe kiri/kanan juga bisa digunakan di mobile.',
                    position: 'top'
                });
            }
        } else if (path.includes('/ibadah/dhikr')) {
            tourKey = 'tour_completed_dhikr';
            steps = [
                {
                    intro: "🤲 Selamat datang di <b>Dzikir</b>!<br>Amalkan dzikir pagi/petang dengan panduan lengkap dan counter otomatis."
                },
                {
                    element: document.querySelector('.bg-gradient-to-r.from-emerald-600'),
                    title: 'Progress Bar',
                    intro: 'Pantau progres dzikir Anda. Bar ini akan terisi seiring Anda menyelesaikan setiap dzikir.',
                    position: 'bottom'
                },
                {
                    element: document.querySelector('.bg-white.rounded-3xl'),
                    title: 'Kartu Dzikir',
                    intro: 'Setiap kartu berisi teks Arab, latin, terjemahan, dan dalil dari dzikir tersebut. Scroll untuk membaca selengkapnya.',
                    position: 'top'
                },
                {
                    element: document.querySelector('button[\\@click="handleTap()"]'),
                    title: 'Counter Dzikir',
                    intro: '<b>Ketuk tombol ini</b> atau area kartu untuk menghitung dzikir. Angka akan bertambah hingga mencapai target. Tombol akan berubah hijau saat selesai.',
                    position: 'top'
                },
                {
                    element: document.querySelector('.bg-white\\/80.backdrop-blur-md'),
                    title: 'Navigasi Dzikir',
                    intro: 'Gunakan tombol <b>Selanjutnya</b> untuk pindah ke dzikir berikutnya setelah selesai, atau <b>Lewati</b> jika ingin skip.',
                    position: 'top'
                }
            ];
        }

        if (steps.length > 0) {
            introJs().setOptions({
                steps: steps.filter(s => s.element !== null || !s.element), 
                showProgress: true,
                showBullets: false,
                exitOnOverlayClick: true,
                nextLabel: 'Lanjut',
                prevLabel: 'Kembali',
                doneLabel: 'Selesai',
                dontShowAgain: true,
                dontShowAgainLabel: 'Jangan tampilkan lagi'
            }).oncomplete(function() {
                localStorage.setItem(tourKey, 'true');
            }).onexit(function() {
                 localStorage.setItem(tourKey, 'true');
            }).start();
        }
    }

    window.resetTour = function() {
        const keys = [
            'tour_completed_dashboard',
            'tour_completed_debts',
            'tour_completed_fidyah',
            'tour_completed_plans',
            'tour_completed_menstrual',
            'tour_completed_quran',
            'tour_completed_dhikr'
        ];
        
        keys.forEach(key => localStorage.removeItem(key));
        
        const path = window.location.pathname;
        const hasTour = (path === '/dashboard' || path === '/' || path.includes('/fasting-debts') || path.includes('/fidyah') || path.includes('/fasting-plans') || path.includes('/menstrual-cycles') || path.includes('/quran') || path.includes('/ibadah/dhikr'));
        
        if (hasTour) {
             // If we are on a page with a tour, start it immediately
             window.startTour();
        } else {
             // Otherwise, show feedback and guide to Dashboard
             if (typeof Swal !== 'undefined') {
                 Swal.fire({
                     title: 'Panduan Direset!',
                     icon: 'success',
                     html: 'Panduan aplikasi telah diatur ulang.<br>Silakan kunjungi <b>Dashboard</b> atau menu lainnya untuk memulai tur.',
                     confirmButtonText: 'Ke Dashboard',
                     confirmButtonColor: '#0ea5e9',
                     showCancelButton: true,
                     cancelButtonText: 'Tutup'
                 }).then((result) => {
                     if (result.isConfirmed) {
                         window.location.href = '/dashboard';
                     }
                 });
             } else {
                 alert('Panduan berhasil direset. Silakan kembali ke Dashboard.');
                 window.location.href = '/dashboard';
             }
        }
    }

    // Auto-start check
    setTimeout(() => {
        const path = window.location.pathname;
        let tourKey = '';
        
        if (path === '/dashboard' || path === '/' || path.includes('/home')) {
            tourKey = 'tour_completed_dashboard';
        } else if (path.includes('/fasting-debts')) {
            tourKey = 'tour_completed_debts';
        } else if (path.includes('/fidyah')) {
            tourKey = 'tour_completed_fidyah';
        } else if (path.includes('/fasting-plans')) {
            tourKey = 'tour_completed_plans';
        } else if (path.includes('/menstrual-cycles')) {
            tourKey = 'tour_completed_menstrual';
        } else if (path.includes('/quran')) {
            tourKey = 'tour_completed_quran';
        } else if (path.includes('/ibadah/dhikr')) {
            tourKey = 'tour_completed_dhikr';
        }

        if (tourKey && !localStorage.getItem(tourKey)) {
             // Only auto-start if relevant elements exist
             if (path.includes('/fasting-debts') || path.includes('/fidyah') || path.includes('/fasting-plans') || path.includes('/menstrual-cycles') || path.includes('/quran') || path.includes('/ibadah/dhikr')) {
                 window.startTour();
             } else if (document.querySelector('#sticky-prayer-bar')) {
                 // For dashboard
                 window.startTour();
             }
        }
    }, 1500); // 1.5s delay
});
</script>
