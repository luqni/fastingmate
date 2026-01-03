<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FastingRepayment extends Model
{
    protected $fillable = ['fasting_debt_id', 'paid_days', 'repayment_date', 'description'];

    protected $casts = [
        'repayment_date' => 'date',
    ];

    public function fastingDebt()
    {
        return $this->belongsTo(FastingDebt::class);
    }
}
