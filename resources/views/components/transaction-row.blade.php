@if (
$tx->type === '0円システム' &&
$tx->action === 'in' &&
$tx->zeroSystemHeader &&
$tx->zeroSystemHeader->details->isNotEmpty()
)
@foreach ($tx->zeroSystemHeader->details->filter(fn($d) => intval($d->initial_chips) > 0)->sortByDesc('created_at') as $detail)
<td>{{ $detail->created_at->format('Y-m-d H:i') }}</td>
<td>
    <span class="chip-display">{{ $detail->initial_chips }}</span>
    <input type="number" class="form-control chip-edit d-none" value="{{ $detail->initial_chips }}">
</td>
<td>{{ $tx->zeroSystemHeader->sum_initial_chips ?? '―' }}</td>
<td>{{ $tx->type ?? '―' }}</td>
<td>{{ $tx->action ?? '―' }}</td>
<td>{{ $tx->accounting_number ?? '―' }}</td>
<td>
    <span class="comment-display" id="display-comment-{{ $tx->id }}">{{ $tx->comment ?? '―' }}</span>
    <input type="text" class="form-control comment-edit d-none" id="edit-comment-{{ $tx->id }}" value="{{ $tx->comment }}">
</td>
<td>
    <button class="btn btn-sm btn-primary" onclick="enableEdit({{ $tx->id }})">編集</button>
    <button class="btn btn-sm btn-success d-none" id="save-btn-{{ $tx->id }}" onclick="saveEdit({{ $tx->id }})">修正</button>
    <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $tx->id }})">削除</button>
</td>
</tr>
@endforeach
@elseif($tx->chips !== 0)
{{-- 通常のリング取引（chips ≠ 0 のみ） --}}
<tr id="row-{{ $tx->id }}">
    <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>
    <td>
        <span class="chip-display" id="display-chips-{{ $tx->id }}">{{ $tx->chips }}</span>
        <input type="number" class="form-control chip-edit d-none" id="edit-chips-{{ $tx->id }}" value="{{ $tx->chips }}">
    </td>
    <td>―</td>
    <td>{{ $tx->type ?? '―' }}</td>
    <td>{{ $tx->action ?? '―' }}</td>
    <td>{{ $tx->accounting_number ?? '―' }}</td>
    <td>
        <span class="comment-display" id="display-comment-{{ $tx->id }}">{{ $tx->comment ?? '―' }}</span>
        <input type="text" class="form-control comment-edit d-none" id="edit-comment-{{ $tx->id }}" value="{{ $tx->comment }}">
    </td>
    <td>
        <button class="btn btn-sm btn-primary" onclick="enableEdit({{ $tx->id }})">編集</button>
        <button class="btn btn-sm btn-success d-none" id="save-btn-{{ $tx->id }}" onclick="saveEdit({{ $tx->id }})">修正</button>
        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $tx->id }})">削除</button>
    </td>
</tr>
@endif