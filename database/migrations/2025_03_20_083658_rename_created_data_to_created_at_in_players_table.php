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
        Schema::table('players', function (Blueprint $table) {
            $table->renameColumn('created_data', 'created_at');
            $table->renameColumn('updated_data', 'updated_at');
            $table->renameColumn('deleted_data', 'deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->renameColumn('created_at', 'created_data');
            $table->renameColumn('updated_at', 'updated_data');
            $table->renameColumn('deleted_at', 'deleted_data');
        });
    }
};
