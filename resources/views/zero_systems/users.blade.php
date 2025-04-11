<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">0円システム利用者一覧（本日）</h2>
    </x-slot>

    <div class="p-3 overflow-x-auto bg-white shadow rounded">
        <table class="table table-bordered table-striped text-xs w-full">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">会計番号</th>
                    <th class="whitespace-nowrap">プレイヤー名</th>
                    <th class="whitespace-nowrap">Cash In</th>
                    <th class="whitespace-nowrap">Cash Out</th>
                    <th class="whitespace-nowrap">操作</th>
                    <th class="whitespace-nowrap">会計</th>
                </tr>
            </thead>
            <tbody>
                @foreach($headers as $header)
                <tr>
                    <td class="whitespace-nowrap">{{ optional($header->ringTransaction)->accounting_number ?? '―' }}</td>
                    <td class="whitespace-nowrap">{{ $header->player->player_name }}</td>
                    <td class="whitespace-nowrap">{{ $header->details->sum('initial_chips') }}</td>
                    <td class="whitespace-nowrap">{{ $header->final_chips ?? '-' }}</td>
                    <td class="whitespace-nowrap">
                        <a href="{{ route('zero-system.edit', $header->player->id) }}" class="btn btn-primary btn-sm">編集</a>
                    </td>
                    <td class="whitespace-nowrap">
                        <a href="{{ route('zero-system.checkout', $header->player->id) }}" class="btn btn-secondary btn-sm">会計</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>