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
        Schema::create('players', function (Blueprint $table) {
            $table->id(); // 自動インクリメントの ID
            $table->string('player_my_id')->unique(); // 一意なプレイヤーID
            $table->string('player_name'); // プレイヤー名
            $table->boolean('is_subscribed')->default(false); // サブスク登録 (デフォルトfalse)
            $table->timestamp('created_data')->nullable(); // 作成日
            $table->timestamp('updated_data')->nullable(); // 更新日
            $table->timestamp('deleted_data')->nullable(); // 削除日
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
