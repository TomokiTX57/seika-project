<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\TournamentTransaction;
use Illuminate\Support\Facades\Auth;
use App\Models\RingTransaction;
use App\Models\ZeroSystemHeader;
use App\Models\ZeroSystemDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::orderBy('created_at', 'asc')->get();
        return view('players.index', compact('players'));
    }

    public function search(Request $request)
    {
        $query = Player::query();

        if ($request->has('name')) {
            $query->where('player_name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('player_my_id')) {
            $query->where('player_my_id', 'like', '%' . $request->player_my_id . '%');
        }

        $players = $query->orderBy('created_at', 'asc')->get();

        return response()->json($players);
    }

    public function show(Player $player)
    {
        $tournamentChips = $player->tournamentTransactions()->sum('chips');
        $ringChips = $player->ringTransactions()->sum('chips');

        $unsettledZeroChips = \App\Models\ZeroSystemDetail::whereHas('header', function ($query) use ($player) {
            $query->where('player_id', $player->id)
                ->whereDate('created_at', now()->toDateString())
                ->whereNull('final_chips');
        })->sum('initial_chips');

        $totalRingChips = $ringChips + $unsettledZeroChips;

        return view('players.show', compact(
            'player',
            'tournamentChips',
            'ringChips',
            'unsettledZeroChips',
            'totalRingChips'
        ));
    }

    public function create()
    {
        return view('players.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'player_name' => ['required', 'string', 'max:255'],
            'player_my_id' => ['nullable', 'string', 'max:255', 'unique:players,player_my_id'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'is_subscribed' => ['required', 'boolean'],
            'uid' => ['nullable', 'string', 'max:255'],
        ]);

        Player::create([
            'player_name' => $request->player_name,
            'player_my_id' => $request->player_my_id ?: null, // 空ならNULL
            'comment' => $request->comment,
            'is_subscribed' => $request->is_subscribed,
            'uid' => $request->uid ?: null, // 空ならNULL
        ]);

        return redirect()->route('players.index')->with('success', 'プレイヤーが作成されました');
    }

    public function edit(Player $player)
    {
        return view('players.edit', compact('player'));
    }

    public function update(Request $request, Player $player)
    {
        $request->validate([
            'player_name' => ['required', 'string', 'max:255'],
            'player_my_id' => ['nullable', 'string', 'max:255', 'unique:players,player_my_id,' . $player->id],
            'comment' => ['nullable', 'string', 'max:1000'],
            'is_subscribed' => ['required', 'boolean'],
            'uid' => ['nullable', 'string', 'max:255'],
        ]);

        $player->update([
            'player_name' => $request->player_name,
            'player_my_id' => $request->player_my_id ?: null,
            'comment' => $request->comment,
            'is_subscribed' => $request->is_subscribed,
            'uid' => $request->uid ?: null,
        ]);

        return redirect()->route('players.show', $player)->with('success', 'プレイヤー情報を更新しました');
    }

    public function storeTournamentTransaction(Request $request, Player $player)
    {
        //デバッグ用のログ
        \Log::info('フォーム送信受信:', $request->all()); // ← 追加

        $request->validate([
            'chips' => 'nullable|integer',
            'points' => 'nullable|integer',
            'entry' => 'required|integer',
            'accounting_number' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ]);

        \Log::info('バリデーション通過');

        TournamentTransaction::create([
            'player_id' => $player->id,
            'store_id' => Auth::id(),
            'chips' => $request->chips,
            'points' => $request->points ?? 0,
            'entry' => $request->entry,
            'accounting_number' => $request->accounting_number,
            'comment' => $request->comment,
        ]);

        \Log::info('Entryの値:', ['entry' => $request->entry]);

        \Log::info('トナメ取引を保存しました');

        return redirect()->route('players.show', $player)->with('success', '保存しました');
    }

    public function history(Player $player, Request $request)
    {
        $tab = $request->query('tab', 'tournament'); // デフォルトで "tournament"

        return view('players.history', [
            'player' => $player,
            'tab' => $tab,
        ]);
    }

    public function subscribed()
    {
        $players = Player::where('is_subscribed', true)->orderBy('player_name')->get();
        return view('players.subscribed', compact('players'));
    }

    public function withdrawRing(Request $request, Player $player)
    {
        $ringChips = $player->ringTransactions()->sum('chips');

        $validator = Validator::make($request->all(), [
            'withdraw_amount' => 'required|integer',
            'withdraw_comment' => 'nullable|string|max:1000',
        ]);

        $validator->after(function ($validator) use ($request, $ringChips) {
            $amount = abs((int)$request->withdraw_amount);

            if ($amount > $ringChips) {
                $validator->errors()->add('withdraw_amount', 'チップ不足です');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 負の値でも常にマイナスで保存（安全策として）
        RingTransaction::create([
            'player_id' => $player->id,
            'store_id' => Auth::id(),
            'chips' => -abs((int)$request->withdraw_amount),
            'comment' => $request->withdraw_comment,
            'is_zero_system' => false,
        ]);

        return redirect()->route('players.show', $player)->with('success', '引き出し処理が完了しました');
    }
    public function storeZeroSystem(Request $request, Player $player)
    {
        $request->validate([
            'zero_amount' => 'required|integer|min:1',
        ]);

        \Log::info('storeZeroSystem リクエスト受信', $request->all());

        DB::beginTransaction();

        try {
            // 1. まず RingTransaction を作成
            $ringTransaction = RingTransaction::create([
                'player_id' => $player->id,
                'store_id' => Auth::id(),
                'chips' => 0,
                'is_zero_system' => true,
                'comment' => '0円システム仮記録',
            ]);

            \Log::info('RingTransaction 作成済み', ['id' => $ringTransaction->id]);
            \Log::info('RingTransaction 作成内容', $ringTransaction->toArray());

            // 2. 未精算の ZeroSystemHeader を探す
            $header = ZeroSystemHeader::where('player_id', $player->id)
                ->whereDate('created_at', now()->toDateString())
                ->whereNull('final_chips')
                ->first();

            \Log::info('ZeroSystemHeader 検索結果', ['header' => $header]);

            if (!$header) {
                $header = ZeroSystemHeader::create([
                    'player_id' => $player->id,
                    'store_id' => Auth::id(),
                    'ring_transaction_id' => $ringTransaction->id,
                    'final_chips' => null,
                ]);

                \Log::info('新規 ZeroSystemHeader 作成', ['id' => $header->id]);
            }

            // 3. ZeroSystemDetail を作成
            ZeroSystemDetail::create([
                'zero_system_header_id' => $header->id,
                'initial_chips' => $request->zero_amount,
            ]);

            \Log::info('ZeroSystemDetail 作成', [
                'header_id' => $header->id,
                'initial_chips' => $request->zero_amount,
            ]);

            DB::commit();

            return redirect()->route('players.show', $player)->with('success', '0円システムを登録しました');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('0円システム登録エラー', ['exception' => $e]);
            return back()->with('error', 'エラーが発生しました: ' . $e->getMessage());
        }
    }


    public function settleRing(Player $player)
    {
        DB::beginTransaction();

        try {
            $today = now()->toDateString();

            // 該当ヘッダーを取得（当日の、final_chips が埋まっているもの）
            $header = ZeroSystemHeader::where('player_id', $player->id)
                ->whereDate('created_at', $today)
                ->whereNotNull('final_chips')
                ->first();

            if (!$header) {
                return redirect()->back()->with('error', '精算対象が見つかりません。');
            }

            // ZeroSystemDetail にある初期チップの合計を取得（絶対値で計算）
            $initialTotal = $header->details()->sum(DB::raw('ABS(initial_chips)'));
            $finalChips = $header->final_chips;

            $diff = $finalChips - $initialTotal;

            if ($diff !== 0) {
                RingTransaction::create([
                    'player_id' => $player->id,
                    'store_id' => auth()->id(),
                    'chips' => $diff,
                    'is_zero_system' => true,
                    'comment' => '0システム',
                ]);
            }

            DB::commit();
            return redirect()->route('players.show', $player)->with('success', '精算が完了しました');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '精算処理でエラーが発生しました: ' . $e->getMessage());
        }
    }

    public function cashoutRing(Request $request, Player $player)
    {
        $request->validate([
            'cashout_amount' => 'required|integer|min:1',
            'cashout_comment' => 'nullable|string|max:1000',
        ]);

        $today = now()->toDateString();

        // 今日の未精算ゼロシステムを探す
        $header = ZeroSystemHeader::where('player_id', $player->id)
            ->whereDate('created_at', $today)
            ->whereNull('final_chips')
            ->first();

        if ($header) {
            // 0円システムとして精算（final_chips を更新）
            $header->final_chips = $request->cashout_amount;
            $header->save();
        } else {
            // 通常のキャッシュアウト処理（ring_transactions に記録）
            RingTransaction::create([
                'player_id' => $player->id,
                'store_id' => auth()->id(),
                'chips' => $request->cashout_amount, // 通常はプラス
                'is_zero_system' => false,
                'accounting_number' => null,
                'comment' => $request->cashout_comment,
            ]);

            \Log::info('リングトランザクション作成:', ['id' => $ringTransaction->id]);
        }

        return redirect()->route('players.show', $player)->with('success', 'Cash-outが完了しました');
    }
}
