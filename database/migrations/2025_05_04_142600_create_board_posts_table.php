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
        Schema::create('board_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id');  // 게시판 ID
            $table->unsignedBigInteger('user_id')->nullable();  // 작성자 ID (로그인한 경우)
            $table->string('author_name');  // 작성자 이름
            $table->string('password')->nullable();  // 비회원 게시글 비밀번호
            $table->string('title');  // 게시글 제목
            $table->text('content');  // 게시글 내용
            $table->string('ip_address')->nullable();  // 작성자 IP
            $table->integer('view_count')->default(0);  // 조회수
            $table->integer('like_count')->default(0);  // 좋아요 수
            $table->integer('comment_count')->default(0);  // 댓글 수
            $table->boolean('is_notice')->default(false);  // 공지사항 여부
            $table->boolean('is_secret')->default(false);  // 비밀글 여부
            $table->boolean('is_pinned')->default(false);  // 상단 고정 여부
            $table->timestamp('published_at')->nullable();  // 발행일
            $table->timestamps();
            $table->softDeletes();  // 소프트 삭제 지원

            // 인덱스
            $table->index(['board_id', 'created_at']);
            $table->index(['board_id', 'is_notice', 'created_at']);
            $table->index(['board_id', 'is_pinned', 'created_at']);

            // 외래 키 제약 조건
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_posts');
    }
};
