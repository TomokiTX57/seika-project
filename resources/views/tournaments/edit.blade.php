<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            トーナメント編集
        </h2>
    </x-slot>

    <div class="py-6 px-6">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-2xl mx-auto">
            <form method="POST" action="{{ route('tournaments.update', $transaction->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">プレイヤー名</label>
                    <input type="text" class="form-control" value="{{ $transaction->player->player_name ?? '不明' }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">チップ</label>
                    <input type="number" name="chips" class="form-control" value="{{ old('chips', $transaction->chips) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">ポイント</label>
                    <input type="number" name="points" class="form-control" value="{{ old('points', $transaction->points) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">会計番号</label>
                    <input type="text" name="accounting_number" class="form-control" value="{{ old('accounting_number', $transaction->accounting_number) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">コメント</label>
                    <textarea name="comment" class="form-control">{{ old('comment', $transaction->comment) }}</textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">更新</button>
                    <a href="{{ route('tournaments.index') }}" class="btn btn-secondary">戻る</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>