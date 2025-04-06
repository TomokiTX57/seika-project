<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RingTransactionController extends Controller
{
    //

    public function update(Request $request, $id)
    {
        $detail = \App\Models\RingTransaction::findOrFail($id);

        $request->validate([
            'chips' => 'required|integer',
        ]);

        $detail->initial_chips = $request->chips;
        $detail->save();

        // ヘッダーの初期チップの合計を更新
        $header = $detail->header;
        $header->sum_initial_chips = $header->details()->sum('initial_chips');
        $header->save();

        return response()->json(['message' => '更新完了']);
    }

    public function destroy($id)
    {
        $tx = \App\Models\RingTransaction::findOrFail($id);

        // 0円システムの out の場合、final_chips を null に戻す
        if ($tx->is_zero_system && $tx->type === '0円システム' && $tx->action === 'out') {
            $header = \App\Models\ZeroSystemHeader::where('player_id', $tx->player_id)
                ->whereDate('created_at', now()->toDateString())
                ->where('final_chips', $tx->chips)
                ->first();

            if ($header) {
                $header->final_chips = null;
                $header->save();
            }
        }

        $tx->delete();

        return response()->json(['message' => '削除完了']);
    }
}
