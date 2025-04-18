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
            'comment' => 'nullable|string',
            'accounting_number' => 'nullable|string|max:255',
        ]);

        $originalChips = $tx->chips;
        $tx->chips = $request->chips;
        $tx->comment = $request->comment;
        $tx->accounting_number = $request->accounting_number;

        if ($tx->is_zero_system && $tx->type === '0円システム') {
            if ($tx->action === 'out') {
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
            } elseif ($tx->action === 'in') {
                $header = $tx->zeroSystemHeader;
                if ($header) {
                    $header->sum_initial_chips = $header->details()->sum('initial_chips');
                    $header->save();

                    \Log::info('ZeroSystemHeader sum_initial_chips を更新', [
                        'header_id' => $header->id,
                        'sum_initial_chips' => $header->sum_initial_chips,
                    ]);
                }
            }
        }
        $tx->save();

        \Log::info('RingTransaction 更新', [
            'id' => $tx->id,
            'original_chips' => $originalChips,
            'updated_chips' => $tx->chips,
            'comment' => $tx->comment,
            'accounting_number' => $tx->accounting_number,
        ]);



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
