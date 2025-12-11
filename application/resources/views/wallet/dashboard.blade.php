@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="col-span-1 bg-white p-4 rounded shadow">

            <h2 class="text-lg font-semibold">Account</h2>

            <p class="mt-2">Hello, {{ $user->name }}</p>
            <p class="mt-2">Balance: <strong>${{ number_format($user->balance, 2) }}</strong></p>


            <form action="{{ route('wallet.deposit') }}" method="POST" class="mt-4" hx-post="{{ route('wallet.deposit') }}"
                hx-target="#transaction-list" hx-swap="innerHTML">

                @csrf
                <label class="block">Deposit amount
                    <input type="number" step="0.01" name="amount" required class="w-full border p-2 rounded mt-1" />
                </label>
                <label class="block mt-2">Notes
                    <input type="text" name="notes" class="w-full border p-2 rounded mt-1" />
                </label>

                <button class="bg-green-500 text-white px-4 py-2 rounded mt-3">Deposit</button>
            </form>
        </div>


        <div class="col-span-2 bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Transfer</h2>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4 mb-3">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block">Please fix the following errors:</span>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('wallet.transfer') }}" method="POST" {{-- hx-post="{{ route('wallet.transfer') }}" 
            hx-target="body" hx-swap="innerHTML" --}}>
                @csrf

                <label class="block">Receiver
                    <select name="receiver_id" required class="w-full border p-2 rounded mt-1">
                        <option value="">Select a user</option>
                        @foreach ($listItems as $u)
                            <option value="{{ $u->id }}" {{ old('receiver_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </label>
                @error('receiver_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <label class="block mt-2">Amount
                    <input type="number" step="0.01" name="amount" required value="{{ old('amount') }}"
                        class="w-full border p-2 rounded mt-1 @error('amount') border-red-500 @enderror" />
                </label>
                @error('amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                <label class="block mt-2">Notes
                    <input type="text" name="notes" value="{{ old('notes') }}"
                        class="w-full border p-2 rounded mt-1 @error('notes') border-red-500 @enderror" />
                </label>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <button class="bg-blue-500 text-white px-4 py-2 rounded mt-3">Transfer</button>
            </form>

            <div id="transaction-list" class="mt-6">
                @include('wallet.partials.transaction-list', ['transactions' => $transactions])
            </div>

        </div>
    </div>
@endsection
