<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('board_gallerys', function (Blueprint $table) {
            // 기본 컬럼들 (모든 게시판 공통)
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title');
            $table->text('content');
            $table->string('author_name');
            $table->string('password')->nullable();
            $table->boolean('is_notice')->default(false);
            $table->boolean('is_secret')->default(false);
            $table->string('category')->nullable();
            $table->json('attachments')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('sort_order')->default(0)->comment('정렬 순서');
            
            // 커스텀 필드들 (JSON으로 저장)
            $table->json('custom_fields')->nullable();
            
            // 갤러리 전용 컬럼 (사용하지 않으면 NULL)
            $table->string('thumbnail')->nullable(); // 썸네일 이미지 경로
            
            $table->timestamps();
            $table->softDeletes();

            // 인덱스
            $table->index(['is_notice', 'created_at']);
            $table->index(['category', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['thumbnail']); // 갤러리 검색용
            $table->index(['sort_order']); // 정렬 성능 최적화
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_gallerys');
    }
};