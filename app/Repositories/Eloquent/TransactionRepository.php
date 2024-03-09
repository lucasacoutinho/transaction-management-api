<?php

namespace App\Repositories\Eloquent;

use App\Enum\Transaction\Status;
use App\Models\Transaction;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Collection;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    protected $model;

    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    /**
     * Get today pending transactions
     */
    public function getTodayScheduledTransactions(): Collection
    {
        return $this->model->whereDate('process_at', now())->where('status', Status::SCHEDULED)->get();
    }
}
