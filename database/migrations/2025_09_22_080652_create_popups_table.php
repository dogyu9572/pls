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
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // 팝업제목
            $table->datetime('start_date')->nullable(); // 게시 시작일
            $table->datetime('end_date')->nullable(); // 게시 종료일
            $table->boolean('use_period')->default(false); // 게시기간 사용 여부
            $table->integer('width')->default(400); // 팝업가로 (px)
            $table->integer('height')->default(300); // 팝업세로 (px)
            $table->integer('position_top')->default(100); // 팝업위치(Top) (px)
            $table->integer('position_left')->default(100); // 팝업위치(Left) (px)
            $table->string('url')->nullable(); // URL
            $table->enum('url_target', ['_self', '_blank'])->default('_blank'); // 새창/현재창
            $table->enum('popup_type', ['image', 'html'])->default('image'); // 팝업타입
            $table->enum('popup_display_type', ['normal', 'layer'])->default('normal'); // 팝업표시타입 (일반팝업/레이어팝업)
            $table->string('popup_image')->nullable(); // 팝업이미지
            $table->longText('popup_content')->nullable(); // HTML 콘텐츠
            $table->boolean('is_active')->default(true); // 사용여부
            $table->integer('sort_order')->default(0); // 순서
            $table->timestamps();
            $table->softDeletes(); // 소프트 삭제
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popups');
    }
};
