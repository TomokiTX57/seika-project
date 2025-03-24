<?php
// app/Http/Controllers/TournamentController.php
namespace App\Http\Controllers;

use App\Models\TournamentTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TournamentController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        // 該当日付のトーナメントトランザクションを取得
        $transactions = TournamentTransaction::with(['player.tournamentTransactions'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'asc')
            ->get();

        // その日付にトランザクションがあるプレイヤーごとにまとめる
        $groupedByPlayer = $transactions->groupBy('player_id');

        return view('tournaments.index', [
            'transactions' => $transactions,
            'date' => $date,
            'groupedByPlayer' => $groupedByPlayer
        ]);
    }

    public function edit($id)
    {
        $transaction = TournamentTransaction::with('player')->findOrFail($id);
        return view('tournaments.edit', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $transaction = TournamentTransaction::findOrFail($id);

        $request->validate([
            'chips' => ['nullable', 'integer', 'min:0'],
            'points' => ['required', 'integer', 'min:0'],
            'accounting_number' => ['nullable', 'string', 'max:255'],
            'entry' => ['required', 'integer', 'min:0'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $transaction->update($request->only(['chips', 'points', 'entry', 'accounting_number', 'comment']));

        return redirect()->route('tournaments.index');
    }
}
