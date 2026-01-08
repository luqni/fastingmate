<?php

namespace App\Notifications;


use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ArticleUnlocked extends Notification
{
    use Queueable;

    public $post;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The article has been unlocked.')
                    ->action('Read Article', url('/posts/' . $this->post->slug))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Artikel Baru Terbuka! 🔓',
            'message' => 'Artikel "' . $this->post->title . '" sekarang bisa dibaca. Yuk cek!',
            'action_url' => route('posts.show', $this->post->slug),
            'type' => 'info',
            'icon' => 'document-text'
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Artikel Baru Terbuka! 🔓')
            ->icon('/images/icon-192x192.png')
            ->body('Artikel "' . $this->post->title . '" sekarang bisa dibaca. Yuk cek!')
            ->action('Baca Artikel', route('posts.show', $this->post->slug));
    }
}
