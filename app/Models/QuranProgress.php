<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class QuranProgress extends Model
{
    protected $fillable = [
        'user_id',
        'surah_name',
        'ayah_number',
        'page'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
