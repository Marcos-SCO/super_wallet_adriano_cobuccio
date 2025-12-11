<div class="bg-white p-4 rounded shadow overflow-y-auto">
    <h3 class="font-semibold mb-3">Transactions</h3>

    @if ($transactions->isEmpty())
        <p class="text-gray-500">No transactions found.</p>
    @endif

    @if (!$transactions->isEmpty())
        <ul class="overflow-y-auto pr-2" style="max-height: 300px;">
            @foreach ($transactions as $tx)
                <x-transaction-item :tx="$tx" />
            @endforeach
        </ul>
    @endif

</div>
