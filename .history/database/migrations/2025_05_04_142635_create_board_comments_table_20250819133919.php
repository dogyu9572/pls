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
            $table->unsignedBigInteger('post_id');  // 게시글 ID
            $table->unsignedBigInteger('parent_id')->nullable();  // 부모 댓글 ID (답글 기능)
            $table->unsignedBigInteger('user_id')->nullable();  // 작성자 ID (비회원 지원)
            $table->string('author_name');  // 작성자명 (비회원용)
            $table->string('password')->nullable();  // 비회원 비밀번호
            $table->text('content');  // 내용
            $table->integer('depth')->default(0);  // 댓글 깊이(계층)
            $table->boolean('is_secret')->default(false);  // 비밀 댓글 여부
            $table->timestamps();
            $table->softDeletes();  // 소프트 삭제 지원

            // 외래 키 제약 조건
            $table->foreign('post_id')->references('id')->on('board_posts')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('board_comments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // 인덱스
            $table->index(['post_id', 'created_at']);
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
