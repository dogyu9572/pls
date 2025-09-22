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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // 배너제목
            $table->string('main_text')->nullable(); // 메인텍스트
            $table->string('sub_text')->nullable(); // 서브텍스트
            $table->string('url')->nullable(); // URL
            $table->enum('url_target', ['_self', '_blank'])->default('_self'); // 현재창/새창
            $table->datetime('start_date')->nullable(); // 게시 시작일
            $table->datetime('end_date')->nullable(); // 게시 종료일
            $table->boolean('use_period')->default(false); // 게시기간 사용 여부
            $table->boolean('is_active')->default(true); // 사용여부 (사용/숨김)
            $table->string('desktop_image')->nullable(); // 데스크톱 이미지
            $table->string('mobile_image')->nullable(); // 모바일 이미지
            $table->string('video_url')->nullable(); // 영상 URL
            $table->integer('sort_order')->default(0); // 배너순서
            $table->timestamps();
            $table->softDeletes(); // 소프트 삭제
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
