<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\ZeroSystemDetailController;
use App\Http\Controllers\RingTransactionController;
use App\Http\Controllers\RingHistoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/players', [PlayerController::class, 'index'])->name('players.index');

    Route::get('/players/create', [PlayerController::class, 'create'])->name('players.create');
    Route::get('/players/search', [PlayerController::class, 'search'])->name('players.search');
    Route::delete('/players/{player}', [PlayerController::class, 'destroy'])->name('players.destroy');
    Route::get('/players/{player}', [PlayerController::class, 'show'])->name('players.show');
    Route::post('/players', [PlayerController::class, 'store'])->name('players.store');
    Route::get('/players/{player}/edit', [PlayerController::class, 'edit'])->name('players.edit');
    Route::put('/players/{player}', [PlayerController::class, 'update'])->name('players.update');
    Route::post('/players/{player}/tournament-transactions', [PlayerController::class, 'storeTournamentTransaction'])->name('players.tournament.store');
    Route::get('/players/{player}/history', [PlayerController::class, 'history'])->name('players.history');

    Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/{tournament}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
    Route::put('/tournaments/{tournament}', [TournamentController::class, 'update'])->name('tournaments.update');

    Route::get('/subscribed-players', [PlayerController::class, 'subscribed'])->name('players.subscribed');

    Route::get('/ring-histories', [RingHistoryController::class, 'index'])->name('ring-histories.index');

    Route::post('/players/{player}/ring/withdraw', [PlayerController::class, 'withdrawRing'])->name('players.ring.withdraw');
    Route::post('/players/{player}/ring/cashout', [PlayerController::class, 'cashoutRing'])->name('players.ring.cashout');
    Route::get('/players/{player}/ring/settle', [PlayerController::class, 'settleRing'])->name('players.ring.settle');

    Route::post('/players/{player}/zero-system', [PlayerController::class, 'storeZeroSystem'])->name('players.zero-system.store');

    Route::get('/zero-system-users', [PlayerController::class, 'zeroSystemUsers'])->name('zero-system.users');
    Route::get('/zero-system/edit/{player}', [PlayerController::class, 'editZeroSystem'])->name('zero-system.edit');
    Route::get('/zero-system/checkout/{player}', [PlayerController::class, 'checkoutZeroSystem'])->name('zero-system.checkout');
    // 0円システム詳細の更新・削除ルート（編集・削除ボタン対応）
    Route::put('/zero-system-details/{id}', [ZeroSystemDetailController::class, 'update']);
    Route::delete('/zero-system-details/{id}', [ZeroSystemDetailController::class, 'destroy']);
    // RingTransaction 更新・削除ルート（編集・削除ボタン対応）
    Route::put('/ring-transactions/{id}', [RingTransactionController::class, 'update']);
    Route::delete('/ring-transactions/{id}', [RingTransactionController::class, 'destroy']);
});

require __DIR__ . '/auth.php';
