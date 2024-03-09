<?php

namespace Tests\Feature\Account;

use App\Models\Account;
use Tests\TestCase;

class AccountCreateTest extends TestCase
{
    /** @test */
    public function checkIfRequireAccountName(): void
    {
        $account = ['balance' => Account::DEFAULT_BALANCE];

        $response = $this->postJson('api/account', $account);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'name' => [
                    'The name field is required.',
                ]
            ]);
    }

    /** @test */
    public function checkIfAccountCantBeCreatedWithNegativeValues(): void
    {
        $account = Account::factory()->make(['balance' => -1])->toArray();

        $response = $this->postJson('api/account', $account);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'balance' => [
                    'The balance field must be at least 0.',
                    'The balance field format is invalid.',
                ]
            ]);
    }

    /** @test */
    public function checkIfDefaultAccountAmountIsZeroWhenCreated(): void
    {
        $account = Account::factory()->make(['balance' => null])->toArray();

        $response = $this->postJson('api/account', $account);

        $response->assertCreated()
            ->assertJson([
                'name' => $account['name'],
                'balance' => Account::DEFAULT_BALANCE,
            ]);

        $this->assertDatabaseHas('accounts', [
            'name' => $account['name'],
            'balance' => Account::DEFAULT_BALANCE,
        ]);
    }

    /** @test */
    public function checkIfAccountWithCustomAmountIsCreated(): void
    {
        $account = Account::factory()->make()->toArray();

        $response = $this->postJson('api/account', $account);

        $response->assertCreated()
            ->assertJson([
                'name' => $account['name'],
                'balance' => $account['balance']
            ]);
    }
}
