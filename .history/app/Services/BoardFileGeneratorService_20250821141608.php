<?php

namespace App\Services;

use App\Models\Board;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class BoardFileGeneratorService
{
    /**
     * 게시판 관련 파일들을 생성합니다.
     */
    public function generateBoardFiles(Board $board): bool
    {
        try {
            // 동적 테이블 생성
            $this->createBoardTable($board);

            // 뷰 파일 생성
            $this->createBoardViews($board);

            // 마이그레이션 파일 생성
            $this->createBoardMigration($board);

            return true;
        } catch (\Exception $e) {
            Log::error('게시판 파일 생성 실패', [
                'board_slug' => $board->slug,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * 게시판 테이블을 생성합니다.
     */
    private function createBoardTable(Board $board): void
    {
        $tableName = 'board_' . $board->slug;

        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->string('author_name');
                $table->string('password')->nullable();
                $table->boolean('is_notice')->default(false);
                $table->boolean('is_secret')->default(false);
                $table->string('category')->nullable();
                $table->json('attachments')->nullable();
                $table->integer('view_count')->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['is_notice', 'created_at']);
                $table->index(['category', 'created_at']);
            });
        }
    }

    /**
     * 게시판 뷰 파일을 생성합니다.
     */
    private function createBoardViews(Board $board): void
    {
        $viewPath = resource_path("views/boards/{$board->slug}");
        
        if (!File::exists($viewPath)) {
            File::makeDirectory($viewPath, 0755, true);
        }

        // 기본 뷰 파일들 생성
        $this->createViewFile($viewPath, 'index.blade.php', $this->getIndexViewContent($board));
        $this->createViewFile($viewPath, 'create.blade.php', $this->getCreateViewContent($board));
        $this->createViewFile($viewPath, 'show.blade.php', $this->getShowViewContent($board));
        $this->createViewFile($viewPath, 'edit.blade.php', $this->getEditViewContent($board));
    }

    /**
     * 게시판 마이그레이션 파일을 생성합니다.
     */
    private function createBoardMigration(Board $board): void
    {
        $migrationPath = database_path('migrations');
        $timestamp = now()->format('Y_m_d_His');
        $fileName = "{$timestamp}_create_board_{$board->slug}_table.php";
        $filePath = $migrationPath . '/' . $fileName;

        $content = $this->getMigrationContent($board);
        File::put($filePath, $content);
    }

    /**
     * 뷰 파일을 생성합니다.
     */
    private function createViewFile(string $path, string $filename, string $content): void
    {
        $filePath = $path . '/' . $filename;
        
        if (!File::exists($filePath)) {
            File::put($filePath, $content);
        }
    }

    /**
     * index 뷰 내용을 생성합니다.
     */
    private function getIndexViewContent(Board $board): string
    {
        return "@extends('layouts.app')

@section('title', '{$board->name}')

@section('content')
<div class=\"container\">
    <h1>{$board->name}</h1>
    <!-- 게시글 목록 내용 -->
</div>
@endsection";
    }

    /**
     * create 뷰 내용을 생성합니다.
     */
    private function getCreateViewContent(Board $board): string
    {
        return "@extends('layouts.app')

@section('title', '{$board->name}')

@section('content')
<div class=\"container\">
    <h1>게시글 작성</h1>
    <!-- 게시글 작성 폼 -->
</div>
@endsection";
    }

    /**
     * show 뷰 내용을 생성합니다.
     */
    private function getShowViewContent(Board $board): string
    {
        return "@extends('layouts.app')

@section('title', '{$board->name}')

@section('content')
<div class=\"container\">
    <h1>게시글 상세</h1>
    <!-- 게시글 상세 내용 -->
</div>
@endsection";
    }

    /**
     * edit 뷰 내용을 생성합니다.
     */
    private function getEditViewContent(Board $board): string
    {
        return "@extends('layouts.app')

@section('title', '{$board->name}')

@section('content')
<div class=\"container\">
    <h1>게시글 수정</h1>
    <!-- 게시글 수정 폼 -->
</div>
@endsection";
    }

    /**
     * 마이그레이션 내용을 생성합니다.
     */
    private function getMigrationContent(Board $board): string
    {
        return "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('board_{$board->slug}', function (Blueprint \$table) {
            \$table->id();
            \$table->string('title');
            \$table->text('content');
            \$table->string('author_name');
            \$table->string('password')->nullable();
            \$table->boolean('is_notice')->default(false);
            \$table->boolean('is_secret')->default(false);
            \$table->string('category')->nullable();
            \$table->json('attachments')->nullable();
            \$table->integer('view_count')->default(0);
            \$table->timestamps();
            \$table->softDeletes();

            \$table->index(['is_notice', 'created_at']);
            \$table->index(['category', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_{$board->slug}');
    }
};";
    }
}
