<?php

namespace Tests\Feature\Transaction;

use App\Enum\Transaction\Status;
use App\Jobs\ProcessTransactionsJob;
use App\Models\Transaction;
use App\Strategies\Transaction\AuthorizationStrategy;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ProcessPendingTransactionsCommandTest extends TestCase
{
    /** @test */
    public function checkIfTransferJobIsPushed(): void
    {
        Queue::fake();

        Transaction::factory([
            'status' => Status::SCHEDULED,
            'process_at' => now(),
        ])->create();

        $this->artisan('app:process-transactions');

        Queue::assertPushed(ProcessTransactionsJob::class);
    }

    /** @test */
    public function checkIfTransferIsProcessed(): void
    {
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

        $transaction = Transaction::factory([
            'status' => Status::SCHEDULED,
            'process_at' => now(),
        ])->create();

        $this->artisan('app:process-transactions');

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'sender_id' => $transaction->sender_id,
            'receiver_id' => $transaction->receiver_id,
            'amount' => $transaction->getRawOriginal('amount'),
            'status' => Status::COMPLETED,
        ]);
    }
}
