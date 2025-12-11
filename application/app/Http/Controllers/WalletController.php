<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\MessageBag;

class WalletController extends Controller
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->middleware('auth');
        $this->walletService = $walletService;
    }

    protected function getUserTransactions(User $user, int $limit = 50)
    {
        return Transaction::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->where(function ($query) {
                // show completed that have not been reversed OR show reversed transactions
                // $query->where('status', TransactionStatus::REVERSED)
                //     ->orWhere(function ($q) {

                //         $q->where('status', TransactionStatus::COMPLETED)
                //             ->whereNull('reversed_id');
                //         // only original not reversed
                //     });

                $query->whereNull('reversed_id');
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $transactions = $this->getUserTransactions($user);

        $listItems = User::where('id', '!=', $user->id)->get();

        return view('wallet.dashboard', compact('user', 'listItems', 'transactions'));
    }

    public function deposit(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);

        $transaction = $this->walletService->deposit($request->user(), (float)$request->amount, $request->notes ?? null);


        if ($request->header('HX-Request')) {

            $transactions = $this->getUserTransactions($request->user());

            return view('wallet.partials.transaction-list', compact('transactions'));
        }

        return redirect()->back()->with('success', 'Deposit completed');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id|not_in:' . $request->user()->id,
            'amount' => 'required|numeric|min:0.01',
        ]);

        $receiver = User::findOrFail($request->receiver_id);

        $errors = new MessageBag();

        try {
            $transaction = $this->walletService->transfer($request->user(), $receiver, (float)$request->amount, $request->notes ?? null);
        } catch (\Exception $e) {

            return redirect()->back()
                ->withErrors(['amount' => $e->getMessage()])
                ->withInput(); 
        }

        $user = $request->user();
        $listItems = User::where('id', '!=', $user->id)->get();

        $transactions = $this->getUserTransactions($request->user());

        return view('wallet.dashboard', compact('user', 'listItems', 'transactions'))
            ->with('success', 'Transfer completed');
    }

    // reverse transaction endpoint (admin or owner)
    public function reverse(Request $request, Transaction $transaction)
    {
        // simple authorization: only involved user or admin can reverse
        $user = $request->user();
        if ($transaction->sender_id !== $user->id && $transaction->receiver_id !== $user->id && !$user->is_admin) {
            abort(403);
        }

        try {

            $this->walletService
                ->reverseTransaction($transaction, $request->reason ?? null);
        } catch (\Exception $e) {
            return back()->withErrors(['reverse' => $e->getMessage()]);
        }

        return back()->with('success', 'Transaction reversed');
    }
}
