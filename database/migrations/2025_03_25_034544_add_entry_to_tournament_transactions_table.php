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
        Schema::table('tournament_transactions', function (Blueprint $table) {
            $table->integer('entry')->default(0)->after('points'); // ← pointsの後にentryを追加
        });
    }

    public function down()
    {
        Schema::table('tournament_transactions', function (Blueprint $table) {
            $table->dropColumn('entry');
        });
    }
};
