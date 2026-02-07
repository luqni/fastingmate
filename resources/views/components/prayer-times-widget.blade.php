<div class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-lg overflow-hidden text-white relative" id="prayer-widget" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);">
    <!-- Header -->
    <div class="p-4 md:p-6 pb-2 md:pb-4">
        <div class="flex items-center justify-between mb-2 md:mb-4">
            <h3 class="text-lg md:text-xl font-bold flex items-center gap-2 text-white drop-shadow-sm">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707l-.707-.707V8a6 6 0 00-6-6z"></path>
                </svg>
                Jadwal Sholat
            </h3>
            <button onclick="refreshPrayerTimes()" class="p-2 hover:bg-white/20 rounded-lg transition-colors" title="Refresh">
                <svg class="w-5 h-5 filter drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
        </div>
        <div id="location-display" class="text-sm text-white font-medium drop-shadow-sm"></div>
        <div id="date-display" class="text-sm text-white mt-1 drop-shadow-sm"></div>
    </div>

    <!-- Prayer Times List -->
    <div class="bg-black/30 backdrop-blur-sm px-4 md:px-6 py-3 md:py-4" id="prayer-times-list">
        <div class="text-center py-8" id="loading-state">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-white mx-auto"></div>
            <p class="mt-2 text-white/80">Memuat jadwal sholat...</p>
        </div>

        <div id="location-not-set" class="text-center py-6 hidden">
            <svg class="w-12 h-12 mx-auto text-white/60 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <p class="text-white/90 font-medium mb-2">Lokasi Belum Diatur</p>
            <p class="text-sm text-white/70 mb-4">Silakan atur lokasi Anda untuk melihat jadwal sholat</p>
            <button onclick="showLocationModal()" class="px-4 py-2 bg-white text-indigo-600 rounded-lg font-medium hover:bg-white/90 transition-colors">
                Atur Lokasi
            </button>
        </div>

        <div id="prayer-times-content" class="grid grid-cols-1 md:grid-cols-5 gap-3 md:gap-6 hidden"></div>
    </div>

    <!-- Dynamic Countdown (Iftar / Sahur) -->
    <div id="countdown-section" class="bg-gradient-to-r from-orange-400 to-pink-500 px-6 py-4 hidden">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-white uppercase tracking-wide font-bold drop-shadow-sm" id="countdown-label">Waktu Menuju Berbuka</p>
                <p class="text-2xl font-bold text-white drop-shadow-md" id="countdown-timer">--:--:--</p>
            </div>
            <svg class="w-12 h-12 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-xs text-white mt-1 italic font-medium drop-shadow-sm" id="countdown-message"></p>
    </div>

    <!-- Dua Display -->
    <div id="dua-display" class="bg-black/30 backdrop-blur-sm px-6 py-4 hidden">
        <p class="text-xs text-white uppercase tracking-wide mb-2 font-bold" id="dua-title">🤲 Doa Berbuka Puasa</p>
        <p class="text-sm text-center leading-relaxed mb-2 font-arabic text-white" id="dua-arabic">
            اللَّهُمَّ لَكَ صُمْتُ وَبِكَ آمَنْتُ وَعَلَيْكَ تَوَكَّلْتُ وَعَلَى رِزْقِكَ أَفْطَرْتُ
        </p>
        <p class="text-xs text-white/90 text-center italic" id="dua-latin">
            Allahumma laka sumtu wa bika amantu wa 'alayka tawakkaltu wa 'ala rizqika aftartu
        </p>
        <p class="text-xs text-white/90 text-center mt-1" id="dua-translation">
            "Ya Allah, untuk-Mu aku berpuasa, dan dengan rezeki-Mu aku berbuka"
        </p>
    </div>
</div>

<!-- Location Settings Modal -->
<div id="location-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 px-6 py-4">
            <h3 class="text-xl font-bold text-white">Atur Lokasi Sholat</h3>
        </div>
        <form id="location-form" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                <input type="text" id="prayer-city" name="prayer_city" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900"
                    placeholder="Contoh: Jakarta">
                <p class="mt-1 text-xs text-gray-500">Untuk Indonesia, masukkan nama kota (data dari Kemenag RI)</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Negara</label>
                <input type="text" id="prayer-country" name="prayer_country" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900"
                    placeholder="Contoh: Indonesia" value="Indonesia">
            </div>
            <div id="method-selection">
                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Kalkulasi</label>
                <select id="prayer-method" name="prayer_method" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900">
                    <option value="2">ISNA (Amerika Utara)</option>
                    <option value="3">Muslim World League (MWL)</option>
                    <option value="4">Umm Al-Qura, Makkah</option>
                    <option value="5">Egyptian General Authority</option>
                    <option value="1">University of Karachi</option>
                    <option value="7">Institute of Tehran</option>
                    <option value="8">Gulf Region</option>
                    <option value="11">Singapura</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Untuk Indonesia otomatis menggunakan data Kemenag RI</p>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Simpan Lokasi
                </button>
                <button type="button" onclick="hideLocationModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let prayerData = null;
