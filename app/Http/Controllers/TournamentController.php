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

        $transactions = TournamentTransaction::whereDate('created_at', $date)
            ->with('player') // プレイヤー名を取得する前提
            ->orderBy('created_at', 'asc')
            ->get();

        return view('tournaments.index', compact('transactions', 'date'));
    }

    public function edit($id)
    {
        $transaction = TournamentTransaction::with('player')->findOrFail($id);
        return view('tournaments.edit', compact('transaction'));
    }
}
