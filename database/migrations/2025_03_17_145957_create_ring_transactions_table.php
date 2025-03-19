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
        Schema::create('ring_transactions', function (Blueprint $table) {
            $table->id(); // 自動インクリメントの ID
            $table->unsignedBigInteger('player_id'); // プレイヤーID（外部キー）
            $table->unsignedBigInteger('store_id'); // 店舗ID（外部キー）
            $table->integer('chips'); // チップ数
            $table->boolean('is_zero_system')->default(false); // 0円システム適用 (デフォルト false)
            $table->string('accounting_number')->nullable(); // 会計番号 (NULL 許可)
            $table->text('comment')->nullable(); // コメント (NULL 許可)
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at

            // 外部キー制約（削除時に連動する場合は onDelete('cascade') も追加可能）
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ring_transactions');
    }
};
