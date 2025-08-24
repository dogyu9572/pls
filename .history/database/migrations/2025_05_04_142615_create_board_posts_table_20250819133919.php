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
            $table->unsignedBigInteger('user_id')->nullable();  // 작성자 ID (비회원 지원)
            $table->string('author_name');  // 작성자명 (비회원용)
            $table->string('password')->nullable();  // 비회원 비밀번호
            $table->string('title');  // 제목
            $table->text('content');  // 내용
            $table->integer('view_count')->default(0);  // 조회수
            $table->boolean('is_notice')->default(false);  // 공지글 여부
            $table->boolean('is_secret')->default(false);  // 비밀글 여부
            $table->timestamp('published_at')->nullable();  // 발행 시간
            $table->timestamps();
            $table->softDeletes();  // 소프트 삭제 지원

            // 외래 키 제약 조건
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // 인덱스
            $table->index(['board_id', 'created_at']);
            $table->index('view_count');
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
