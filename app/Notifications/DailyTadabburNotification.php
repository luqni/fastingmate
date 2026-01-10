<?php

namespace App\Notifications;

use App\Models\QuranSource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class DailyTadabburNotification extends Notification
{
    use Queueable;

    public $quranSource;

    public function __construct(QuranSource $quranSource)
    {
        $this->quranSource = $quranSource;
    }

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Ayat Tadabbur Baru ✨',
            'message' => 'Renungkan ayat hari ini: ' . $this->quranSource->surah_name . ' ayat ' . $this->quranSource->ayah_number,
            'action_url' => route('dashboard'), // Or specific tadabbur link if available
            'type' => 'tadabbur',
            'icon' => 'book-open'
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Ayat Tadabbur Baru ✨')
            ->icon('/pwa-192x192.png')
            ->body('Renungkan ayat hari ini: ' . $this->quranSource->surah_name . ' ayat ' . $this->quranSource->ayah_number)
            ->action('Lihat Sekarang', 'view_tadabbur')
            ->data(['url' => route('dashboard')]);
    }
}
