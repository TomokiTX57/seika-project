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
        Schema::create('tournament_transactions', function (Blueprint $table) {
            $table->id(); // 自動インクリメントのID
            $table->unsignedBigInteger('player_id'); // プレイヤーID
            $table->unsignedBigInteger('store_id'); // ユーザーに紐づく店舗ID
            $table->integer('chips'); // チップ数
            $table->integer('points'); // ポイント数
            $table->string('accounting_number')->nullable(); // 会計番号（NULL許可）
            $table->text('comment')->nullable(); // コメント（NULL許可）
            $table->timestamps(); // created_at, updated_at を自動追加
            $table->softDeletes(); // deleted_at を自動追加

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
        Schema::dropIfExists('tournament_transactions');
    }
};
