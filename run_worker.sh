#!/bin/bash

# Script ini dibuat untuk menjalankan Queue Worker dan Scheduler di lingkungan Easypanel/Docker
# Pastikan script ini executable: chmod +x run_worker.sh

echo ">>> Starting Custom Worker Script..."

# 1. Jalankan Queue Worker (untuk memproses notifikasi async)
# Kita jalankan sebagai background process &
php artisan queue:work --tries=3 --timeout=90 &

# 2. Jalankan Scheduler (untuk reminder puasa & hadits harian)
# Scheduler perlu dijalankan setiap menit. Di container tanpa cron, kita bisa pakai loop while.
echo ">>> Starting Scheduler Loop..."

while [ true ]
do
  php artisan schedule:run --verbose --no-interaction &
  sleep 60
done
