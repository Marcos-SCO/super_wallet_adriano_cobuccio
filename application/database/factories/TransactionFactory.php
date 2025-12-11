<?php

namespace Database\Factories;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\User;
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
        $type = $this->faker->randomElement([TransactionType::DEPOSIT, TransactionType::TRANSFER]);
        
        return [
            'sender_id' => $type === TransactionType::TRANSFER ? User::factory() : null,
            'receiver_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'type' => $type,
            'status' => $this->faker->randomElement([
                TransactionStatus::PENDING,
                TransactionStatus::COMPLETED,
                TransactionStatus::REVERSED,
            ]),
            'notes' => $this->faker->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }

    /**
     * Indicate that the transaction should be a deposit.
     */
    public function deposit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::DEPOSIT,
            'sender_id' => null,
            'status' => TransactionStatus::COMPLETED,
        ]);
    }

    /**
     * Indicate that the transaction should be a transfer.
     */
    public function transfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::TRANSFER,
            'sender_id' => User::factory(),
            'status' => TransactionStatus::COMPLETED,
        ]);
    }

    /**
     * Indicate that the transaction should be completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TransactionStatus::COMPLETED,
        ]);
    }

    /**
     * Indicate that the transaction should be pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TransactionStatus::PENDING,
        ]);
    }
}
