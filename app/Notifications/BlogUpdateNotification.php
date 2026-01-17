<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class BlogUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Artikel Baru: ' . $this->post->title,
            'body' => 'Baca artikel terbaru kami: ' . $this->post->title,
            'action_url' => route('posts.show', $this->post->slug),
            'icon' => 'document-text', // Function for FE icon mapping
            'type' => 'blog_post'
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Artikel Baru ✨')
            ->body('Baca sekarang: ' . $this->post->title)
            ->icon('/icons/icon-512x512.png')
            ->badge('/icons/icon-96x96.png')
            ->data(['url' => route('posts.show', $this->post->slug)]);
    }
}
