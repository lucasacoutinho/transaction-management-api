<?php

namespace App\Models;

use App\Enum\Transaction\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'amount',
        'status',
        'process_at',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($amount) => $amount / 100,
        );
    }
}
