<?php

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Funds into a user's wallet and create a transaction record.
     *
     * @param User $user
     * @param float $amount
     * @param string|null $notes
     * @return Transaction
     */
    public function deposit(User $user, float $amount, ?string $notes = null): Transaction
    {
        // Here's where Agiota receives payment and you don't turn into Saudade
        return DB::transaction(function () use ($user, $amount, $notes) {

            $user->increment('balance', $amount);

            $transaction = Transaction::create([
                'receiver_id' => $user->id,
                'amount' => $amount,
                'type' => TransactionType::DEPOSIT,
                'status' => TransactionStatus::COMPLETED,
                'notes' => $notes,
            ]);

            return $transaction;
        });
    }

    public function transfer(User $sender, User $receiver, float $amount, ?string $notes = null): Transaction
    {

        return DB::transaction(function () use ($sender, $receiver, $amount, $notes) {

            // reload for consistency check
            $sender->refresh();

            /* bcsub() (and all BC Math functions) 
            requires its operands to be passed as strings to ensure perfect precision. */
            if (bcsub((string)$sender->balance, (string)$amount, 2) < 0) {
                throw new \Exception('Insufficient balance');
            }

            // This is how money from free software change hands
            $sender->decrement('balance', $amount);
            $receiver->increment('balance', $amount);

            $transaction = Transaction::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
                'type' => TransactionType::TRANSFER,
                'status' => TransactionStatus::COMPLETED,
                'notes' => $notes,
            ]);

            return $transaction;
        });
    }

    /**
     * Reverse a transaction and adjust user balances.
     */
    public function reverseTransaction(Transaction $transaction, ?string $reason = null): Transaction
    {
        return DB::transaction(function () use ($transaction, $reason) {

            if ($transaction->status !== TransactionStatus::COMPLETED) {
                throw new \Exception('Only completed transactions can be reversed.');
            }

            $transaction->status = TransactionStatus::REVERSED;
            $transaction->save();

            // reverse amounts
            if ($transaction->type === TransactionType::DEPOSIT) {

                $receiver = $transaction->receiver()->lockForUpdate()->first();

                // subtract the deposit from receiver
                $receiver->decrement('balance', $transaction->amount);

                $reversal = Transaction::create([
                    'sender_id' => $receiver->id,
                    'receiver_id' => null,
                    'amount' => $transaction->amount,
                    'type' => TransactionType::TRANSFER,
                    'status' => TransactionStatus::COMPLETED,
                    'notes' => 'reversal: ' . ($reason ?? ' deposit reversal'),
                ]);

                return $reversal;
            }


            if ($transaction->type === TransactionType::TRANSFER) {

                $sender = $transaction->sender()->lockForUpdate()->first();
                $receiver = $transaction->receiver()->lockForUpdate()->first();

                // move money back
                $receiver->decrement('balance', $transaction->amount);
                $sender->increment('balance', $transaction->amount);

                $reversal = Transaction::create([
                    'sender_id' => $receiver->id,
                    'receiver_id' => $sender->id,
                    'amount' => $transaction->amount,
                    'type' => TransactionType::TRANSFER,
                    'status' => TransactionStatus::COMPLETED,
                    'notes' => 'reversal: ' . ($reason ?? ' transfer reversal'),
                ]);

                return $reversal;
            }
        });
    }
}
