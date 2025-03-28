<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Player 詳細') }}
        </h2>
    </x-slot>

    <div class="w-100 mx-0 bg-white p-6 rounded shadow">
        <!-- Player Name -->
        <div class="flex items-center justify-between border p-4 rounded mb-4">
            <span class="font-bold">Player Name:</span>
            <span>{{ $player->player_name }}</span>
            <a href="{{ route('players.edit', $player) }}" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-blue-600">
                編集
            </a>
        </div>

        <!-- Player ID -->
        <div class="flex items-center justify-between border p-4 rounded mb-4">
            <span class="font-bold">Player ID:</span>
            <span>{{ $player->player_my_id }}</span>
        </div>

        <!-- comment -->
        <div class="border p-4 rounded mb-4">
            <span class="font-bold">コメント:</span>
            <span></span>{{ $player->comment }}</span>
        </div>

        <!-- サブスク表示 -->
        @if ($player->is_subscribed)
        <div class="text-red-500 font-bold mb-4">サブスク利用中</div>
        @endif

        <!-- 0円システム or 引き出し利用中 -->
        <div class="border p-4 rounded mb-4">
            0円システム利用中 / 引き出し利用中 のどちらかを表示
        </div>

        <!-- QRコード -->
        <div class="mt-4 p-4 border rounded bg-gray-50">
            <strong>QRコード</strong>
            @if ($player->uid)
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $player->uid }}" alt="QR Code">
            @else
            <p class="text-red-500 font-bold">uidを更新してください</p>
            @endif
        </div>

        <!--  Bootstrap ナビゲーションタブ -->
        <ul class="nav nav-tabs mt-4" id="playerTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="ring-tab" data-bs-toggle="tab" data-bs-target="#ring" type="button" role="tab" aria-controls="ring" aria-selected="true">
                    リング
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tournament-tab" data-bs-toggle="tab" data-bs-target="#tournament" type="button" role="tab" aria-controls="tournament" aria-selected="false">
                    トナメ
                </button>
            </li>
        </ul>

        <!--  タブの内容 -->
        <div class="tab-content mt-3" id="playerTabsContent">

            <!--  リング -->
            <div class="tab-pane fade show active" id="ring" role="tabpanel" aria-labelledby="ring-tab">
                <p><strong>保有チップ:</strong> ○○点</p>

                <h5>cash-in</h5>
                <div class="mb-2">
                    <label>引き出し額</label>
                    <input type="text" class="form-control">
                    <button class="btn btn-primary mt-2">ボタン</button>
                </div>

                <div class="mb-2">
                    <label>0円システム</label>
                    <input type="text" class="form-control">
                    <button class="btn btn-primary mt-2">ボタン</button>
                </div>

                <h5>cash-out</h5>
                <div class="mb-2">
                    <label>アウト額</label>
                    <input type="text" class="form-control">
                    <button class="btn btn-primary mt-2">ボタン</button>
                </div>

                <div class="mb-2">
                    <label>コメント入力</label>
                    <textarea class="form-control"></textarea>
                </div>

                <a href="{{ route('players.history', ['player' => $player->id, 'tab' => 'ring']) }}" class="btn btn-secondary mt-2">
                    リング履歴を見る
                </a>
            </div>

            <!--  トナメ -->
            <div class="tab-pane fade" id="tournament" role="tabpanel" aria-labelledby="tournament-tab">
                <form method="POST" action="{{ route('players.tournament.store', $player) }}">
                    @csrf
                    <p><strong>保有トナメチップ:</strong> {{ number_format($tournamentChips) }} 点</p>
                    <div class="mb-2">
                        <label>チップ</label>
                        <!-- 表示用（ユーザーが見る/入力する） -->
                        <input type="text" id="chips_view" class="form-control" inputmode="numeric">

                        <!-- 実際に送信される値（hidden） -->
                        <input type="hidden" name="chips" id="chips_real">
                    </div>

                    <div class="mb-2">
                        <label>ポイント</label>
                        <input type="number" name="points" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>会計番号</label>
                        <input type="text" name="accounting_number" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>エントリー</label>
                        <input type="number" name="entry" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>コメント</label>
                        <textarea name="comment" class="form-control"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">登録</button>
                </form>

                <a href="{{ route('players.history', ['player' => $player->id, 'tab' => 'tournament']) }}" class="btn btn-outline-secondary mt-3">
                    トナメ履歴を見る
                </a>
            </div>
        </div>
</x-app-layout>