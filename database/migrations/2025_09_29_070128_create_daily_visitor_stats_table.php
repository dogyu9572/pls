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
        Schema::create('daily_visitor_stats', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date')->unique()->comment('방문 날짜');
            $table->integer('visitor_count')->default(0)->comment('일일 방문자 수');
            $table->integer('page_views')->default(0)->comment('일일 페이지뷰 수');
            $table->integer('unique_visitors')->default(0)->comment('일일 고유 방문자 수');
            $table->timestamps();
            
            // 인덱스
            $table->index(['visit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_visitor_stats');
    }
};
