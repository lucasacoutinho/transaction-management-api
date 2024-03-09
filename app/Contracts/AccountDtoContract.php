<?php

namespace App\Contracts;

use App\Models\Account;

interface AccountDtoContract
{
    public function setBalance(float $balance): self;
    public function getBalance(): float;
    public function getAccount(): Account;
    public function setAccount(Account $account): self;
    public function toArray(): array;
}
