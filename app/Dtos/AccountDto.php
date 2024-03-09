<?php

namespace App\Dtos;

use App\Contracts\AccountDtoContract;
use App\Models\Account;

class AccountDto implements AccountDtoContract
{
    protected int $balance;

    protected Account $account;

    public function __construct(
        protected string $name,
    ) {
    }

    public function getBalance(): float
    {
        return $this->balance / 100;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = (int) round(($balance * 100), 0);
        return $this;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'balance' => $this->balance,
        ];
    }
}
