<?php

namespace App\Strategies\Transaction;

use App\Contracts\AuthorizationContract;
use App\Contracts\TransactionDtoContract;
use App\Exceptions\Transaction\TransactionRejectedException;
use App\Repositories\AuthorizerInterfaceRepository;
use Illuminate\Support\Arr;

class AuthorizationStrategy implements AuthorizationContract
{
    public function __construct(
        private AuthorizerInterfaceRepository $authorizerRepository,
    ) {
    }

    public function execute(TransactionDtoContract $transactionDto): void
    {
        $data = $this->authorizerRepository->authorize([
            'sender' => $transactionDto->getSender(),
            'receiver' => $transactionDto->getReceiver(),
            'amount' => $transactionDto->getAmount(),
        ]);

        $authorized = Arr::get($data, 'authorized');
        if (!$authorized) {
            throw new TransactionRejectedException();
        }
    }
}
