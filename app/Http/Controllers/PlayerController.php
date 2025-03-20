<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

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
        return view('players.show', compact('player'));
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
            'is_subscribed' => ['required', 'boolean'],
            'uid' => ['nullable', 'string', 'max:255'],
        ]);

        Player::create([
            'player_name' => $request->player_name,
            'player_my_id' => $request->player_my_id ?: null, // 空ならNULL
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
            'is_subscribed' => ['required', 'boolean'],
            'uid' => ['nullable', 'string', 'max:255'],
        ]);

        $player->update([
            'player_name' => $request->player_name,
            'player_my_id' => $request->player_my_id ?: null,
            'is_subscribed' => $request->is_subscribed,
            'uid' => $request->uid ?: null,
        ]);

        return redirect()->route('players.show', $player)->with('success', 'プレイヤー情報を更新しました');
    }
}
