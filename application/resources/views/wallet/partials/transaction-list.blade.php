<div class="bg-white p-4 rounded shadow overflow-y-auto">
    <h3 class="font-semibold mb-3">Transactions</h3>

    @if ($transactions->isEmpty())
        <p class="text-gray-500">No transactions found.</p>
    @endif

    @if (!$transactions->isEmpty())
        <ul class="overflow-y-auto pr-2" style="max-height: 300px;">
            @foreach ($transactions as $tx)
                @php
                    $itemClasses = 'flex justify-between p-3 border-b mb-3';
                    $badgeClasses = 'inline-block text-xs font-semibold px-2 py-0.5 rounded';

                    $itemClassesAdd = ' border-l-4 border-gray-200';
                    $badgeClassesAdd = ' bg-gray-100 text-gray-800';

                    if ($tx->status->value === \App\Enums\TransactionStatus::REVERSED->value) {
                        $itemClassesAdd = ' border-l-4 border-red-400 bg-red-50';
                        $badgeClassesAdd = ' bg-red-100 text-red-800';
                    }

                    if ($tx->type->value === \App\Enums\TransactionType::DEPOSIT->value) {
                        $itemClassesAdd = ' border-l-4 border-green-400 bg-green-50';
                        $badgeClassesAdd = ' bg-green-100 text-green-800';
                    }

                    if ($tx->type->value === \App\Enums\TransactionType::TRANSFER->value) {
                        $itemClassesAdd = ' border-l-4 border-blue-800 bg-blue-50';
                        $badgeClassesAdd = ' bg-blue-100 text-blue-800';
                    }

                    $itemClasses .= $itemClassesAdd;
                    $badgeClasses .= $badgeClassesAdd;
                @endphp

                <li class="{{ $itemClasses }}">
                    <div>
                        <div class="text-sm">{{ Str::ucfirst($tx->type->value) }} — ${{ number_format($tx->amount, 2) }}
                        </div>

                        @if ($tx->type !== \App\Enums\TransactionType::DEPOSIT)
                            <div class="text-xs text-gray-500">
                                <p>• From: {{ $tx->sender->name }} </p>
                                <p>• To: {{ $tx->receiver->name }}</p>
                            </div>
                        @endif

                        <div class="text-xs text-gray-500">• Message:
                            {{ $tx->notes ?? 'N/A' }}
                        </div>

                        <div class="text-xs text-gray-500">{{ $tx->created_at->diffForHumans() }} • Status:
                            {{ $tx->status }}
                        </div>

                    </div>

                    <div class="text-right">
                        @if ($tx->status->value === \App\Enums\TransactionStatus::COMPLETED->value)
                            <form method="POST" action="{{ route('wallet.reverse', $tx) }}">
                                @csrf
                                <button
                                    class="bg-red-500 text-white px-2 py-1 rounded text-sm cursor-pointer">Reverse</button>
                            </form>
                        @else
                            <div class="flex items-center justify-end space-x-2">
                                <span class="{{ $badgeClasses }}">{{ Str::ucfirst($tx->type->value) }}</span>
                                <span class="text-xs text-gray-500">{{ $tx->status }}</span>
                            </div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

</div>
