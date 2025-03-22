<!-- resources/views/tournaments/index.blade.php -->

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
        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-2 py-1">会計番号</th>
                    <th class="border px-2 py-1">プレイヤーネーム</th>
                    <th class="border px-2 py-1">保有チップ</th>
                    <th class="border px-2 py-1">ランキング</th>
                    <th class="border px-2 py-1">付与チップ</th>
                    <th class="border px-2 py-1">ポイント</th>
                    <th class="border px-2 py-1">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $tx)
                <tr>
                    <td class="border px-2 py-1">{{ $tx->accounting_number }}</td>
                    <td class="border px-2 py-1">{{ $tx->player->player_name ?? '不明' }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($tx->player->tournament_chips ?? 0) }}</td>
                    <td class="border px-2 py-1 text-center">3</td> {{-- 仮ランキング --}}
                    <td class="border px-2 py-1 text-right">{{ $tx->chips }}</td>
                    <td class="border px-2 py-1 text-right">{{ $tx->points }}</td>
                    <td class="border px-2 py-1 text-center">
                        <a href="{{ route('tournaments.edit', $tx->id) }}" class="px-2 py-1 bg-blue-500 text-white rounded">編集</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>