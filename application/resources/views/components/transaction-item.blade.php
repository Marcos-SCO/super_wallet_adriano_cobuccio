@props(['tx'])

@php

    use App\Enums\TransactionStatus;
    use App\Enums\TransactionType;

    $itemClasses = 'flex items-start justify-between p-3 bg-white rounded-lg shadow-sm mb-3 transition hover:shadow-md';
    $badgeClasses = 'inline-block text-xs font-semibold px-2 py-0.5 rounded';

    $itemClassesAdd = ' border-l-4 border-gray-200';
    $badgeClassesAdd = ' bg-gray-100 text-gray-800';

    if ($tx->type->value === TransactionType::DEPOSIT->value) {
        $itemClassesAdd = ' border-l-4 border-green-400 bg-green-50';
        $badgeClassesAdd = ' bg-green-100 text-green-800';
    }

    if ($tx->type->value === TransactionType::TRANSFER->value) {
        $itemClassesAdd = ' border-l-4 border-blue-800 bg-blue-50';
        $badgeClassesAdd = ' bg-blue-100 text-blue-800';
    }

    if ($tx->status->value === TransactionStatus::REVERSED->value) {
        $itemClassesAdd = ' border-l-4 border-red-400 bg-red-50';
        $badgeClassesAdd = ' bg-red-100 text-red-800';
    }

    $itemClasses .= $itemClassesAdd;
    $badgeClasses .= $badgeClassesAdd;

    $translatedTransactionType = __('messages.transaction_type.' . strtolower($tx->type->value));
    $translatedTransactionStatus = __('messages.transaction_status.' . strtolower($tx->status->value));
@endphp

<li class="{{ $itemClasses }}">
    <div class="flex items-start space-x-3">
        <div
            class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-700">
            {{ Str::upper(Str::substr($translatedTransactionType, 0, 1)) }}
        </div>

        <div class="min-w-0">
            <div class="text-sm font-medium text-gray-800">
                {{ Str::ucfirst($translatedTransactionType) }} —
                ${{ number_format($tx->amount, 2) }}
            </div>

            @if ($tx->type !== TransactionType::DEPOSIT)
                <div class="text-xs text-gray-500 mt-1">
                    <p class="leading-tight">{{ __('messages.from') }}: {{ $tx->sender->name }}</p>
                    <p class="leading-tight">{{ __('messages.to') }}: {{ $tx->receiver->name }}</p>
                </div>
            @endif

            <div class="text-xs text-gray-500 mt-1">{{ __('messages.message') }}: {{ $tx->notes ?? 'N/A' }}</div>

            <div class="text-xs text-gray-400 mt-1">{{ $tx->created_at->diffForHumans() }} •
                {{ __('messages.status') }}: {{ $translatedTransactionStatus }}
            </div>
        </div>
    </div>

    <div class="flex flex-col items-end justify-between ml-4">
        @if ($tx->status->value === TransactionStatus::COMPLETED->value)
            <form method="POST" action="{{ route('wallet.reverse', $tx) }}">
                @csrf
                <button class="bg-red-500 text-white px-3 py-1 rounded text-sm">{{ __('messages.reverse') }}</button>
            </form>
        @else
            <div class="flex items-center space-x-2">
                <span class="{{ $badgeClasses }}">{{ Str::ucfirst($translatedTransactionType) }}</span>
                <span class="text-xs text-gray-500">{{ $translatedTransactionStatus }}</span>
            </div>
        @endif
    </div>
</li>
