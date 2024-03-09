<?php

namespace App\Enum\Transaction;

enum Status: string
{
    case REJECTED = 'REJECTED';
    case COMPLETED = 'COMPLETED';
    case SCHEDULED = 'SCHEDULED';
}
