<?php

namespace App\Http\Controllers;

use App\Dtos\AccountDto;
use App\Http\Requests\AccountStoreRequest;
use App\Http\Resources\AccountResource;
use App\UseCases\Account\CreateAccountUseCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Account
 */
class AccountCreateController extends Controller
{
    public function __construct(
        private CreateAccountUseCase $createAccountUseCase,
    ) {
    }

    /**
     * Create a new account.
     *
     * @responseFile storage/responses/account.post.json
     */
    public function __invoke(AccountStoreRequest $request)
    {
        $accountDto = new AccountDto($request->input('name'));
        $accountDto->setBalance($request->input('balance'));

        $accountDto = $this->createAccountUseCase->execute($accountDto);

        return AccountResource::make($accountDto->getAccount())
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
