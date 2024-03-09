<?php

namespace Database\Factories;

use App\Enum\Transaction\Status;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_id' => Account::factory(),
            'receiver_id' => Account::factory(),
            'amount' => fake()->randomNumber(),
            'status' => fake()->randomElement([Status::cases()]),
        ];
    }
}
