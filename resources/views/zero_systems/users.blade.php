<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">0円システム利用者一覧（本日）</h2>
    </x-slot>

    <div class="p-6 bg-white shadow rounded">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>会計番号</th>
                    <th>プレイヤー名</th>
                    <th>Cash In</th>
                    <th>Cash Out</th>
                    <th>操作</th>
                    <th>会計</th>
                </tr>
            </thead>
            <tbody>
                @foreach($headers as $header)
                <tr>
                    <td>{{ optional($header->ringTransaction)->accounting_number ?? '―' }}</td>
                    <td>{{ $header->player->player_name }}</td>
                    <td>{{ $header->details->sum('initial_chips') }}</td>
                    <td>{{ $header->final_chips ?? '-' }}</td>
                    <td>
                        <a href="{{ route('zero-system.edit', $header->player->id) }}" class="btn btn-primary btn-sm">編集</a>
                    </td>
                    <td>
                        <a href="{{ route('zero-system.checkout', $header->player->id) }}" class="btn btn-secondary btn-sm">会計</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>