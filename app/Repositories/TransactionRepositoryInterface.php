<?php

namespace App\Repositories;

use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Support\Collection;

interface TransactionRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get today pending transactions
     */
    public function getTodayScheduledTransactions(): Collection;
}
