@if (
$tx->type === '0円システム' &&
$tx->action === 'in' &&
$tx->zeroSystemHeader &&
$tx->zeroSystemHeader->details->isNotEmpty()
)
@foreach ($tx->zeroSystemHeader->details->filter(fn($d) => $d->initial_chips > 0)->sortByDesc('created_at') as $detail)
<tr id="row-detail-{{ $detail->id }}">
    <td>{{ $detail->created_at->format('Y-m-d H:i') }}</td>
    <td>
        <span class="chip-display" id="display-chips-detail-{{ $detail->id }}">
            {{ $detail->initial_chips }}
        </span>
        <input type="number" class="form-control chip-edit d-none" id="edit-chips-detail-{{ $detail->id }}" value="{{ $detail->initial_chips }}">
    </td>
    <td>{{ $tx->zeroSystemHeader->sum_initial_chips ?? '―' }}</td>
    <td>{{ $tx->type ?? '―' }}</td>
    <td>{{ $tx->action ?? '―' }}</td>
    <td>
        <span class="accounting-number-display" id="display-accounting-number-detail-{{ $detail->id }}">{{ $tx->accounting_number ?? '―' }}</span>
        <input type="text" class="form-control accounting-number-edit d-none" id="edit-accounting-number-detail-{{ $detail->id }}" value="{{ $tx->accounting_number }}">
    </td>
    <td>
        {{-- コメントはring_transactions側のみなので非表示にしてもOK --}}
        <span class="comment-display" id="display-comment-detail-{{ $detail->id }}">―</span>
        <input type="hidden" id="edit-comment-detail-{{ $detail->id }}" value="">
    </td>
    <td>
        <button class="btn btn-sm btn-primary" onclick="enableEdit('detail-{{ $detail->id }}')">編集</button>
        <button class="btn btn-sm btn-success d-none" id="save-btn-detail-{{ $detail->id }}" onclick="saveEdit('detail-{{ $detail->id }}')">保存</button>
        <button class="btn btn-sm btn-danger" onclick="confirmDelete('detail-{{ $detail->id }}')">削除</button>
    </td>
</tr>
@endforeach
@elseif($tx->chips !== 0)
{{-- 通常のリング取引（chips ≠ 0 のみ） --}}
<tr id="row-ring-{{ $tx->id }}">
    <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>
    <td>
        <span class="chip-display" id="display-chips-ring-{{ $tx->id }}">{{ $tx->chips }}</span>
        <input type="number" class="form-control chip-edit d-none" id="edit-chips-ring-{{ $tx->id }}" value="{{ $tx->chips }}">
    </td>
    <td>―</td>
    <td>{{ $tx->type ?? '―' }}</td>
    <td>{{ $tx->action ?? '―' }}</td>
    <td>
        <span class="accounting-number-display" id="display-accounting-number-ring-{{ $tx->id }}">{{ $tx->accounting_number ?? '―' }}</span>
        <input type="text" class="form-control accounting-number-edit d-none" id="edit-accounting-number-ring-{{ $tx->id }}" value="{{ $tx->accounting_number }}">
    </td>
    <td>
        <span class="comment-display" id="display-comment-ring-{{ $tx->id }}">{{ $tx->comment ?? '―' }}</span>
        <input type="text" class="form-control comment-edit d-none" id="edit-comment-ring-{{ $tx->id }}" value="{{ $tx->comment }}">
    </td>
    <td>
        <button class="btn btn-sm btn-primary" onclick="enableEdit('ring-{{ $tx->id }}')">編集</button>
        <button class="btn btn-sm btn-success d-none" id="save-btn-ring-{{ $tx->id }}" onclick="saveEdit('ring-{{ $tx->id }}')">修正</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('ring-{{ $tx->id }}')">削除</button>
    </td>
</tr>
@endif