<tr id="row-{{ $tx->id }}">
    <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>

    <!-- チップ -->
    <td>
        @if ($tx->type === '0円システム' && $tx->action === 'in' && $tx->zeroSystemHeader && $tx->zeroSystemHeader->details)
        <span class="chip-display" id="display-chips-{{ $tx->id }}">
            {{ $tx->zeroSystemHeader->details->sum('initial_chips') }}
        </span>
        <input type="number" class="form-control chip-edit d-none" id="edit-chips-{{ $tx->id }}" value="{{ $tx->zeroSystemHeader->details->sum('initial_chips') }}">
        @else
        <span class="chip-display" id="display-chips-{{ $tx->id }}">{{ $tx->chips }}</span>
        <input type="number" class="form-control chip-edit d-none" id="edit-chips-{{ $tx->id }}" value="{{ $tx->chips }}">
        @endif
    </td>

    <td>{{ $tx->type ?? '―' }}</td>
    <td>{{ $tx->action ?? '―' }}</td>
    <td>{{ $tx->accounting_number ?? '―' }}</td>

    <!-- コメント編集 -->
    <td>
        <span class="comment-display" id="display-comment-{{ $tx->id }}">{{ $tx->comment ?? '―' }}</span>
        <input type="text" class="form-control comment-edit d-none" id="edit-comment-{{ $tx->id }}" value="{{ $tx->comment }}">
    </td>

    <!-- ボタン -->
    <td>
        <button class="btn btn-sm btn-primary" onclick="enableEdit({{ $tx->id }})">編集</button>
        <button class="btn btn-sm btn-success d-none" id="save-btn-{{ $tx->id }}" onclick="saveEdit({{ $tx->id }})">修正</button>
        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $tx->id }})">削除</button>
    </td>
</tr>