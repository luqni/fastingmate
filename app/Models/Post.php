<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'is_published',
        'is_locked',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_locked' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the estimated reading time for the article.
     * Based on average reading speed of 200 words per minute.
     *
     * @return string
     */
    public function getReadingTimeAttribute(): string
    {
        // Strip HTML tags and decode entities
        $text = strip_tags($this->content);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        
        // Count words (split by whitespace)
        $wordCount = str_word_count($text);
        
        // Calculate reading time (200 words per minute is average)
        $minutes = floor($wordCount / 200);
        $seconds = floor(($wordCount % 200) / (200 / 60));
        
        // Format output
        if ($minutes > 0) {
            return $minutes . ' menit baca';
        } else if ($seconds > 0) {
            return $seconds . ' detik baca';
        } else {
            return '< 1 detik baca';
        }
    }
}
