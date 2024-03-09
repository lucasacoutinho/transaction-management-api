<?php

namespace App\Console\Commands;

use App\Dtos\TransactionDto;
use App\Jobs\ProcessTransactionsJob;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Console\Command;

class ProcessPendingTransactionsCommand extends Command
{
    protected $signature = 'app:process-transactions';

    protected $description = 'Process scheduled transactions';

    public function handle(TransactionRepositoryInterface $transactionRepository)
    {
        $transactions = $transactionRepository->getTodayScheduledTransactions();

        foreach ($transactions as $transaction) {
            $transactionDto = new TransactionDto(
                senderId: $transaction->sender_id,
                receiverId: $transaction->receiver_id,
            );
            $transactionDto->setAmount($transaction->amount);
            $transactionDto->setTransaction($transaction);

            ProcessTransactionsJob::dispatch($transactionDto);
        }
    }
}
