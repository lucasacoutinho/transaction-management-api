<?php

namespace Tests\Feature\Transaction;

use App\Enum\Transaction\Status;
use App\Exceptions\Transaction\TransactionRejectedException;
use App\Models\Account;
use App\Strategies\Transaction\AuthorizationStrategy;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class TransactionCreateTest extends TestCase
{
    /** @test */
    public function checkIfTransferIsntMadeWhenAmountIsInvalid(): void
    {
        $sender = Account::factory()->create();
        $receiver = Account::factory()->create();
        $transfer = [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => 0,
        ];

        $response = $this->postJson('api/transaction', $transfer);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'amount' => [
                    'The amount field must be at least 0.01.'
                ]
            ]);
    }

    /** @test */
    public function checkIfTransferCompletedWhenAuthorized(): void
    {
        $sender = Account::factory()->create();
        $receiver = Account::factory()->create();
        $transfer = [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => $sender->balance,
        ];

        $this->instance(
            AuthorizationStrategy::class,
            Mockery::mock(AuthorizationStrategy::class, function (MockInterface $mock) {
                $expected = [
                    'success' => true,
                    'authorized' => true
                ];

                $mock->shouldReceive('execute')->andReturn($expected);
            })
        );

        $response = $this->postJson('api/transaction', $transfer);

        $response->assertCreated()
            ->assertJson([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $sender->balance,
            ]);

        $this->assertDatabaseHas('transactions', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => $sender->getRawOriginal('balance'),
            'status' => Status::COMPLETED,
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $sender->id,
            'balance' => 0,
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $receiver->id,
            'balance' => $receiver->getRawOriginal('balance') + $sender->getRawOriginal('balance')
        ]);
    }

    /** @test */
    public function checkIfTransferRejectedWhenNotAuthorized(): void
    {
        $sender = Account::factory()->create();
        $receiver = Account::factory()->create();
        $transfer = [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => $sender->balance,
        ];

        $this->instance(
            AuthorizationStrategy::class,
            Mockery::mock(AuthorizationStrategy::class, function (MockInterface $mock) {
                $mock->shouldReceive('execute')->andThrow(TransactionRejectedException::class);
            })
        );

        $response = $this->postJson('api/transaction', $transfer);

        $response->assertCreated()
            ->assertJson([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $sender->balance,
            ]);

        $this->assertDatabaseHas('transactions', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => $sender->getRawOriginal('balance'),
            'status' => Status::REJECTED,
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $sender->id,
            'balance' => $sender->getRawOriginal('balance'),
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $receiver->id,
            'balance' => $receiver->getRawOriginal('balance')
        ]);
    }
}
