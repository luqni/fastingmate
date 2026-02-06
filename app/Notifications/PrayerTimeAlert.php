<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PrayerTimeAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public $prayerName;
    public $time;
    public $type; // 'prayer', 'sahur', 'iftar'

    /**
     * Create a new notification instance.
     */
    public function __construct($prayerName, $time, $type = 'prayer')
    {
        $this->prayerName = $prayerName;
        $this->time = $time;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class, 'database'];
    }

    /**
     * Get the web push representation of the notification.
     */
    public function toWebPush($notifiable, $notification)
    {
        $title = "Waktunya {$this->prayerName}";
        $body = "Saatnya menunaikan sholat {$this->prayerName} ({$this->time})";
        $icon = '/images/icons/prayer.png'; // Make sure these icons exist or use generic
        $tag = 'prayer-' . strtolower($this->prayerName);

        if ($this->type === 'sahur') {
            $title = "Waktunya Sahur!";
            $body = "Segera makan sahur sebelum Imsak pukul {$this->time}. Jangan lupa niat puasa ya!";
            $icon = '/images/icons/sahur.png';
            $tag = 'sahur';
        } elseif ($this->type === 'iftar') {
            $title = "Selamat Berbuka Puasa!";
            $body = "Alhamdulillah, telah masuk waktu Maghrib ({$this->time}). Selamat menikmati hidangan berbuka.";
            $icon = '/images/icons/iftar.png';
            $tag = 'iftar';
        }

        return (new WebPushMessage)
            ->title($title)
            ->body($body)
            ->icon($icon)
            ->tag($tag)
            ->data(['url' => url('/dashboard')])
            ->action('Buka App', 'open_app');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = "Saatnya menunaikan sholat {$this->prayerName} ({$this->time})";
        if ($this->type === 'sahur') {
            $message = "Waktunya sahur! Imsak pukul {$this->time}.";
        } elseif ($this->type === 'iftar') {
            $message = "Selamat berbuka puasa! Maghrib pukul {$this->time}.";
        }

        return [
            'title' => "Waktunya {$this->prayerName}",
            'message' => $message,
            'type' => $this->type,
            'time' => $this->time,
            'icon' => $this->type === 'prayer' ? 'clock' : ($this->type === 'sahur' ? 'moon' : 'sun'),
        ];
    }
}
