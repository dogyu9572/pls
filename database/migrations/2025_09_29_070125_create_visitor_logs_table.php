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
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->comment('방문자 IP 주소');
            $table->text('user_agent')->nullable()->comment('브라우저 정보');
            $table->string('page_url', 500)->comment('방문한 페이지 URL');
            $table->string('referer', 500)->nullable()->comment('이전 페이지 URL');
            $table->string('session_id', 100)->nullable()->comment('세션 ID');
            $table->boolean('is_unique')->default(false)->comment('고유 방문자 여부');
            $table->timestamps();
            
            // 인덱스
            $table->index(['created_at']);
            $table->index(['ip_address', 'created_at']);
            $table->index(['is_unique', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
