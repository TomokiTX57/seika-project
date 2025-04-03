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
                <tr id="row-{{ $tx->id }}">
                    <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>

                    <!-- 編集可能なチップ欄 -->
                    <td>
                        @if ($tx->type === '0円システム' && $tx->action === 'in' && $tx->zeroSystemHeader && $tx->zeroSystemHeader->details)
                        <span class="chip-display" id="display-chips-{{ $tx->id }}">
                            {{ $tx->zeroSystemHeader->details->sum('initial_chips') }}
                        </span>
                        <input type="number" class="form-control chip-edit d-none" id="edit-chips-{{ $tx->id }}" value="{{ $tx->zeroSystemHeader->details->sum('initial_chips') }}">
                        @else
                        <span class="chip-display" id="display-chips-{{ $tx->id }}">{{ $tx->chips }}</span>
                        <input type="number" class="form-control chip-edit d-none" id="edit-chips-{{ $tx->id }}" value="{{ $tx->chips }}">
                        @endif
                    </td>

                    <td>{{ $tx->type ?? '―' }}</td>
                    <td>{{ $tx->action ?? '―' }}</td>
                    <td>{{ $tx->accounting_number ?? '―' }}</td>

                    <!-- コメント編集 -->
                    <td>
                        <span class="comment-display" id="display-comment-{{ $tx->id }}">{{ $tx->comment ?? '―' }}</span>
                        <input type="text" class="form-control comment-edit d-none" id="edit-comment-{{ $tx->id }}" value="{{ $tx->comment }}">
                    </td>

                    <!-- 編集ボタンと削除ボタン -->
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="enableEdit({{ $tx->id }})">編集</button>
                        <button class="btn btn-sm btn-success d-none" id="save-btn-{{ $tx->id }}" onclick="saveEdit({{ $tx->id }})">修正</button>
                        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $tx->id }})">削除</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @vite(['resources/js/history-action.js'])
</x-app-layout>