<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 이 마이그레이션은 동적 테이블 생성을 위한 기본 구조만 제공합니다.
        // 실제 게시판별 테이블은 게시판 생성 시 동적으로 생성됩니다.
        
        // boards 테이블에 동적 테이블 생성 여부를 추적하는 컬럼 추가
        if (Schema::hasTable('boards')) {
            Schema::table('boards', function (Blueprint $table) {
                if (!Schema::hasColumn('boards', 'table_created')) {
                    $table->boolean('table_created')->default(false)->after('is_active')->comment('동적 테이블 생성 여부');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // boards 테이블에서 추가된 컬럼 제거
        if (Schema::hasTable('boards')) {
            Schema::table('boards', function (Blueprint $table) {
                if (Schema::hasColumn('boards', 'table_created')) {
                    $table->dropColumn('table_created');
                }
            });
        }
    }
};
