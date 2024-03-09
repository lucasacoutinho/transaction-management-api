<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    const DEFAULT_BALANCE = 0;

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'balance'
    ];

    protected $casts = [
        'balance' => 'integer'
    ];

    public function balance(): Attribute
    {
        return Attribute::make(
            get: fn ($balance) => $balance / 100,
        );
    }
}