let countdownInterval = null;
let iftarInterval = null;

// Load prayer times on page load
document.addEventListener('DOMContentLoaded', function() {
    loadPrayerTimes();
    
    // Refresh prayer times at midnight
    const now = new Date();
    const tomorrow = new Date(now);
    tomorrow.setHours(24, 0, 0, 0);
    const msUntilMidnight = tomorrow - now;
    setTimeout(() => {
        loadPrayerTimes();
        setInterval(loadPrayerTimes, 24 * 60 * 60 * 1000); // Refresh daily
    }, msUntilMidnight);
});

async function loadPrayerTimes() {
    try {
        const response = await fetch('{{ route("prayer-times.get") }}');
        const data = await response.json();

        if (data.error) {
            if (data.error === 'location_not_set') {
                showLocationNotSet();
            } else {
                showError('Gagal memuat jadwal sholat');
            }
            return;
        }

        prayerData = data;
        displayPrayerTimes(data);
        startCountdowns(data);
    } catch (error) {
        console.error('Error loading prayer times:', error);
        showError('Terjadi kesalahan jaringan');
    }
}

function displayPrayerTimes(data) {
    // Hide loading, show content
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('location-not-set').classList.add('hidden');
    document.getElementById('prayer-times-content').classList.remove('hidden');

    // Display location
    let locationText = '';
    if (data.source === 'kemenag') {
        locationText = `📍 ${data.location.city} (Data Kemenag RI)`;
    } else {
        const city = data.location.city.trim();
        const country = data.location.country.trim();
        // Avoid duplicate country name if city already contains it
        if (city.toLowerCase().includes(country.toLowerCase())) {
            locationText = `📍 ${city}`;
        } else {
            locationText = `📍 ${city}, ${country}`;
        }
    }
    document.getElementById('location-display').innerHTML = `
        <div class="flex items-center gap-1.5 cursor-pointer hover:underline decoration-white/30" onclick="showLocationModal()" title="Ubah Lokasi">
            <span>${locationText}</span>
            <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
        </div>
    `;
    
    // Display date
    if (data.date) {
        const hijri = data.date.hijri;
        const gregorian = data.date.gregorian;
        document.getElementById('date-display').textContent = 
            `${hijri.day} ${hijri.month.en} ${hijri.year} H • ${gregorian.day} ${gregorian.month.en} ${gregorian.year}`;
    }

    // Display prayer times with Indonesian names
    const prayers = [
        { name: 'Subuh', time: data.timings.Fajr, icon: '🌅', englishName: 'Fajr' },
        { name: 'Dzuhur', time: data.timings.Dhuhr, icon: '☀️', englishName: 'Dhuhr' },
        { name: 'Ashar', time: data.timings.Asr, icon: '🌤️', englishName: 'Asr' },
        { name: 'Maghrib', time: data.timings.Maghrib, icon: '🌆', englishName: 'Maghrib' },
        { name: 'Isya', time: data.timings.Isha, icon: '🌙', englishName: 'Isha' },
    ];

    const content = prayers.map(prayer => {
        const isNext = data.next_prayer && data.next_prayer.name === prayer.englishName;
        // Responsive list item: compact on mobile, spacious on desktop
        return `
            <div class="flex md:flex-col items-center justify-between md:justify-center py-3 px-4 rounded-xl transition-all duration-300 gap-2 md:gap-3 ${isNext ? 'bg-white/20 shadow-lg ring-1 ring-white/30 transform md:-translate-y-1 scale-[1.02]' : 'hover:bg-white/10 hover:shadow-md'}">
                <div class="flex md:flex-col items-center gap-3 md:gap-2">
                    <span class="text-2xl md:text-3xl lg:text-4xl filter drop-shadow-md transition-transform hover:scale-110">${prayer.icon}</span>
                    <span class="font-bold text-white text-base md:text-lg drop-shadow-sm">${prayer.name}</span>
                </div>
                <div class="flex md:flex-col items-center gap-2 md:gap-1">
                    <span class="font-extrabold text-lg md:text-2xl text-white drop-shadow-sm tracking-wide font-mono">${prayer.time}</span>
                    ${isNext ? '<span class="text-[10px] uppercase tracking-wider bg-yellow-400 text-gray-900 px-2 py-0.5 rounded-full font-bold shadow-sm ring-1 ring-yellow-300 mt-1">Next</span>' : ''}
                </div>
            </div>
        `;
    }).join('');

    document.getElementById('prayer-times-content').innerHTML = content;
}

