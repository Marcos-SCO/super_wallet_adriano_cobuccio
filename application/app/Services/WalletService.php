<?php

namespace App\Services;

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

            // Round to 2 decimals
            $amount = round($amount, 2);

            $user->balance = round($user->balance + $amount, 2);
            $user->save();

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
            $amount = round($amount, 2);

            // reload for consistency check
            $sender->refresh();

            if (round($sender->balance, 2) < $amount) {
                throw new \Exception(__('messages.walletService_balance_exception'));
            }

            // This is how money from free software change hands
            $sender->balance = round($sender->balance - $amount, 2);
            $receiver->balance = round($receiver->balance + $amount, 2);

            $sender->save();
            $receiver->save();

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

            $transaction->status = TransactionStatus::REVERSED;
            $transaction->save();

            $amount = round($transaction->amount, 2);

            $sender = $transaction->sender()->lockForUpdate()->first();
            $receiver = $transaction->receiver()->lockForUpdate()->first();

            // reverse amounts
            if ($transaction->type === TransactionType::DEPOSIT) {

                // subtract the deposit from receiver
                $receiver->balance = round($receiver->balance - $amount, 2);
                $receiver->save();

                $reversal = Transaction::create([
                    'reversed_id' => $transaction->id,
                    'sender_id' => null,
                    'receiver_id' => $receiver->id,
                    'amount' => $transaction->amount,
                    'type' => TransactionType::TRANSFER,
                    'status' => TransactionStatus::REVERSED,
                    'notes' => 'reversal: ' . ($reason ?? ' deposit reversal'),
                ]);

                return $reversal;
            }


            if ($transaction->type === TransactionType::TRANSFER) {

                // move money back
                $receiver->balance = round($receiver->balance - $amount, 2);
                $sender->balance = round($sender->balance + $amount, 2);

                $sender->save();
                $receiver->save();

                $reversal = Transaction::create([
                    'reversed_id' => $transaction->id,
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                    'amount' => $transaction->amount,
                    'type' => TransactionType::TRANSFER,
                    'status' => TransactionStatus::REVERSED,
                    'notes' => 'reversal: ' . ($reason ?? ' transfer reversal'),
                ]);

                return $reversal;
            }
        });
    }
}
