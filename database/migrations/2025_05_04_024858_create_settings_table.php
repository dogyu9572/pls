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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title')->nullable()->comment('사이트 타이틀명');
            $table->string('site_url')->nullable()->comment('사이트 URL');
            $table->string('admin_email')->nullable()->comment('관리자 이메일');
            $table->string('company_name')->nullable()->comment('회사명');
            $table->string('company_address')->nullable()->comment('회사 주소');
            $table->string('company_tel')->nullable()->comment('회사 연락처');
            $table->string('logo_path')->nullable()->comment('로고 경로');
            $table->string('favicon_path')->nullable()->comment('파비콘 경로');
            $table->text('footer_text')->nullable()->comment('푸터 텍스트');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
