<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('プレイヤー情報の編集') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white p-6 shadow-md rounded">
        <form action="{{ route('players.update', $player) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Player Name -->
            <div class="mb-4">
                <label class="block font-bold">Player Name:</label>
                <input type="text" name="player_name" class="w-full border rounded p-2" value="{{ old('player_name', $player->player_name) }}" required>
            </div>

            <!-- Player ID -->
            <div class="mb-4">
                <label class="block font-bold">Player ID:</label>
                <input type="text" name="player_my_id" class="w-full border rounded p-2" value="{{ old('player_my_id', $player->player_my_id) }}">
            </div>

            <!-- comment -->
            <div class="mb-4">
                <label class="block font-bold">コメント:</label>
                <textarea name="comment" class="w-full border rounded p-2">{{ old('comment', $player->comment) }}</textarea>
            </div>

            <!-- サブスク -->
            <div class="mb-4">
                <label class="block font-bold">サブスク:</label>
                <select name="is_subscribed" class="w-full border rounded p-2">
                    <option value="0" {{ !$player->is_subscribed ? 'selected' : '' }}>未加入</option>
                    <option value="1" {{ $player->is_subscribed ? 'selected' : '' }}>加入済み</option>
                </select>
            </div>

            <!-- uid -->
            <div class="mb-6">
                <label class="block font-bold">uid:</label>
                <input type="text" name="uid" class="w-full border rounded p-2" value="{{ old('uid', $player->uid) }}">
            </div>

            <!-- 保存ボタン -->
            <div class="text-center">
                <button type="submit" class="px-6 py-2 bg-red-500 text-white font-bold rounded hover:bg-green-600">
                    更新
                </button>
            </div>
        </form>
    </div>
</x-app-layout>