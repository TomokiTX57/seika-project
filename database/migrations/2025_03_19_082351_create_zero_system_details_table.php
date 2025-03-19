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
        Schema::create('zero_system_details', function (Blueprint $table) {
            $table->id(); // 自動インクリメントのID
            $table->unsignedBigInteger('zero_system_header_id'); // zero_system_headers.id を参照
            $table->integer('initial_chips'); // 初期チップ数
            $table->timestamps(); // created_at, updated_at を自動追加

            // 外部キー制約
            $table->foreign('zero_system_header_id')->references('id')->on('zero_system_headers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zero_system_details');
    }
};
