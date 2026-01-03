<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenstrualCycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'converted_to_debt',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'converted_to_debt' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
