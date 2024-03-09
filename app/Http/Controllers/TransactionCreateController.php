<?php

namespace App\Http\Controllers;

use App\Dtos\TransactionDto;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Resources\TransactionResource;
use App\UseCases\Transaction\CreateTransactionUseCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Transaction
 */
class TransactionCreateController extends Controller
{
    public function __construct(
        private CreateTransactionUseCase $createTransactionUseCase,
    ) {
    }

    /**
     * Create a new transaction.
     *
     * @responseFile storage/responses/transaction.post.json
     */
    public function __invoke(TransactionStoreRequest $request)
    {
        $transactionDto = new TransactionDto(
            senderId: $request->input('sender_id'),
            receiverId: $request->input('receiver_id'),
            processAt: $request->input('process_at')
        );

        $transactionDto->setAmount($request->input('amount'));

        $this->createTransactionUseCase->execute($transactionDto);

        return TransactionResource::make($transactionDto->getTransaction())
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
