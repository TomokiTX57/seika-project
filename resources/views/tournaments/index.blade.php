<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">トーナメント</h2>
            <button class="px-4 py-2 bg-gray-300 border rounded">ランキング</button>
        </div>
    </x-slot>

    <div class="p-6">
        <!-- 日付切り替え -->
        <form method="GET" class="flex space-x-2 mb-4">
            <input type="hidden" name="date" value="{{ \Carbon\Carbon::yesterday()->toDateString() }}">
            <button type="submit" class="px-4 py-2 bg-gray-200 border rounded">前日</button>
        </form>
        <form method="GET" class="flex space-x-2 mb-4">
            <input type="hidden" name="date" value="{{ \Carbon\Carbon::today()->toDateString() }}">
            <button type="submit" class="px-4 py-2 bg-gray-200 border rounded">本日</button>
        </form>
        <form method="GET" class="flex items-center space-x-2 mb-4">
            <input type="date" name="date" class="border rounded p-1" value="{{ $date }}">
            <button type="submit" class="px-4 py-2 bg-gray-200 border rounded">日付指定</button>
        </form>

        <!-- 一覧テーブル -->
        <div class="p-3 overflow-x-auto bg-white">
            <table class="table table-bordered table-striped text-xs w-full">
                <thead>
                    <tr class="border-b">
                        <th class="border px-2 py-1 whitespace-nowrap">会計番号</th>
                        <th class="border px-2 py-1 whitespace-nowrap">プレイヤーネーム</th>
                        <th class="border px-2 py-1 whitespace-nowrap">保有チップ</th>
                        <th class="border px-2 py-1 whitespace-nowrap">ランキング</th>
                        <th class="border px-2 py-1 whitespace-nowrap">付与チップ</th>
                        <th class="border px-2 py-1 whitespace-nowrap">ポイント</th>
                        <th class="border px-2 py-1 whitespace-nowrap">エントリー</th>
                        <th class="border px-2 py-1 whitespace-nowrap">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupedByPlayer as $playerId => $playerTransactions)
                    @php
                    $player = $playerTransactions->first()->player;
                    $chipSum = $player->tournamentTransactions->sum('chips');
                    $firstTx = $playerTransactions->first();
                    $entrySum = $playerTransactions->sum('entry');
                    @endphp
                    <tr>
                        <td class="border px-2 py-1 whitespace-nowrap">{{ $firstTx->accounting_number ?? '--' }}</td>
                        <td class="border px-2 py-1 whitespace-nowrap">{{ $player->player_name ?? '不明' }}</td>
                        <td class="border px-2 py-1 text-right whitespace-nowrap">{{ number_format($chipSum) }}</td>
                        <td class="border px-2 py-1 text-center whitespace-nowrap">--</td> {{-- ランキング仮 --}}
                        <td class="border px-2 py-1 text-right whitespace-nowrap">{{ $firstTx->chips }}</td>
                        <td class="border px-2 py-1 text-right whitespace-nowrap">{{ $firstTx->points }}</td>
                        <td class="border px-2 py-1 text-center whitespace-nowrap">{{ number_format($entrySum) }}</td>
                        <td class="border px-2 py-1 text-center whitespace-nowrap">
                            <a href="{{ route('tournaments.edit', $firstTx->id) }}" class="px-2 py-1 bg-blue-500 text-white rounded">編集</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>