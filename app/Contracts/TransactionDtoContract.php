<?php

namespace App\Contracts;

use App\Models\Transaction;

interface TransactionDtoContract
{
    public function getSender(): int;
    public function getReceiver(): int;
    public function getAmount(): float;
    public function setAmount(float $amount): self;
    public function getProcessAt(): ?string;
    public function getTransaction(): ?Transaction;
    public function setTransaction(Transaction $transaction): self;
    public function toArray(): array;
}
