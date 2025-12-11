@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="col-span-1 bg-white p-4 rounded shadow">

            <h2 class="text-lg font-semibold">{{ __('messages.account') }}</h2>

            <p class="mt-2">{{ __('messages.hello', ['name' => $user->name]) }}</p>
            <p class="mt-2">{{ __('messages.balance') }}: <strong>${{ number_format($user->balance, 2) }}</strong></p>


            <form action="{{ route('wallet.deposit') }}" method="POST" class="mt-4" hx-post="{{ route('wallet.deposit') }}"
                hx-target="body" hx-swap="outerHTML">

                @csrf

                <x-form-input label="{{ __('messages.deposit_amount') }}" name="amount" focusLabel="deposit_amount"
                    type="number" step="0.01" :value="old('amount')" errorBag="deposit" />

                <x-form-input label="{{ __('messages.notes') }}" name="notes" focusLabel="deposit_notes" type="text"
                    :value="old('notes')" errorBag="deposit" />

                <button data-loading-message="{{ __('messages.loading') }}"
                    data-regular-message="{{ __('messages.deposit') }}"
                    class="bg-green-500 text-white px-4 py-2 rounded mt-3 cursor-pointer">{{ __('messages.deposit') }}</button>
            </form>
        </div>


        <div class="col-span-2 bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">{{ __('messages.transfer') }}</h2>

            @if ($errors->transfer->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4 mb-3">
                    <strong class="font-bold">{{ __('messages.oops') }}</strong>
                    <span class="block">{{ __('messages.fix_errors') }}</span>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->transfer->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('wallet.transfer') }}" method="POST" hx-post="{{ route('wallet.transfer') }}"
                hx-target="body" hx-swap="outerHTML">
                @csrf

                <x-form-select label="{{ __('messages.receiver') }}" name="receiver_id" focusLabel="transfer_receiver_id"
                    :options="$listItems" :value="old('receiver_id')" placeholder="{{ __('messages.select_user') }}" required />

                <x-form-input label="{{ __('messages.amount') }}" name="amount" focusLabel="transfer_amount"
                    type="number" step="0.01" required :value="old('amount')" errorBag="transfer" />

                <x-form-input label="{{ __('messages.notes') }}" name="notes" focusLabel="transfer_notes" type="text"
                    :value="old('notes')" errorBag="transfer" />

                <button data-loading-message="{{ __('messages.loading') }}"
                    data-regular-message="{{ __('messages.transfer') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded mt-3 cursor-pointer">{{ __('messages.transfer') }}</button>
            </form>

            <div id="transaction-list" class="mt-6">
                @include('wallet.partials.transaction-list', ['transactions' => $transactions])
            </div>

        </div>
    </div>
@endsection
