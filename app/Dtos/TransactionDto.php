<?php

namespace App\Dtos;

use App\Contracts\TransactionDtoContract;
use App\Models\Transaction;

class TransactionDto implements TransactionDtoContract
{
    protected int $amount;

    protected ?Transaction $transaction = null;

    public function __construct(
        protected int $senderId,
        protected int $receiverId,
        protected ?string $processAt = null,
    ) {
    }

    public function getSender(): int
    {
        return $this->senderId;
    }

    public function getReceiver(): int
    {
        return $this->receiverId;
    }

    public function getAmount(): float
    {
        return $this->amount / 100;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = (int) round(($amount * 100), 0);
        return $this;
    }

    public function getProcessAt(): ?string
    {
        return $this->processAt;
    }

    public function setTransaction(Transaction $transaction): self
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function toArray(): array
    {
        return [
            'sender_id' => $this->senderId,
            'receiver_id' => $this->receiverId,
            'amount' => $this->amount,
            'process_at' => $this->processAt,
        ];
    }
}
