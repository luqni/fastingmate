<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FidyahRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'region',
        'price_per_day',
    ];
}
