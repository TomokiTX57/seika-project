<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('新規プレイヤー作成') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white p-6 shadow-md rounded">
        <form action="{{ route('players.store') }}" method="POST">
            @csrf

            <!-- Player Name -->
            <div class="mb-4">
                <label class="block font-bold">Player Name:</label>
                <input type="text" name="player_name" class="w-full border rounded p-2" required>
            </div>

            <!-- Player ID -->
            <div class="mb-4">
                <label class="block font-bold">My ID:</label>
                <input type="text" name="player_my_id" class="w-full border rounded p-2">
            </div>

            <!-- サブスク -->
            <div class="mb-4">
                <label class="block font-bold">サブスク:</label>
                <select name="is_subscribed" class="w-full border rounded p-2">
                    <option value="0">未加入</option>
                    <option value="1">加入済み</option>
                </select>
            </div>

            <!-- uid -->
            <div class="mb-6">
                <label class="block font-bold">uid:</label>
                <input type="text" name="uid" class="w-full border rounded p-2">
            </div>

            <!-- 保存ボタン -->
            <div class="text-center">
                <button type="submit" class="px-6 py-2 bg-red-500 text-white font-bold rounded hover:bg-green-600">
                    保存
                </button>
            </div>
        </form>
    </div>
</x-app-layout>