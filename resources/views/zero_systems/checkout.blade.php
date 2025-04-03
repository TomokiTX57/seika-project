<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">{{ $player->player_name }} さんの本日の会計情報</h2>
    </x-slot>

    <div class="p-6 bg-white shadow rounded">
        <p><strong>Total Cash In:</strong> {{ $totalCashIn }}</p>
        <p><strong>Total Cash Out:</strong> {{ $totalCashOut }}</p>

        @if ($chipDifference < 0)
            <p class="text-danger"><strong>支払いチップ:</strong> {{ abs($chipDifference) }}</p>
            @else
            <p class="text-success"><strong>支払い無し</strong></p>
            @endif
    </div>

    <div class="p-6 bg-white shadow rounded mt-4">
        <h3 class="text-lg font-semibold">本日の履歴</h3>
        <table class="table table-bordered w-full mt-2">
            <thead>
                <tr>
                    <th>日時</th>
                    <th>チップ</th>
                    <th>種別</th>
                    <th>処理</th>
                    <th>コメント</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ringTransactions as $tx)
                <tr>
                    <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>

                    <td>
                        {{-- 0円システム in の場合は detail の合計を表示 --}}
                        @if ($tx->type === '0円システム' && $tx->action === 'in' && $tx->zeroSystemHeader && $tx->zeroSystemHeader->details)
                        {{ $tx->zeroSystemHeader->details->sum('initial_chips') }}
                        @else
                        {{ $tx->chips }}
                        @endif
                    </td>

                    <td>{{ $tx->type ?? '―' }}</td>
                    <td>{{ $tx->action ?? '―' }}</td>
                    <td>{{ $tx->accounting_number ?? '―' }}</td>
                    <td>{{ $tx->comment ?? '―' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>