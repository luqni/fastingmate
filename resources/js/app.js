import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

import Swal from 'sweetalert2';
window.Swal = Swal;

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
window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    // Stash the event so it can be triggered later.
    const deferredPrompt = e;

    // Check if user has already declined recently (optional logic could go here)

    // Show the install prompt
    Swal.fire({
        title: 'Install FastingMate',
        text: 'Install aplikasi ini untuk pengalaman yang lebih baik dan akses offline!',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Install',
        cancelButtonText: 'Nanti Saja',
        confirmButtonColor: '#0ca678',
    }).then((result) => {
        if (result.isConfirmed) {
            // Show the install prompt
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('User accepted the install prompt');
                } else {
                    console.log('User dismissed the install prompt');
                }
            });
        }
    });
});

Alpine.start();
