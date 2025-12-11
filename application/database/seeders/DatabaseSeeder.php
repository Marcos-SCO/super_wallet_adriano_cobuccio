<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 1 admin user if it doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'balance' => 50000.00,
                'is_admin' => true,
            ]
        );
        
        // Create 20 additional regular users
        $users = User::factory(20)->create();

        // Create transactions
        // Deposits for all users
        foreach ($users as $user) {
            Transaction::factory()
                ->deposit()
                ->count(fake()->numberBetween(1, 3))
                ->create([
                    'receiver_id' => $user->id,
                ]);
        }

        // Transfers between users
        Transaction::factory()
            ->transfer()
            ->count(15)
            ->create();

        // Additional mixed transactions
        Transaction::factory()
            ->count(10)
            ->create();
    }
}
