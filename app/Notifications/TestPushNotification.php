<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestPushNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [\NotificationChannels\WebPush\WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new \NotificationChannels\WebPush\WebPushMessage)
            ->title('Halo dari FastingMate! 👋')
            ->body('Ini adalah notifikasi percobaan. Sistem notifikasi Anda berfungsi dengan baik!')
            ->action('Buka Aplikasi', 'app_open')
            ->icon('/pwa-192x192.png');
    }
}
