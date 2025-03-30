<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ring_transactions', function (Blueprint $table) {
            $table->string('type')->nullable();   // 0円システム or 引き出し
            $table->string('action')->nullable(); // in, out, 清算
        });
    }

    public function down()
    {
        Schema::table('ring_transactions', function (Blueprint $table) {
            $table->dropColumn(['type', 'action']);
        });
    }
};
