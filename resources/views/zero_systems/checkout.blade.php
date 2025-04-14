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

    <div class="p-3 overflow-x-auto bg-white shadow rounded mt-4">
        <h3 class="text-lg font-semibold">本日の履歴</h3>
        <table class="table table-bordered table-striped text-xs w-full mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">日時</th>
                    <th class="whitespace-nowrap">チップ</th>
                    <th class="whitespace-nowrap">合計チップ</th>
                    <th class="whitespace-nowrap">種別</th>
                    <th class="whitespace-nowrap">処理</th>
                    <th class="whitespace-nowrap">会計番号</th>
                    <th class="whitespace-nowrap">コメント</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ringTransactions as $tx)
                @include('components.transaction-row', ['tx' => $tx])
                @endforeach
            </tbody>
        </table>
    </div>
    @vite(['resources/js/history-action.js'])
</x-app-layout>