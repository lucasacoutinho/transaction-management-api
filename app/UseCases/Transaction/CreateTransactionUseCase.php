<?php

namespace App\UseCases\Transaction;

use App\Contracts\transactionDtoContract;
use App\Enum\Transaction\Status;
use App\Exceptions\Transaction\TransactionRejectedException;
use App\Factories\Transaction\AuthorizationFactory;
use App\Repositories\AccountRepositoryInterface;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Cache\Lock;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CreateTransactionUseCase
{
    const LOCK_SENDER = 'acc:sender:%s';
    const LOCK_RECEIVER = 'acc:receiver:%s';

    public function __construct(
        protected AccountRepositoryInterface $accountRepository,
        protected TransactionRepositoryInterface $transactionRepository,
    ) {
    }

    protected function acquireLock(string $lock): Lock
    {
        $lock = Cache::lock($lock, 10);

        if (!$lock->block(5)) {
            throw new \Exception("Unable to acquire lock for account operation.");
        }

        return $lock;
    }


    public function execute(TransactionDtoContract $transactionDto): TransactionDtoContract
    {
        return DB::transaction(function () use ($transactionDto) {
            $lockSender = $this->acquireLock(
                lock: sprintf(self::LOCK_SENDER, $transactionDto->getSender())
            );
            $lockReceiver = $this->acquireLock(
                lock: sprintf(self::LOCK_RECEIVER, $transactionDto->getSender())
            );

            $data = $transactionDto->toArray();

            if ($transactionDto->getProcessAt()) {
                Arr::set($data, 'status', Status::SCHEDULED);
                $transaction = $this->transactionRepository->create($data);

                return $transactionDto->setTransaction($transaction);
            }

            try {
                $strategy = AuthorizationFactory::factory('default');
                $strategy->execute($transactionDto);
                Arr::set($data, 'status', Status::COMPLETED);
            } catch (TransactionRejectedException) {
                Arr::set($data, 'status', Status::REJECTED);
            }

            $amount = Arr::get($data, 'amount');

            $senderAccount = $this->accountRepository->findById($transactionDto->getSender());
            $senderCurrentBalance = $senderAccount->getRawOriginal('balance');

            $senderHasBalance = $senderCurrentBalance >= $amount;
            if (!$senderHasBalance) {
                Arr::set($data, 'status', Status::REJECTED);
            }

            if ($transaction = $transactionDto->getTransaction()) {
                $this->transactionRepository->update($transaction->id, [
                    'status' => Arr::get($data, 'status'),
                ]);

                $transaction = $this->transactionRepository->findById($transaction->id);
            } else {
                $transaction = $this->transactionRepository->create($data);
            }

            if (in_array($transaction->status, [Status::COMPLETED]) && $senderHasBalance) {
                $this->accountRepository->update(
                    modelId: $transactionDto->getSender(),
                    payload: ['balance' => $senderCurrentBalance - $amount]
                );

                $receiverAccount = $this->accountRepository->findById($transactionDto->getReceiver());
                $receiverCurrentBalance = $receiverAccount->getRawOriginal('balance');

                $this->accountRepository->update(
                    modelId: $transactionDto->getReceiver(),
                    payload: ['balance' => $receiverCurrentBalance + $amount]
                );
            }

            // Release locks
            $lockSender->release();
            $lockReceiver->release();

            return $transactionDto->setTransaction($transaction);
        });
    }
}
