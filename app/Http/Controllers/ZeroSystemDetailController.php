<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ZeroSystemHeader;

class ZeroSystemDetailController extends Controller
{
    //

    public function header()
    {
        return $this->belongsTo(ZeroSystemHeader::class, 'zero_system_header_id');
    }


    public function update(Request $request, $id)
    {
        \Log::info('ZeroSystemDetail 更新開始', [
            'id' => $id,
            'request' => $request->all(),
        ]);

        $detail = \App\Models\ZeroSystemDetail::findOrFail($id);

        $request->validate([
            'chips' => 'required|integer',
            'accounting_number' => 'nullable|string|max:255',
        ]);

        $detail->initial_chips = $request->chips;
        $detail->save();

        $header = $detail->header;

        if ($header && $header->ringTransaction) {
            $tx = $header->ringTransaction;

            if ($tx->action === 'in' && $tx->type === '0円システム') {
                $tx->accounting_number = $request->accounting_number;
                $tx->save();

            } else {
                \Log::info('リングトランザクションの条件が一致しないため更新されませんでした', [
                    'tx_id' => $tx->id,
                    'action' => $tx->action,
                    'type' => $tx->type,
                ]);
            }
        } else {
            \Log::warning('ZeroSystemHeaderまたはその関連トランザクションが見つかりません', [
                'detail_id' => $detail->id,
                'header_id' => $header?->id,
            ]);
        }

        return response()->json(['message' => '更新完了']);
    }


    public function destroy($id)
    {
        $detail = \App\Models\ZeroSystemDetail::findOrFail($id);

        $header = $detail->header;

        // 明細削除
        $detail->delete();

        // ヘッダーが存在すれば再計算
        if ($header) {
            // 合計再計算
            $header->sum_initial_chips = $header->details()->sum('initial_chips');

            // final_chipsが設定されている場合はnullに戻す
            if (!is_null($header->final_chips)) {
                $header->final_chips = null;
            }

            $header->save();
        }

        // 明細が他に無ければヘッダーも削除
        if ($header && $header->details()->count() === 0) {
            $header->delete();
        }

        return response()->json(['message' => '削除完了']);
    }
}
