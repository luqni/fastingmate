@props(['timings' => []])

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Function to check and prompt for notifications
        const checkNotificationPermission = () => {
            // Check if browser supports notifications
            if (!("Notification" in window)) {
                console.log("Browser does not support desktop notification");
                return;
            }

            // If already granted, do nothing
            if (Notification.permission === "granted") {
                return;
            }

            // Check if we already reminded user in this session (to avoid annoying spam on every page load)
            const hasReminded = sessionStorage.getItem('notification_reminder_shown');
            if (hasReminded) {
               return;
            }

            // Show Reminder Popup
            setTimeout(() => {
                Swal.fire({
                    title: 'Aktifkan Notifikasi? 🔔',
                    html: 'Dapatkan pengingat waktu <b>sholat</b>, <b>imsak</b>, dan <b>jadwal puasa</b> tepat waktu.<br><span class="text-sm text-gray-500 mt-2 block">Jangan lewatkan momen ibadah penting Anda.</span>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Aktifkan!',
                    cancelButtonText: 'Nanti Saja',
                    confirmButtonColor: '#059669', // Emerald 600
                    cancelButtonColor: '#9ca3af',
                    backdrop: `
                        rgba(0,0,123,0.1)
                        left top
                        no-repeat
                    `,
                    allowOutsideClick: false
                }).then((result) => {
                    // Mark as reminded for this session regardless of answer
                    sessionStorage.setItem('notification_reminder_shown', 'true');

                    if (result.isConfirmed) {
                        // Call the global function defined in app.js
                        if (typeof window.enableNotifications === 'function') {
                            window.enableNotifications();
                        } else {
                            // Fallback if function not ready
                            Notification.requestPermission().then(permission => {
                                if (permission === 'granted') {
                                    Swal.fire('Terima Kasih!', 'Notifikasi telah diaktifkan.', 'success');
                                }
                            });
                        }
                    }
                });
            }, 1500); // Small delay for better UX
        };

        // Run check
        checkNotificationPermission();
    });
</script>
