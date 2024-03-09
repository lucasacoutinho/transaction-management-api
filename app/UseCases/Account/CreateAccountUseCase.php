<?php

namespace App\UseCases\Account;

use App\Contracts\AccountDtoContract;
use App\Repositories\AccountRepositoryInterface;

class CreateAccountUseCase
{
    public function __construct(
        protected AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function execute(AccountDtoContract $accountDto): AccountDtoContract
    {
        $account = $this->accountRepository->create($accountDto->toArray());
        $accountDto->setAccount($account);

        return $accountDto;
    }
}
