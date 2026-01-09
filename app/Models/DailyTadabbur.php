<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyTadabbur extends Model
{
    protected $fillable = [
        'user_id',
        'quran_source_id',
        'date',
        'reflection',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quranSource(): BelongsTo
    {
        return $this->belongsTo(QuranSource::class);
    }
}
