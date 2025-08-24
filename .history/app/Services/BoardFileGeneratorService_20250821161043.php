<?php

namespace App\Services;

use App\Models\Board;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

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
            Schema::create($tableName, function (Blueprint $table) use ($board) {
                // 기본 컬럼들 (모든 게시판 공통)
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('title');
                $table->text('content');
                $table->string('author_name');
                $table->string('password')->nullable();
                $table->boolean('is_notice')->default(false);
                $table->boolean('is_secret')->default(false);
                $table->string('category')->nullable();
                $table->json('attachments')->nullable();
                $table->integer('view_count')->default(0);
                
                // 커스텀 필드들 (JSON으로 저장)
                $table->json('custom_fields')->nullable();
                
                // 갤러리 전용 컬럼들 (사용하지 않으면 NULL)
                $table->string('thumbnail')->nullable(); // 썸네일 이미지 경로
                $table->json('images')->nullable(); // 이미지 갤러리 (여러 장)
                $table->string('image_alt')->nullable(); // 이미지 대체 텍스트
                
                $table->timestamps();
                $table->softDeletes();

                // 인덱스
                $table->index(['is_notice', 'created_at']);
                $table->index(['category', 'created_at']);
                $table->index(['user_id', 'created_at']);
                $table->index(['thumbnail']); // 갤러리 검색용
            });
        }
    }

    /**
     * 게시판 뷰 파일을 생성합니다.
     */
    private function createBoardViews(Board $board): void
    {
        $sourcePath = resource_path('views/backoffice/board_skins/default');
        $targetPath = resource_path("views/backoffice/board-posts/{$board->slug}");
        
        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        // default 스킨의 모든 파일을 복사
        $this->copySkinFiles($sourcePath, $targetPath, $board);
    }

    /**
     * 스킨 파일들을 복사하고 변수를 치환합니다.
     */
    private function copySkinFiles(string $sourcePath, string $targetPath, Board $board): void
    {
        $files = File::glob($sourcePath . '/*.blade.php');
        
        foreach ($files as $file) {
            $filename = basename($file);
            $content = File::get($file);
            
            // 변수 치환
            $content = $this->replaceBoardVariables($content, $board);
            
            // 새 위치에 저장
            File::put($targetPath . '/' . $filename, $content);
        }
    }

    /**
     * 템플릿의 변수들을 실제 게시판 정보로 치환합니다.
     */
    private function replaceBoardVariables(string $content, Board $board): string
    {
        // 이제 템플릿 자체가 변수화되었으므로 단순한 치환만 필요
        $replacements = [
            // 기본 변수 치환 (필요한 경우에만)
            '{{ $board->name ?? \'게시판\' }}' => $board->name,
            '{{ $board->slug ?? \'notice\' }}' => $board->slug,
        ];
        
        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * 게시판 관련 리소스들을 삭제합니다.
     */
    public function deleteBoardResources(Board $board): bool
    {
        try {
            // 1. 뷰 파일들 삭제
            $this->deleteBoardViews($board);

            // 2. 동적 테이블 삭제
            $this->deleteBoardTable($board);

            // 3. 마이그레이션 파일 삭제
            $this->deleteBoardMigration($board);

            return true;
        } catch (\Exception $e) {
            Log::error('게시판 리소스 삭제 실패', [
                'board_slug' => $board->slug,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * 게시판 뷰 파일들을 삭제합니다.
     */
    private function deleteBoardViews(Board $board): void
    {
        $viewPath = resource_path("views/backoffice/board-posts/{$board->slug}");
        
        if (File::exists($viewPath)) {
            File::deleteDirectory($viewPath);
            Log::info('게시판 뷰 파일 삭제 완료', ['path' => $viewPath]);
        }
    }

    /**
     * 게시판 테이블을 삭제합니다.
     */
    private function deleteBoardTable(Board $board): void
    {
        $tableName = 'board_' . $board->slug;

        if (Schema::hasTable($tableName)) {
            Schema::dropIfExists($tableName);
            Log::info('게시판 테이블 삭제 완료', ['table' => $tableName]);
        }
    }

    /**
     * 게시판 마이그레이션 파일을 삭제합니다.
     */
    private function deleteBoardMigration(Board $board): void
    {
        $migrationPath = database_path('migrations');
        $pattern = "*_create_board_{$board->slug}_table.php";
        $migrationFiles = File::glob($migrationPath . '/' . $pattern);

        foreach ($migrationFiles as $file) {
            File::delete($file);
            Log::info('게시판 마이그레이션 파일 삭제 완료', ['file' => $file]);
        }
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
