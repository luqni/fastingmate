<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'type',
        'status',
        'is_custom',
    ];

    protected $casts = [
        'date' => 'date',
        'is_custom' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
