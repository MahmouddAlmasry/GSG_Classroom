<?php

namespace App\Models;

use App\Concerns\HasPrice;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory, HasPrice;

    protected $fillable = [
        'plan_id', 'user_id', 'price', 'expires_at', 'status'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // public function price(): Attribute
    // {
    //     return new Attribute(
    //         get: fn($price) => $price / 100,
    //         set: fn($price) => $price * 100,
    //     );
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
