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
        Schema::create('board_notice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('작성자 ID');
            $table->string('author_name', 100)->comment('작성자명');
            $table->string('password', 255)->nullable()->comment('비밀번호');
            $table->string('title', 255)->comment('제목');
            $table->text('content')->comment('내용');
            $table->string('category', 50)->nullable()->comment('카테고리 분류');
            $table->json('attachments')->nullable()->comment('첨부파일 정보');
            $table->boolean('is_notice')->default(false)->comment('공지사항 여부');
            $table->boolean('is_secret')->default(false)->comment('비밀글 여부');
            $table->integer('view_count')->default(0)->comment('조회수');
            $table->timestamp('published_at')->nullable()->comment('발행일');
            $table->timestamps();
            $table->softDeletes();
            
            // 인덱스 추가
            $table->index(['user_id', 'created_at']);
            $table->index(['is_notice', 'created_at']);
            $table->index(['category', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_notice');
    }
};