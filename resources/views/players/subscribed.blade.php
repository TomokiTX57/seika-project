<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">サブスク利用者一覧</h2>
    </x-slot>

    <div class="p-1">
        @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('players.upload_subscription_csv') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <label class="block mb-2">CSVファイルをアップロード:</label>
            <input type="file" name="csv_file" accept=".csv" required class="border p-2">
            <button type="submit" class="ml-2 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                アップロード
            </button>
        </form>

        @if (session('csv_uploaded'))
        <div class="mb-4 text-green-600">CSVアップロードが完了しました。</div>
        <form action="{{ route('players.update_subscription_status') }}" method="POST">
            @csrf
            <button type="submit" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                サブスク情報を更新
            </button>
        </form>
        @endif

        <div class="bg-white shadow rounded p-4">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="text-left px-4 py-2">プレイヤーネーム</th>
                        <th class="text-left px-4 py-2">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($players as $player)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $player->player_name }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('players.edit', $player) }}"
                                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                編集
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-4 py-2 text-gray-500">登録されているサブスクプレイヤーはいません。</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>