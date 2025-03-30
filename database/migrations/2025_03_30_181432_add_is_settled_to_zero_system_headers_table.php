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
        Schema::table('zero_system_headers', function (Blueprint $table) {
            $table->boolean('is_settled')->default(false)->after('final_chips');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zero_system_headers', function (Blueprint $table) {
            $table->dropColumn('is_settled');
        });
    }
};
