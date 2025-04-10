<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RingTransaction;
use Carbon\Carbon;

class RingHistoryController extends Controller
{

    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());

        $transactions = RingTransaction::with(['player', 'zeroSystemHeader'])
            ->whereDate('created_at', $date)
            ->where(function ($query) {
                $query->where('chips', '!=', 0)
                    ->orWhere(function ($q) {
                        $q->where('type', '!=', '0円システム')
                            ->orWhere('action', '!=', 'in');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ring-histories.index', compact('transactions'));
    }
}
