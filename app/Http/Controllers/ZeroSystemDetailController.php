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
        $detail = \App\Models\ZeroSystemDetail::findOrFail($id);

        $request->validate([
            'chips' => 'required|integer',
        ]);

        $detail->initial_chips = $request->chips;
        $detail->save();

        // 親ヘッダーの再計算
        $header = $detail->header;
        $header->sum_initial_chips = $header->details()->sum('initial_chips');

        // 既にfinal_chipsがあれば null に戻す（追加入力されたとみなして）
        if (!is_null($header->final_chips)) {
            $header->final_chips = null;
        }

        $header->save();

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

        return response()->json(['message' => '削除完了']);
    }
}
