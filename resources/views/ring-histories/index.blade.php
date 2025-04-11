<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <h2>リング履歴</h2>
        </div>
    </x-slot>

    {{-- 日付選択フォーム --}}
    <form method="GET" action="{{ route('ring-histories.index') }}" class="mb-4">
        <div class="form-group d-flex align-items-center gap-2">
            <label for="date" class="mb-0">日付:</label>
            <input type="date" name="date" id="date" value="{{ request('date', now()->toDateString()) }}" class="form-control" style="width: 200px;">
            <button type="submit" class="btn btn-primary">検索</button>
        </div>
    </form>

    {{-- 履歴テーブル --}}
    <table class="table table-bordered table-striped text-xs whitespace-normal w-full">
        <thead>
            <tr>
                <th class="whitespace-nowrap">会計番号</th>
                <th class="whitespace-nowrap">プレイヤー名</th>
                <th class="whitespace-nowrap">チップ</th>
                <th class="whitespace-nowrap">合計チップ</th>
                <th class="whitespace-nowrap">種別</th>
                <th class="whitespace-nowrap">処理</th>
                <th class="whitespace-nowrap">コメント</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $tx)
            @php
            $isZeroIn = $tx->chips == 0 && $tx->type === '0円システム' && $tx->action === 'in';
            @endphp

            @if (!$isZeroIn)
            <tr>
                <td class="whitespace-nowrap">{{ $tx->accounting_number ?? '―' }}</td>
                <td class="whitespace-nowrap">{{ $tx->player->player_name ?? '―' }}</td>
                <td class="whitespace-nowrap">{{ $tx->chips }}</td>
                <td class="whitespace-nowrap">{{ $tx->zeroSystemHeader->sum_initial_chips ?? '―' }}</td>
                <td class="whitespace-nowrap">{{ $tx->type }}</td>
                <td class="whitespace-nowrap">{{ $tx->action }}</td>
                <td class="whitespace-nowrap">{{ $tx->comment ?? '―' }}</td>
            </tr>
            @endif
            @empty
            <tr>
                <td colspan="7" class="text-center">データがありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</x-app-layout>