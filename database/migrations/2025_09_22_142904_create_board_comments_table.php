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
        Schema::create('board_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id'); // 게시글 ID (board_posts 테이블 참조)
            $table->unsignedBigInteger('parent_id')->nullable(); // 부모 댓글 ID (대댓글용)
            $table->unsignedBigInteger('user_id')->nullable(); // 작성자 ID (로그인한 경우)
            $table->string('author_name'); // 작성자 이름
            $table->string('password')->nullable(); // 비회원 댓글 비밀번호
            $table->text('content'); // 댓글 내용
            $table->integer('depth')->default(0); // 댓글 깊이 (대댓글용)
            $table->boolean('is_secret')->default(false); // 비밀댓글 여부
            $table->timestamps();
            $table->softDeletes(); // 소프트 삭제 지원

            // 인덱스
            $table->index(['post_id', 'created_at']);
            $table->index(['parent_id']);

            // 외래 키 제약 조건
            $table->foreign('parent_id')->references('id')->on('board_comments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_comments');
    }
};
