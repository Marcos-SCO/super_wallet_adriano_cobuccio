@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="col-span-1 bg-white p-4 rounded shadow">

            <h2 class="text-lg font-semibold">Account</h2>

            <p class="mt-2">Hello, {{ $user->name }}</p>
            <p class="mt-2">Balance: <strong>${{ number_format($user->balance, 2) }}</strong></p>


            <form action="{{ route('wallet.deposit') }}" method="POST" class="mt-4" {{-- hx-post="{{ route('wallet.deposit') }}"
                hx-target="#transaction-list" hx-swap="innerHTML" --}}>

                @csrf

                <x-form-input label="Deposit amount" name="amount" type="number" step="0.01" :value="old('amount')" errorBag="deposit" />

                <x-form-input label="Notes" name="notes" type="text" :value="old('notes')" errorBag="deposit" />

                <button class="bg-green-500 text-white px-4 py-2 rounded mt-3">Deposit</button>
            </form>
        </div>


        <div class="col-span-2 bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Transfer</h2>

            @if ($errors->transfer->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4 mb-3">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block">Please fix the following errors:</span>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->transfer->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('wallet.transfer') }}" method="POST" {{-- hx-post="{{ route('wallet.transfer') }}" 
            hx-target="body" hx-swap="innerHTML" --}}>
                @csrf

                <div>
                    <x-form-select label="Receiver" name="receiver_id" :options="$listItems" :value="old('receiver_id')" placeholder="Select a user" required />
                </div>

                <div>
                    <x-form-input label="Amount" name="amount" type="number" step="0.01" required :value="old('amount')" errorBag="transfer" />

                    <x-form-input label="Notes" name="notes" type="text" :value="old('notes')" errorBag="transfer" />
                </div>

                <button class="bg-blue-500 text-white px-4 py-2 rounded mt-3">Transfer</button>
            </form>

            <div id="transaction-list" class="mt-6">
                @include('wallet.partials.transaction-list', ['transactions' => $transactions])
            </div>

        </div>
    </div>
@endsection
