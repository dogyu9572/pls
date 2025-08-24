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
        Schema::table('boards', function (Blueprint $table) {
            // slug 컬럼을 table_name으로 변경
            $table->renameColumn('slug', 'table_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boards', function (Blueprint $table) {
            // table_name 컬럼을 slug로 되돌리기
            $table->renameColumn('table_name', 'slug');
        });
    }
};
