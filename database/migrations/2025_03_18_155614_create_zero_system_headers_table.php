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
        Schema::create('zero_system_headers', function (Blueprint $table) {
            $table->id(); // 自動インクリメントのID
            $table->unsignedBigInteger('player_id'); // players.id を参照
            $table->unsignedBigInteger('store_id'); // ユーザーに紐づく店舗ID
            $table->integer('final_chips'); // 最終チップ数
            $table->unsignedBigInteger('ring_transaction_id'); // Ring_Transactions の id を参照
            $table->timestamps(); // created_at, updated_at を自動追加

            // 外部キー制約
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ring_transaction_id')->references('id')->on('ring_transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zero_system_headers');
    }
};
