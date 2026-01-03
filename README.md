# FastingMate 🌙

**FastingMate** adalah aplikasi web modern yang membantu muslimah mengelola hutang puasa Ramadhan dengan tenang dan terencana. Aplikasi ini dirancang untuk mencatat, menjadwalkan, dan melacak Qadha puasa, serta menghitung kewajiban Fidyah secara otomatis.

![FastingMate Dashboard](https://via.placeholder.com/1200x600?text=FastingMate+Preview)

## 🌟 Fitur Utama

*   **Multi-Year Debt Tracker**: Pisahkan hutang puasa berdasarkan tahun untuk akurasi perhitungan fidyah.
*   **Smart Auto-Scheduler**: Generate jadwal puasa Senin & Kamis secara otomatis hingga hutang lunas.
*   **Menstrual Cycle Sync**: Catat periode haid dan otomatis konversi hari-hari tersebut menjadi hutang puasa baru.
*   **Fidyah Calculator**: Hitung total beras/uang yang harus dibayarkan jika hutang melewati satu Ramadhan berikutnya.
*   **Modern UI**: Antarmuka "Kekinian" berbasis Tailwind CSS yang responsif (Mobile-First) dan elegan.
*   **Privacy Friendly**: Mode privasi untuk menyembunyikan nominal hutang di tempat umum.

## 🛠️ Teknologi

*   **Backend**: Laravel 12 (PHP 8.2+)
*   **Frontend**: Tailwind CSS 3, Alpine.js, Blade Templates
*   **Database**: MySQL / MariaDB
*   **Deployment**: Docker Ready (Easypanel Support)

## 🚀 Cara Install (Local Development)

Ikuti langkah berikut untuk menjalankan aplikasi di komputer Anda:

### Prasyarat
*   PHP >= 8.2
*   Composer
*   Node.js & NPM
*   MySQL

### Langkah-langkah

1.  **Clone Repository**
    ```bash
    git clone https://github.com/luqni/fastingmate.git
    cd fastingmate
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**
    Salin file `.env.example` ke `.env` dan atur database Anda.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Database Migration**
    Pastikan database sudah dibuat di MySQL, lalu jalankan:
    ```bash
    php artisan migrate --seed
    ```

5.  **Jalankan Aplikasi**
    Buka dua terminal terpisah:
    ```bash
    # Terminal 1 (Server PHP)
    php artisan serve

    # Terminal 2 (Vite Assets)
    npm run dev
    ```

6.  Akses aplikasi di `http://localhost:8000`.

---

## 🐳 Cara Install (Docker / Production)

Aplikasi ini sudah siap untuk dideploy menggunakan Docker (misalnya di Easypanel atau Coolify).

1.  **Build & Run**
    ```bash
    docker build -t fastingmate .
    docker run -p 80:80 \
      -e APP_KEY=... \
      -e DB_HOST=... \
      -e DB_DATABASE=... \
      -e DB_USERNAME=... \
      -e DB_PASSWORD=... \
      fastingmate
    ```

2.  **Easypanel Deployment**
    *   Buat App baru dari Source (GitHub).
    *   Pilih repository ini.
    *   Easypanel akan otomatis mendeteksi `Dockerfile`.
    *   Isi Environment Variables di tab Environment.
    *   Klik Deploy.

---

## 👨‍💻 Credits

Dibuat dan dikembangkan oleh **Muhammad Luqni Baehaqi**.

---

© 2026 FastingMate. All rights reserved.
