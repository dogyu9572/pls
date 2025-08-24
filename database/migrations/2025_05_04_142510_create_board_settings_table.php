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
        Schema::create('board_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id');
            $table->string('key');  // 설정 키
            $table->text('value')->nullable();  // 설정 값
            $table->timestamps();

            // 외래 키 제약 조건
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');

            // 복합 유니크 키 (한 게시판에 중복 키가 없도록)
            $table->unique(['board_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_settings');
    }
};
