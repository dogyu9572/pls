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
        Schema::create('board_skins', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // 스킨 이름
            $table->string('directory');  // 스킨 디렉토리 경로
            $table->text('description')->nullable();  // 스킨 설명
            $table->string('thumbnail')->nullable();  // 스킨 미리보기 이미지
            $table->json('options')->nullable();  // 스킨 옵션(JSON)
            $table->boolean('is_active')->default(true);  // 활성화 여부
            $table->boolean('is_default')->default(false);  // 기본 스킨 여부
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_skins');
    }
};
