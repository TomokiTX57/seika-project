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
            $table->integer('sum_initial_chips')->nullable()->after('initial_chips');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zero_system_details', function (Blueprint $table) {
            //
        });
    }
};
