<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RingTransactionController extends Controller
{
    //

    public function update(Request $request, $id)
    {
        $tx = \App\Models\RingTransaction::findOrFail($id);

        $request->validate([
            'chips' => 'required|integer',
        ]);

        $originalChips = $tx->chips;
        $tx->chips = $request->chips;
        $tx->save();

        \Log::info('RingTransaction 更新', [
            'id' => $tx->id,
            'original_chips' => $originalChips,
            'updated_chips' => $tx->chips,
        ]);

        // 0円システムの out の場合、final_chips も更新
        if ($tx->is_zero_system && $tx->type === '0円システム' && $tx->action === 'out') {
            $header = \App\Models\ZeroSystemHeader::where('player_id', $tx->player_id)
                ->whereDate('created_at', $tx->created_at->toDateString())
                ->where('final_chips', $originalChips)
                ->first();

            if ($header) {
                $header->final_chips = $tx->chips;
                $header->save();

                \Log::info('ZeroSystemHeader final_chips を更新', [
                    'header_id' => $header->id,
                    'final_chips' => $header->final_chips,
                ]);
            } else {
                \Log::warning('final_chips を更新できるヘッダーが見つかりませんでした', [
                    'player_id' => $tx->player_id,
                    'original_chips' => $originalChips,
                ]);
            }
        }

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
