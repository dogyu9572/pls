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
                $table->unsignedBigInteger('user_id')->nullable();  // 사용자 ID 추가
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
                $table->index(['user_id', 'created_at']);  // user_id 인덱스 추가
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
        $replacements = [
            '{{ $board->name ?? \'게시판\' }}' => $board->name,
            '{{ $board->slug ?? \'notice\' }}' => $board->slug,
            '{{ route(\'backoffice.board-posts.index\', $board->slug ?? \'notice\') }}' => route('backoffice.board-posts.index', $board->slug),
            '{{ route(\'backoffice.board-posts.create\', $board->slug ?? \'notice\') }}' => route('backoffice.board-posts.create', $board->slug),
            '{{ route(\'backoffice.board-posts.store\', $board->slug ?? \'notice\') }}' => route('backoffice.board-posts.store', $board->slug),
        ];
        
        return str_replace(array_keys($replacements), array_values($replacements), $content);
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
