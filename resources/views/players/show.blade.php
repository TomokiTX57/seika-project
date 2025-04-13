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

        <!-- タブの内容 -->
        <div class="tab-content mt-3" id="playerTabsContent">
            <!-- リング -->
            <div class="tab-pane fade show active" id="ring" role="tabpanel" aria-labelledby="ring-tab">
                <p>
                    <strong>保有リングチップ: {{ number_format($ringChips) }} 点</strong><br>
                    <!-- 通常: <strong>{{ number_format($ringChips) }} 点</strong><br> -->
                    0円システム: {{ number_format($unsettledZeroChips) }} 点<br>
                    <!-- 合計: <strong>{{ number_format($player->total_ring_chips) }} 点</strong> -->
                </p>

                <!-- 0円システム or 引き出し利用中 -->
                <div class="border p-4 rounded mb-4 text-white {{ $player->hasUnsettledZeroSystem() ? 'bg-danger' : 'bg-success' }}">
                    {{ $chipStatus }}
                </div>

                <form method="POST" id="unified-form"
                    data-withdraw-url="{{ route('players.ring.withdraw', $player) }}"
                    data-zero-url="{{ route('players.zero-system.store', $player) }}">
                    @csrf

                    <!-- 0円システム処理後にリダイレクトするURL -->
                    <input type="hidden" name="redirect_to" value="{{ route('players.show', $player->id) }}">

                    <!-- 会計番号 -->
                    <div class="mb-2">
                        <label>会計番号</label>
                        <input type="text" name="accounting_number" class="form-control">
                    </div>

                    <!-- チップ金額 -->
                    <div class="mb-2">
                        <label>Cash in</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>

                    <!-- コメント（引き出し用のみ） -->
                    <div class="mb-2" id="withdraw-comment-area">
                        <label>コメント</label>
                        <textarea name="withdraw_comment" class="form-control"></textarea>
                    </div>

                    <!-- ボタン -->
                    <div class="d-flex gap-2 mt-2">
                        <button type="button" class="btn btn-primary"
                            onclick="submitUnifiedForm('withdraw')"
                            {{ $player->hasUnsettledZeroSystem() ? 'disabled' : '' }}>
                            引き出し
                        </button>

                        <button type="button" class="btn btn-warning"
                            onclick="submitUnifiedForm('zero')">
                            0円システム
                        </button>
                    </div>

                    @if ($player->hasUnsettledZeroSystem())
                    <div class="text-danger mt-1">
                        ※ 0円システム精算が完了するまで引き出しできません
                    </div>
                    @endif
                </form>

                <!-- リング：Cash-out -->
                <form method="POST" action="{{ route('players.ring.cashout', $player) }}">
                    @csrf
                    <div class="mb-2">
                        <label>Cash out</label>
                        <input type="number" name="cashout_amount" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>コメント</label>
                        <textarea name="cashout_comment" class="form-control"></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-success">Cash-out</button>

                        <a href="{{ route('players.ring.settle', $player) }}"
                            class="btn btn-secondary {{ !$shouldSettle ? 'disabled pointer-events-none opacity-50' : '' }}">
                            精算
                        </a>

                        @if (!$shouldSettle)
                        <div class="text-sm text-gray-500 mt-1">清算が不要、または既に完了しています。</div>
                        @endif
                    </div>
                </form>

                <a href="{{ route('players.history', ['player' => $player->id, 'tab' => 'ring']) }}" class="btn btn-outline-secondary mt-3">
                    リング履歴を見る
                </a>
            </div>

            <!-- トナメ -->
            <div class="tab-pane fade" id="tournament" role="tabpanel" aria-labelledby="tournament-tab">
                <form method="POST" action="{{ route('players.tournament.store', $player) }}">
                    @csrf
                    <p><strong>保有トナメチップ:</strong> {{ number_format($tournamentChips) }} 点</p>

                    <div class="mb-2">
                        <label>チップ</label>
                        <input type="text" id="chips_view" class="form-control" inputmode="numeric">
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

        <!-- QRコード -->
        <div class="mt-4 p-4 border rounded bg-gray-50">
            <strong>QRコード</strong>
            @if ($player->uid)
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $player->uid }}" alt="QR Code">
            @else
            <p class="text-red-500 font-bold">uidを更新してください</p>
            @endif
        </div>

        <!-- 削除ボタン -->
        <form method="POST" action="{{ route('players.destroy', $player->id) }}" class="text-center mt-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded"
                onclick="return confirm('本当にこのプレイヤーを削除しますか？')">
                プレイヤーを削除
            </button>
        </form>
        @vite('resources/js/unified-form.js')
</x-app-layout>