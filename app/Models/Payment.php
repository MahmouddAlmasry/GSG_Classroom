<?php

namespace App\Models;

use App\Concerns\HasPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasPrice;

    protected $casts = [
        'data' => 'json',
    ];
}
