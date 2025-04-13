<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Player;
use App\Models\TournamentTransaction;
use Illuminate\Support\Facades\Auth;
use App\Models\RingTransaction;
use App\Models\ZeroSystemHeader;
use App\Models\ZeroSystemDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class PlayerController extends Controller
{
    // プレイヤー一覧　ページネーション
    public function index(Request $request)
    {
        $agent = new Agent();

        // スマホなら15件、それ以外なら50件
        $perPage = $agent->isMobile() ? 12 : 13;

        $players = Player::orderBy('created_at', 'asc')->paginate($perPage);

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

    // プレイヤーの詳細画面
    public function show(Player $player)
    {

        $latestRingTx = $player->ringTransactions()
            ->whereDate('created_at', now()->toDateString())
            ->where('type', '0円システム')
            ->where('action', 'in')
            ->whereNotNull('accounting_number')
            ->orderByDesc('created_at')
            ->first();

        $latestAccountingNumber = $latestRingTx?->accounting_number;
        $tournamentChips = $player->tournamentTransactions()->sum('chips');
        $ringChips = $player->ringTransactions()
            ->where(function ($query) {
                $query->where('chips', '!=', 0)
                    ->orWhere(function ($q) {
                        $q->where('type', '!=', '0円システム')
                            ->orWhere('action', '!=', 'in');
                    });
            })
            ->sum('chips');

        // 未精算の0円システムチップ
        $unsettledZeroChips = \App\Models\ZeroSystemDetail::whereHas('header', function ($query) use ($player) {
            $query->where('player_id', $player->id)
                ->whereDate('created_at', now()->toDateString())
                ->whereNull('final_chips');
        })->sum('initial_chips');

        $totalRingChips = $ringChips + $unsettledZeroChips;

        // 利用状態（表示用）
        $chipStatus = $player->hasUnsettledZeroSystem() ? '0円システム利用中' : '通常チップ利用中';

        $shouldSettle = \App\Models\ZeroSystemHeader::where('player_id', $player->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereNotNull('final_chips')       // ← cashout 済み
            ->where('is_settled', false)        // ← まだ精算してない
            ->exists();

        return view('players.show', compact(
            'player',
            'tournamentChips',
            'ringChips',
            'unsettledZeroChips',
            'totalRingChips',
            'chipStatus',
            'shouldSettle',
            'latestAccountingNumber'
        ));
    }

    // プレイヤーの新規作成画面
    public function create()
    {
        return view('players.create');
    }

    // プレイヤーの新規作成
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

    // プレイヤー情報の更新
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

    // プレイヤーの削除
    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')->with('success', 'プレイヤーを削除しました');
    }

    //トナメの取引を保存
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

        $ringTransactions = RingTransaction::where('player_id', $player->id)
            ->with(['zeroSystemHeader.details' => function ($query) {
                $query->where('initial_chips', '>', 0)->orderByDesc('created_at');
            }])
            ->orderByDesc('created_at')
            ->get();

        return view('players.history', [
            'player' => $player,
            'tab' => $tab,
            'ringTransactions' => $ringTransactions, // ←ビューで使用するため追加
        ]);
    }

    //サブスク
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
            'is_zero_system' => false,
            'type' => '引き出し',
            'action' => 'in',
            'comment' => $request->withdraw_comment ?: null,
            'accounting_number' => $request->accounting_number,
        ]);

        return redirect()->route('players.show', $player)->with('success', '引き出し処理が完了しました');
    }

    // 0円システムのcashin
    public function storeZeroSystem(Request $request, Player $player)
    {

        \Log::debug('zero_amountの値: ' . json_encode($request->input('zero_amount')));

        if ($request->filled('zero_amount')) {
            $request->validate([
                'zero_amount' => 'required|integer|min:1',
            ]);
        }

        \Log::info('storeZeroSystem リクエスト受信', $request->all());

        DB::beginTransaction();

        try {
            // 1. 取引を作成（chips=0）
            $ringTransaction = RingTransaction::create([
                'player_id' => $player->id,
                'store_id' => Auth::id(),
                'chips' => 0,
                'is_zero_system' => true,
                'type' => '0円システム',
                'action' => 'in',
                'comment' => null,
                'accounting_number' => $request->accounting_number,
            ]);

            // 2. 未精算のヘッダーを取得（なければ新規作成）
            $header = ZeroSystemHeader::where('player_id', $player->id)
                ->whereDate('created_at', now()->toDateString())
                ->whereNull('final_chips')
                ->first();

            if (!$header) {
                $header = ZeroSystemHeader::create([
                    'player_id' => $player->id,
                    'store_id' => Auth::id(),
                    'ring_transaction_id' => $ringTransaction->id,
                    'final_chips' => null,
                ]);
            }

            // 3. 合計（未cashoutな明細のみ）を再計算
            $existingSum = ZeroSystemDetail::where('zero_system_header_id', $header->id)->sum('initial_chips');
            $newAmount = $request->zero_amount;
            $newSum = $existingSum + $newAmount;

            // 4. 明細を作成
            // 3. ZeroSystemDetail を作成
            ZeroSystemDetail::create([
                'zero_system_header_id' => $header->id,
                'initial_chips' => $request->zero_amount,
            ]);

            // 5. detailsにもsum_initial_chips を保存（ビュー側で使う想定）
            $sum = ZeroSystemDetail::where('zero_system_header_id', $header->id)->sum('initial_chips');
            $header->sum_initial_chips = $sum;
            $header->save();

            DB::commit();

            return redirect($request->input('redirect_to', route('players.show', $player)))
                ->with('success', '0円システムを登録しました');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('0円システム登録エラー', ['exception' => $e]);
            return back()->with('error', 'エラーが発生しました: ' . $e->getMessage());
        }
    }

    // 0円システムの精算
    public function settleRing(Player $player)
    {
        DB::beginTransaction();

        try {
            $today = now()->toDateString();

            // 未清算で final_chips が入っているヘッダーをすべて取得
            $headers = ZeroSystemHeader::where('player_id', $player->id)
                ->whereDate('created_at', $today)
                ->whereNotNull('final_chips')
                ->where('is_settled', false)
                ->get();

            if ($headers->isEmpty()) {
                return back()->with('error', '精算対象が見つかりません。');
            }

            foreach ($headers as $header) {
                // 対象ヘッダーに紐づく明細の初期チップ合計
                $initialTotal = ZeroSystemDetail::where('zero_system_header_id', $header->id)
                    ->sum(DB::raw('ABS(initial_chips)'));

                $finalChips = $header->final_chips;
                $diff = $finalChips - $initialTotal;

                if ($diff !== 0) {
                    RingTransaction::create([
                        'player_id' => $player->id,
                        'store_id' => auth()->id(),
                        'chips' => $diff,
                        'is_zero_system' => true,
                        'type' => '0円システム',
                        'action' => '清算',
                        'comment' => null, // コメントは純粋にユーザー入力のみ
                    ]);
                }

                $header->is_settled = true;
                $header->save();
            }

            DB::commit();

            return redirect()->back()->with('success', '未清算分をすべて精算しました');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '精算処理でエラーが発生しました: ' . $e->getMessage());
        }
    }

    // 引き出しのcashout
    public function cashoutRing(Request $request, Player $player)
    {
        $request->validate([
            'cashout_amount' => 'required|integer|min:0',
            'cashout_comment' => 'nullable|string|max:1000',
        ]);

        \Log::info('cashout処理開始', [
            'player_id' => $player->id,
            'cashout_amount' => $request->cashout_amount,
        ]);

        $today = now()->toDateString();

        // 今日の未精算ゼロシステムを探す
        $header = ZeroSystemHeader::where('player_id', $player->id)
            ->whereDate('created_at', $today)
            ->whereNull('final_chips')
            ->first();


        \Log::info('取得したheader', $header?->toArray() ?? ['header' => null]);

        if ($header) {
            // final_chips を更新
            $header->final_chips = $request->cashout_amount;
            $header->save();

            \Log::info('final_chipsを更新しました', ['final_chips' => $header->final_chips]);

            // RingTransaction にも記録（0円システム out として履歴に表示）
            RingTransaction::create([
                'player_id' => $player->id,
                'store_id' => auth()->id(),
                'chips' => $request->cashout_amount,
                'is_zero_system' => true,
                'type' => '0円システム',
                'action' => 'out',
                'comment' => null,
            ]);
        } else {
            // 通常のキャッシュアウト処理
            RingTransaction::create([
                'player_id' => $player->id,
                'store_id' => auth()->id(),
                'chips' => $request->cashout_amount,
                'is_zero_system' => false,
                'type' => '引き出し',
                'action' => 'out',
                'comment' => $request->cashout_comment,
            ]);
        }

        return redirect()->route('zero-system.users')->with('success', '更新が完了しました');
    }

    // 0円システムを利用しているプレイヤー一覧
    public function zeroSystemUsers()
    {
        $today = now()->toDateString();

        // 本日中に0円システムを利用しているプレイヤー一覧を取得
        $headers = ZeroSystemHeader::with(['player'])
            ->whereDate('created_at', $today)
            ->get();

        return view('zero_systems.users', compact('headers'));
    }

    // 0円システムプレイヤー一覧の編集画面
    public function editZeroSystem(Player $player)
    {
        $today = now()->toDateString();

        // header は取得しなくてもよければ省略可能（使用していなければ）
        $header = ZeroSystemHeader::where('player_id', $player->id)
            ->whereDate('created_at', $today)
            ->whereNull('is_settled')
            ->first();

        $ringTx = $player->ringTransactions()
            ->whereDate('created_at', $today)
            ->whereNotNull('accounting_number')
            ->orderByDesc('created_at')
            ->first();

        $tournamentTx = $player->tournamentTransactions()
            ->whereDate('created_at', $today)
            ->whereNotNull('accounting_number')
            ->orderByDesc('created_at')
            ->first();

        $latestAccountingNumber = $ringTx->accounting_number ?? $tournamentTx->accounting_number ?? null;

        return view('zero_systems.edit', compact('player', 'header', 'latestAccountingNumber'));
    }

    // 0円システムの会計画面
    public function checkoutZeroSystem(Player $player)
    {
        $today = now()->toDateString();

        // 当日の zero system データ（合計集計用）
        $headers = ZeroSystemHeader::with('details')
            ->where('player_id', $player->id)
            ->whereDate('created_at', $today)
            ->get();

        $totalCashIn = $headers->flatMap->details->sum('initial_chips');
        $totalCashOut = $headers->sum('final_chips');
        $chipDifference = $totalCashOut - $totalCashIn;

        // RingTransaction を全件取得（chips=0 も含む）、ただし詳細もwith
        $ringTransactions = RingTransaction::where('player_id', $player->id)
            ->whereDate('created_at', now()->toDateString())
            ->with(['zeroSystemHeader.details' => function ($query) {
                $query->where('initial_chips', '>', 0)->orderByDesc('created_at');
            }])
            ->orderByDesc('created_at')
            ->get();

        return view('zero_systems.checkout', compact(
            'player',
            'totalCashIn',
            'totalCashOut',
            'chipDifference',
            'ringTransactions'
        ));
    }

    // RingTransaction 更新処理
    public function updateRingTransaction(Request $request, $id): JsonResponse
    {
        \Log::info("updateRingTransaction 入力:", $request->all());

        try {
            $tx = RingTransaction::findOrFail($id);
            $tx->chips = $request->chips;
            $tx->comment = $request->comment;
            $tx->save();

            \Log::info("更新完了:", $tx->toArray());

            return response()->json(['message' => '更新完了']);
        } catch (\Exception $e) {
            \Log::error("更新失敗:", ['error' => $e->getMessage()]);
            return response()->json(['error' => '更新に失敗しました'], 500);
        }
    }

    // RingTransaction 削除処理
    public function deleteZeroSystemDetail($id): JsonResponse
    {
        ZeroSystemDetail::destroy($id);
        return response()->json(['message' => '明細を削除しました']);
    }
}
