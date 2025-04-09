<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ $player->player_name }} さんの 0円システム 編集
        </h2>
    </x-slot>

    <div class="p-6 bg-white max-w-3xl mx-auto rounded shadow">
        <div class="mb-4">
            <strong>会計番号:</strong> {{ $latestAccountingNumber ?? '未設定' }}
        </div>

        <!-- Cash in フォーム -->
        <form method="POST" action="{{ route('players.zero-system.store', $player) }}">
            @csrf
            <div class="mb-3">
                <label for="zero_amount" class="form-label">Cash in (0円システム額)</label>
                <input type="number" name="zero_amount" id="zero_amount" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning">Cash in</button>
        </form>

        <!-- Cash out フォーム -->
        <form method="POST" action="{{ route('players.ring.cashout', $player) }}" class="mt-4">
            @csrf
            <div class="mb-3">
                <label for="cashout_amount" class="form-label">Cash out (精算額)</label>
                <input type="number" name="cashout_amount" id="cashout_amount" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="cashout_comment" class="form-label">コメント（任意）</label>
                <textarea name="cashout_comment" id="cashout_comment" class="form-control"></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Cash out</button>
                <a href="{{ route('players.ring.settle', $player) }}" class="btn btn-secondary">清算</a>
                <a href="{{ route('players.history', ['player' => $player->id, 'tab' => 'ring']) }}" class="btn btn-outline-secondary">履歴</a>
            </div>
        </form>
    </div>
</x-app-layout>