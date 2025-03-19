<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_numbers', function (Blueprint $table) {
            $table->id(); // 自動インクリメントのID
            $table->unsignedBigInteger('player_id'); // players.id を参照
            $table->unsignedBigInteger('store_id'); // users.id を参照（店舗ID）
            $table->string('accounting_number')->unique(); // 会計番号（ユニーク制約）
            $table->timestamps(); // created_at, updated_at を自動追加

            // 外部キー制約
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');

            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_numbers');
    }
};
