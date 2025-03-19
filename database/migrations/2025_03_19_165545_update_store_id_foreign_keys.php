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
        Schema::table('ring_transactions', function (Blueprint $table) {
            $table->dropForeign(['store_id']); // store_id の外部キー制約を削除
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade'); // users.id に変更
        });

        Schema::table('tournament_transactions', function (Blueprint $table) {
            $table->dropForeign(['store_id']); // store_id の外部キー制約を削除
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade'); // users.id に変更
        });

        Schema::table('zero_system_headers', function (Blueprint $table) {
            $table->dropForeign(['store_id']); // store_id の外部キー制約を削除
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade'); // users.id に変更
        });

        Schema::table('account_numbers', function (Blueprint $table) {
            $table->dropForeign(['store_id']); // store_id の外部キー制約を削除
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade'); // users.id に変更
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ring_transactions', function (Blueprint $table) {
            $table->dropForeign(['store_id']); // 外部キーを削除
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade'); // 元に戻す
        });

        Schema::table('tournament_transactions', function (Blueprint $table) {
            $table->dropForeign(['store_id']); // 外部キーを削除
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade'); // 元に戻す
        });

        Schema::table('zero_system_headers', function (Blueprint $table) {
            $table->dropForeign(['store_id']); // 外部キーを削除
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade'); // 元に戻す
        });

        Schema::table('account_numbers', function (Blueprint $table) {
            $table->dropForeign(['store_id']); // 外部キーを削除
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade'); // 元に戻す
        });
    }
};
