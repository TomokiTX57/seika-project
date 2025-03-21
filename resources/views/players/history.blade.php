<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ $player->player_name }} さんの履歴
        </h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow max-w-5xl mx-auto">
        <!-- Bootstrap タブ -->
        <ul class="nav nav-tabs mb-4" id="historyTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'ring' ? 'active' : '' }}"
                    href="{{ route('players.history', ['player' => $player->id, 'tab' => 'ring']) }}">
                    リング履歴
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'tournament' ? 'active' : '' }}"
                    href="{{ route('players.history', ['player' => $player->id, 'tab' => 'tournament']) }}">
                    トナメ履歴
                </a>
            </li>
        </ul>

        <!-- タブ内容 -->
        <div class="tab-content">
            @if ($tab === 'ring')
            <div class="tab-pane fade show active">
                <h5 class="mb-3">リング履歴</h5>
                @if($player->ringTransactions->isEmpty())
                <p>履歴がありません。</p>
                @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>日付</th>
                            <th>チップ</th>
                            <th>0円システム</th>
                            <th>会計番号</th>
                            <th>コメント</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($player->ringTransactions->sortByDesc('created_at') as $tx)
                        <tr>
                            <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $tx->chips }}</td>
                            <td>{{ $tx->is_zero_system ? '✅' : '✖' }}</td>
                            <td>{{ $tx->accounting_number }}</td>
                            <td>{{ $tx->comment }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            @elseif ($tab === 'tournament')
            <div class="tab-pane fade show active">
                <h5 class="mb-3">トーナメント履歴</h5>
                @if($player->tournamentTransactions->isEmpty())
                <p>履歴がありません。</p>
                @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>日付</th>
                            <th>チップ</th>
                            <th>ポイント</th>
                            <th>会計番号</th>
                            <th>コメント</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($player->tournamentTransactions->sortByDesc('created_at') as $tx)
                        <tr>
                            <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $tx->chips }}</td>
                            <td>{{ $tx->points }}</td>
                            <td>{{ $tx->accounting_number }}</td>
                            <td>{{ $tx->comment }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>