import './bootstrap';
import Swal from 'sweetalert2';
window.Swal = Swal;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Handle Flash Messages
document.addEventListener('DOMContentLoaded', () => {
    console.log('Flash Messages:', window.flashMessages);
    if (window.flashMessages) {
        if (window.flashMessages.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: window.flashMessages.success,
                confirmButtonColor: '#0ca678',
                timer: 3000,
                timerProgressBar: true
            });
        }

        if (window.flashMessages.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: window.flashMessages.error,
                confirmButtonColor: '#e03131',
            });
        }
    }

    // Global Delete Confirmation
    document.body.addEventListener('submit', (e) => {
        if (e.target.classList.contains('delete-confirm-form')) {
            e.preventDefault();
            const form = e.target;

            Swal.fire({
                title: 'Apakah yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Generic Action Confirmation
        if (e.target.classList.contains('action-confirm-form')) {
            e.preventDefault();
            const form = e.target;
            const title = form.dataset.confirmTitle || 'Apakah yakin?';
            const text = form.dataset.confirmText || 'Lanjutkan proses ini?';
            const icon = form.dataset.confirmIcon || 'question';
            const confirmBtnText = form.dataset.confirmBtn || 'Ya, Lanjutkan';

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#0ca678',
                cancelButtonColor: '#3085d6',
                confirmButtonText: confirmBtnText,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
});
import { registerSW } from 'virtual:pwa-register';

// Register Service Worker
registerSW({
    immediate: true,
    onNeedRefresh() {
        console.log('App updated. Please reload.');
    },
    onOfflineReady() {
        console.log('App is ready for offline work.');
    },
});

// PWA Install Prompt
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    // Stash the event so it can be triggered later.
    deferredPrompt = e;

    // Check if user has already seen the prompt
    const hasSeenPrompt = localStorage.getItem('pwa-prompt-shown');

    if (!hasSeenPrompt) {
        window.showInstallPrompt();
    }
});

window.showInstallPrompt = () => {
    if (deferredPrompt) {
        Swal.fire({
            title: 'Install FastingMate',
            text: 'Install aplikasi ini untuk pengalaman yang lebih baik!',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Install',
            cancelButtonText: 'Nanti Saja',
            confirmButtonColor: '#0ca678',
        }).then((result) => {
            if (result.isConfirmed) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            }
            // Mark as shown regardless of choice to prevent auto-popup again
            localStorage.setItem('pwa-prompt-shown', 'true');
        });
    } else {
        // If app is already installed or not installable
        Swal.fire({
            title: 'Info',
            text: 'Aplikasi mungkin sudah terinstall atau browser tidak mendukung installasi otomatis.',
            icon: 'info',
            confirmButtonColor: '#0ca678',
        });
    }
};

window.addEventListener('appinstalled', () => {
    console.log('PWA was installed');
    // Track install
    axios.post('/track-install').catch(e => console.error('Failed to track install:', e));
});

// Push Notification Logic
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

window.enableNotifications = async () => {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        Swal.fire('Error', 'Push messaging is not supported in this browser.', 'error');
        return;
    }

    try {
        const registration = await navigator.serviceWorker.ready;

        // Request permission
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            throw new Error('Notification permission denied');
        }

        // Subscribe
        const subscribeOptions = {
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(import.meta.env.VITE_VAPID_PUBLIC_KEY)
        };

        const pushSubscription = await registration.pushManager.subscribe(subscribeOptions);

        // Send to backend
        await axios.post('/push/subscribe', pushSubscription);

        Swal.fire({
            title: 'Berhasil',
            text: 'Notifikasi berhasil diaktifkan!',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });

    } catch (error) {
        console.error('Error enabling notifications:', error);
        Swal.fire('Error', 'Gagal mengaktifkan notifikasi: ' + error.message, 'error');
    }
};

window.subscribeUser = window.enableNotifications; // Alias for user request

if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}
