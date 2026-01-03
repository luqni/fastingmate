<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fasting_debt_id',
        'scheduled_date',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fastingDebt()
    {
        return $this->belongsTo(FastingDebt::class);
    }
}
