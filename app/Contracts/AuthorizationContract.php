<?php

namespace App\Contracts;

interface AuthorizationContract
{
    public function execute(TransactionDtoContract $transactionDto): void;
}
