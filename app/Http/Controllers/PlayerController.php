<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\TournamentTransaction;
use Illuminate\Support\Facades\Auth;

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

        return view('players.show', compact('player', 'tournamentChips'));
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
        \Log::info('フォーム送信受信:', $request->all()); // ← 追加

        // もしここで止まっていたらログは出ない
        $request->validate([
            'chips' => 'required|integer',
            'points' => 'nullable|integer',
            'accounting_number' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ]);

        \Log::info('バリデーション通過');

        TournamentTransaction::create([
            'player_id' => $player->id,
            'store_id' => Auth::id(),
            'chips' => $request->chips,
            'points' => $request->points ?? 0,
            'accounting_number' => $request->accounting_number,
            'comment' => $request->comment,
        ]);

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
}