function startCountdowns(data) {
    if (countdownInterval) clearInterval(countdownInterval);
    if (iftarInterval) clearInterval(iftarInterval);

    // Use the new countdown object from backend
    const countdown = data.countdown;

    if (countdown && countdown.is_active && countdown.seconds > 0) {
        document.getElementById('countdown-section').classList.remove('hidden');
        document.getElementById('dua-display').classList.remove('hidden');
        
        // Setup Label & Message
        document.getElementById('countdown-label').textContent = `Waktu Menuju ${countdown.target_label}`;
        document.getElementById('countdown-message').textContent = countdown.message;

        // Setup Dua Content based on type
        if (countdown.dua_type === 'niat_puasa') {
            document.getElementById('dua-title').textContent = '🤲 Niat Puasa Esok Hari';
            document.getElementById('dua-arabic').textContent = 'نَوَيْتُ صَوْمَ غَدٍ عَنْ أَدَاءِ فَرْضِ شَهْرِ رَمَضَانَ هَذِهِ السَّنَةِ لِلَّهِ تَعَالَى';
            document.getElementById('dua-latin').textContent = "Nawaitu sauma ghadin 'an ada'i fardhi syahri Ramadhana hadzihis sanati lillahi ta'ala";
            document.getElementById('dua-translation').textContent = '"Aku berniat puasa esok hari untuk menunaikan fardhu bulan Ramadhan tahun ini karena Allah Ta\'ala"';
        } else {
            // Default to Iftar Dua
            document.getElementById('dua-title').textContent = '🤲 Doa Berbuka Puasa';
            document.getElementById('dua-arabic').textContent = 'ذَهَبَ الظَّمَأُ وَابْتَلَّتِ الْعُرُوقُ، وَثَبَتَ الأَجْرُ إِنْ شَاءَ اللهُ';
            document.getElementById('dua-latin').textContent = "Dzahabaz zhame'u wabtallatil 'uruqu wa tsabbatal ajru insya Allah";
            document.getElementById('dua-translation').textContent = '"Telah hilang dahaga, telah basah urat-urat, dan telah pasti pahala, insya Allah"';
        }
        
        // Start Timer
        let secondsLeft = Math.floor(countdown.seconds);
        updateTimerDisplay(secondsLeft);
        
        iftarInterval = setInterval(() => {
            secondsLeft--;
            if (secondsLeft <= 0) {
                clearInterval(iftarInterval);
                document.getElementById('countdown-timer').textContent = '00:00:00';
                // Trigger refresh to update state (e.g. from Sahur -> Imsak Passed -> Iftar)
                setTimeout(loadPrayerTimes, 2000); 
            } else {
                updateTimerDisplay(secondsLeft);
            }
        }, 1000);

    } else {
        document.getElementById('countdown-section').classList.add('hidden');
        document.getElementById('dua-display').classList.add('hidden');
    }
}

function updateTimerDisplay(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const secs = Math.floor(seconds % 60);
    document.getElementById('countdown-timer').textContent = 
        `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}

function showLocationNotSet() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('prayer-times-content').classList.add('hidden');
    document.getElementById('location-not-set').classList.remove('hidden');
}

function showError(message) {
    document.getElementById('loading-state').innerHTML = `
        <p class="text-white/80">${message}</p>
        <button onclick="loadPrayerTimes()" class="mt-2 px-4 py-2 bg-white/20 rounded-lg hover:bg-white/30">Coba Lagi</button>
    `;
}

function showLocationModal() {
    document.getElementById('location-modal').classList.remove('hidden');
    document.getElementById('location-modal').classList.add('flex');
}

function hideLocationModal() {
    document.getElementById('location-modal').classList.remove('flex');
    document.getElementById('location-modal').classList.add('hidden');
}

function refreshPrayerTimes() {
    document.getElementById('loading-state').classList.remove('hidden');
    document.getElementById('prayer-times-content').classList.add('hidden');
    loadPrayerTimes();
}

// Handle location form submission
document.getElementById('location-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('{{ route("prayer-times.update-location") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            hideLocationModal();
            loadPrayerTimes();
        } else {
            alert('Gagal menyimpan lokasi');
        }
    } catch (error) {
        console.error('Error updating location:', error);
        alert('Terjadi kesalahan jaringan');
    }
});
</script>

<style>
.font-arabic {
    font-family: 'Amiri', 'Traditional Arabic', serif;
    font-size: 1.1rem;
    line-height: 1.8;
}
</style>
