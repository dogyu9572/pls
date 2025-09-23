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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // 게시판 이름
            $table->string('slug')->unique();  // URL에 사용할 슬러그
            $table->text('description')->nullable();  // 게시판 설명
            $table->unsignedBigInteger('skin_id');  // 사용할 스킨 ID
            $table->boolean('is_active')->default(true);  // 활성화 여부
            $table->boolean('table_created')->default(false);  // 동적 테이블 생성 여부
            $table->integer('list_count')->default(15);  // 페이지당 게시물 수
            $table->boolean('enable_notice')->default(true);  // 공지사항 활성화 여부
            $table->boolean('is_single_page')->default(false)->comment('단일페이지 모드 여부');  // 단일페이지 모드
            $table->boolean('enable_sorting')->default(false)->comment('정렬 기능 활성화 여부');  // 정렬 기능 활성화
            $table->integer('hot_threshold')->default(100);  // 인기 게시물 조회수 기준
            $table->string('permission_read')->default('all');  // 읽기 권한 (all, member, admin)
            $table->string('permission_write')->default('member');  // 쓰기 권한
            $table->string('permission_comment')->default('member');  // 댓글 권한
            $table->timestamps();
            $table->softDeletes();  // 소프트 삭제 지원
            $table->json('custom_fields_config')->nullable();  // 커스텀 필드 설정

            // 외래 키 제약 조건
            $table->foreign('skin_id')->references('id')->on('board_skins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
