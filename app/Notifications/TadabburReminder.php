<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class TadabburReminder extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Pengingat Tadabbur 📝',
            'message' => 'Belum sempat mencatat tadabbur hari ini? Jangan lupa luangkan waktu sejenak untuk merenung.',
            'action_url' => route('daily-tadabbur.index'),
            'type' => 'tadabbur_reminder',
            'icon' => 'book-open'
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Pengingat Tadabbur 📝')
            ->icon('/icons/icon-512x512.png')
            ->body('Belum sempat mencatat tadabbur hari ini? Jangan lupa luangkan waktu sejenak untuk merenung.')
            ->action('Catat Sekarang', 'tadabbur_entry')
            ->data(['url' => route('daily-tadabbur.index')]);
    }
}
