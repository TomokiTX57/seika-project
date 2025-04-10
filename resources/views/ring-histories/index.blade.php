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
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>会計番号</th>
                <th>プレイヤー名</th>
                <th>チップ</th>
                <th>合計チップ</th>
                <th>種別</th>
                <th>処理</th>
                <th>コメント</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $tx)
            @php
            $isZeroIn = $tx->chips == 0 && $tx->type === '0円システム' && $tx->action === 'in';
            @endphp

            @if (!$isZeroIn)
            <tr>
                <td>{{ $tx->accounting_number ?? '―' }}</td>
                <td>{{ $tx->player->player_name ?? '―' }}</td>
                <td>{{ $tx->chips }}</td>
                <td>{{ $tx->zeroSystemHeader->sum_initial_chips ?? '―' }}</td>
                <td>{{ $tx->type }}</td>
                <td>{{ $tx->action }}</td>
                <td>{{ $tx->comment ?? '―' }}</td>
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