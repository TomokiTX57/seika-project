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
        Schema::table('zero_system_details', function (Blueprint $table) {
            $table->dropColumn('sum_initial_chips');
        });
    }

    public function down()
    {
        Schema::table('zero_system_details', function (Blueprint $table) {
            $table->integer('sum_initial_chips')->nullable();
        });
    }
};
