# Panduan Deploy Push Notification di Easypanel

## 📋 Checklist Deployment

### 1. Environment Variables
Pastikan di Easypanel sudah set:
```bash
VAPID_PUBLIC_KEY=BPYmxA9WtyVpz4WHwR-BLzewEK9HS39WHjbXZspgfDTrmXxcgJiOvZ1TYygrJmBfBLML9R2HiimqLFruz9PpR-k
VAPID_PRIVATE_KEY=PhaS2UMaqUg5yvqCSPXnhHXkRw3Kd1cEa9wxNOhbSNY
VITE_VAPID_PUBLIC_KEY="${VAPID_PUBLIC_KEY}"
QUEUE_CONNECTION=database
```

### 2. Build & Deploy
```bash
# Build image
docker build -t fastingmate:latest .

# Push ke registry (jika perlu)
docker push your-registry/fastingmate:latest
```

### 3. Verifikasi Setelah Deploy

#### Cek Queue Worker
```bash
docker exec -it <container-name> supervisorctl status
```

Output yang benar:
```
laravel-scheduler                RUNNING   pid 123, uptime 0:01:00
laravel-web                      RUNNING   pid 124, uptime 0:01:00
laravel-worker:laravel-worker_00 RUNNING   pid 125, uptime 0:01:00
```

#### Test Push Notification
```bash
# SSH ke container
docker exec -it <container-name> bash

# Test command
php artisan fasting:reminders
```

### 4. Subscribe dari Browser
1. Buka https://fastingmate.my.id
2. Login
3. Klik tombol notifikasi (bell icon)
4. Allow permission
5. Notifikasi akan muncul setiap hari jam 20:00 WIB

## 🔧 Troubleshooting

### Queue Worker Tidak Jalan
```bash
# Restart supervisor
supervisorctl restart laravel-worker:*
```

### Scheduler Tidak Jalan
```bash
# Cek logs
supervisorctl tail -f laravel-scheduler
```

### Notifikasi Tidak Muncul
1. Cek apakah user sudah subscribe:
   ```bash
   php artisan tinker
   \App\Models\User::has('pushSubscriptions')->count()
   ```

2. Cek queue jobs:
   ```bash
   php artisan queue:monitor
   ```

3. Cek logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## ✅ Fitur yang Sudah Berjalan

- ✅ Queue Worker (untuk kirim notifikasi)
- ✅ Laravel Scheduler (untuk jadwal otomatis)
- ✅ Push Notification Support
- ✅ Fasting Reminders (20:00 WIB)
- ✅ Daily Hadith (05:00 WIB)
