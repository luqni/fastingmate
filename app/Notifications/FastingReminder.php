<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class FastingReminder extends Notification
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Pengingat Puasa',
            'message' => $this->message,
            'action_url' => route('fasting-plans.index'),
            'type' => 'reminder',
            'icon' => 'calendar'
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Pengingat Puasa')
            ->icon('/pwa-192x192.png')
            ->body($this->message)
            ->action('Lihat Kalender', 'view_calendar')
            ->data(['url' => route('fasting-plans.index')]);
    }
}
