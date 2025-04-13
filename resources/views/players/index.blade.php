<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Player 検索') }}
        </h2>
    </x-slot>

    <div class="d-flex w-100">

        <!-- メインコンテンツ -->
        <div class="w-full p-1">


            <!-- 新規作成ボタン -->
            <div class="mb-4 text-center">
                <a href="{{ route('players.create') }}" class="px-4 py-2 bg-red-600 text-white rounded">新規作成</a>
            </div>

            <!-- 検索ボックス -->
            <div class="flex mb-4">
                <input type="text" id="search_name" placeholder="Player Name"
                    class="border p-2 w-1/2 mr-2" oninput="searchPlayers()">
                <input type="text" id="search_id" placeholder="MyID"
                    class="border p-2 w-1/2" oninput="searchPlayers()">
            </div>

            <!-- 検索結果 -->
            <div class="bg-white p-3 rounded overflow-x-auto">
                <table class="table table-bordered table-hover text-sm whitespace-normal bg-white w-full">
                    <thead class="thead-light">
                        <tr>
                            <th>Player Name</th>
                            <th>MyID</th>
                            <th>登録日</th>
                        </tr>
                    </thead>
                    <tbody id="player-list">
                        @foreach ($players as $player)
                        <tr class="cursor-pointer" data-url="{{ route('players.show', $player->id) }}">
                            <td>{{ $player->player_name }}</td>
                            <td>{{ $player->player_my_id }}</td>
                            <td>{{ $player->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach

                        <div class="m-3">
                            {{ $players->links('pagination::bootstrap-4') }}
                        </div>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 検索処理 -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</x-app-layout>