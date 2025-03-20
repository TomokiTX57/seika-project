<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Player 検索') }}
        </h2>
    </x-slot>

    <div class="flex">

        <!-- メインコンテンツ -->
        <div class="w-4/5 p-6">


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
            <div class="border p-4 bg-white">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2 text-left">Player Name</th>
                            <th class="p-2 text-left">MyID</th>
                            <th class="p-2 text-left">登録日</th>
                        </tr>
                    </thead>
                    <tbody id="player-list">
                        @foreach ($players as $player)
                        <tr class="border-b cursor-pointer hover:bg-gray-100" data-url="{{ route('players.show', $player->id) }}">
                            <td class="p-2">{{ $player->player_name }}</td>
                            <td class="p-2">{{ $player->player_my_id }}</td>
                            <td class="p-2">{{ $player->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 検索処理 -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function addRowClickEvents() {
                document.querySelectorAll("#player-list tr").forEach(function(row) {
                    row.addEventListener("click", function() {
                        window.location = this.getAttribute("data-url");
                    });
                });
            }

            // 初回ロード時に適用
            addRowClickEvents();

            // 検索処理が完了するたびにクリックイベントを適用
            function searchPlayers() {
                let searchText = document.getElementById("search_name").value.toLowerCase();
                let rows = document.querySelectorAll("#player-list tr");

                rows.forEach(row => {
                    let playerName = row.cells[0].textContent.toLowerCase();
                    if (playerName.includes(searchText)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });

                // 検索後にクリックイベントを再適用
                addRowClickEvents();
            }

            // 検索ボックスのイベント
            document.getElementById("search_name").addEventListener("input", searchPlayers);
        });
    </script>
</x-app-layout>