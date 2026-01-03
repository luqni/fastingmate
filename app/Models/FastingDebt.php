<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastingDebt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'total_days',
        'paid_days',
        'target_finish_date',
        'is_paid_off',
    ];

    protected $casts = [
        'target_finish_date' => 'date',
        'is_paid_off' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function smartSchedules()
    {
        return $this->hasMany(SmartSchedule::class);
    }

    public function repayments()
    {
        return $this->hasMany(FastingRepayment::class)->latest('repayment_date');
    }
}
