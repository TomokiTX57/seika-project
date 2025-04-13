<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ $player->player_name }} さんの履歴
        </h2>
    </x-slot>

    <div class="p-3 overflow-x-auto bg-white shadow rounded">
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
                <table class="table table-bordered table-striped text-xs w-full">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">日付</th>
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
                @endif
            </div>
            @elseif ($tab === 'tournament')
            <div class="tab-pane fade show active">
                <h5 class="mb-3">トーナメント履歴</h5>
                @if($player->tournamentTransactions->isEmpty())
                <p>履歴がありません。</p>
                @else
                <table class="table table-bordered table-striped text-xs w-full">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">日付</th>
                            <th class="whitespace-nowrap">チップ</th>
                            <th class="whitespace-nowrap">ポイント</th>
                            <th class="whitespace-nowrap">エントリー</th>
                            <th class="whitespace-nowrap">会計番号</th>
                            <th class="whitespace-nowrap">コメント</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($player->tournamentTransactions->sortByDesc('created_at') as $tx)
                        <tr>
                            <td class="whitespace-nowrap">{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                            <td class="whitespace-nowrap">{{ $tx->chips }}</td>
                            <td class="whitespace-nowrap">{{ $tx->points }}</td>
                            <td class="whitespace-nowrap">{{ $tx->entry  }}</td>
                            <td class="whitespace-nowrap">{{ $tx->accounting_number }}</td>
                            <td class="whitespace-nowrap">{{ $tx->comment }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            @endif
        </div>
    </div>

    @vite(['resources/js/history-action.js'])
</x-app-layout>