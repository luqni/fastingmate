<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\User;
use App\Notifications\BlogUpdateNotification;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        if ($post->is_published && !$post->is_locked) {
            $this->notifyUsers($post);
        }
    }

    public function updated(Post $post): void
    {
        $wasPublished = $post->getOriginal('is_published');
        $isPublished = $post->is_published;
        
        $wasLocked = $post->getOriginal('is_locked');
        $isLocked = $post->is_locked;

        // 1. Just Published (and not locked)
        if ($isPublished && !$wasPublished && !$isLocked) {
             $this->notifyUsers($post);
             return;
        }

        // 2. Just Unlocked (and already published)
        if ($isPublished && !$isLocked && $wasLocked) {
             $this->notifyUsers($post);
             return;
        }
    }

    protected function notifyUsers(Post $post)
    {
        // Notify all users who have push subscriptions
        // Ideally chunk this if user base is large
        try {
            $users = User::has('pushSubscriptions')->get();
            foreach ($users as $user) {
                $user->notify(new BlogUpdateNotification($post));
            }
            Log::info("Sent BlogUpdateNotification for post: {$post->title} to {$users->count()} users.");
        } catch (\Exception $e) {
            Log::error("Failed to send Blog notifications: " . $e->getMessage());
        }
    }
}
