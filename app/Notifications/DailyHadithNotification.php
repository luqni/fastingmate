<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class DailyHadithNotification extends Notification
{
    use Queueable;

    protected $hadith;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($hadith)
    {
        $this->hadith = $hadith;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Hadits Hari Ini',
            'body' => $this->hadith['text'],
            'source' => $this->hadith['source'],
            'type' => 'hadith'
        ];
    }

    /**
     * Get the web push representation of the notification.
     *
     * @param  mixed  $notifiable
     * @param  mixed  $notification
     * @return \Illuminate\Notifications\Messages\DatabaseMessage
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Hadits Hari Ini | ' . $this->hadith['source'])
            ->body($this->hadith['text'])
            ->icon('/icons/icon-512x512.png')
            ->badge('/icons/icon-96x96.png')
            ->data(['url' => url('/')]); // Open app when clicked
    }
}
