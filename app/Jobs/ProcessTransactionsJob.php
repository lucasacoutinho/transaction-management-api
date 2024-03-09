<?php

namespace App\Jobs;

use App\Contracts\TransactionDtoContract;
use App\UseCases\Transaction\CreateTransactionUseCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected TransactionDtoContract $transactionDto
    ) {
    }

    public function handle(CreateTransactionUseCase $createTransactionUseCase): void
    {
        try {
            $this->transactionDto = $createTransactionUseCase->execute($this->transactionDto);
        } catch (Throwable) {
            $this->release();
        }
    }
}
