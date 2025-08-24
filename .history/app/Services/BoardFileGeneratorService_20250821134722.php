<?php

namespace App\Services;

use App\Models\Board;
use App\Models\BoardSkin;
use App\Services\BoardSkinCopyService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;

class BoardFileGeneratorService
{
    protected $boardSkinCopyService;

    public function __construct(BoardSkinCopyService $boardSkinCopyService)
    {
        $this->boardSkinCopyService = $boardSkinCopyService;
    }

    /**
     * 게시판 생성을 위한 모든 파일을 생성합니다.
     */
    public function generateBoardFiles(Board $board): void
    {
        try {
            // 스킨 파일들을 게시판별 디렉토리로 복사
            $this->copySkinFiles($board);

            // 동적 테이블 생성
            $this->createBoardTable($board);

            // 뷰 파일 생성
            $this->createBoardViews($board);

            // 마이그레이션 파일 생성
            $this->createBoardMigration($board);

        } catch (\Exception $e) {
            Log::error('게시판 파일 생성 실패', [
                'board_slug' => $board->slug,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * 스킨 파일을 복사합니다.
     */
    private function copySkinFiles(Board $board): void
    {
        $skin = BoardSkin::find($board->skin_id);
        if (!$skin) {
            throw new \Exception('스킨을 찾을 수 없습니다.');
        }

        $this->boardSkinCopyService->copySkinToBoard($skin->directory, $board->slug);
    }

    /**
     * 게시판 테이블을 생성합니다.
     */
    private function createBoardTable(Board $board): void
    {
        $tableName = 'board_posts_' . $board->slug;

        if (Schema::hasTable($tableName)) {
            Log::warning('테이블이 이미 존재합니다.', ['table' => $tableName]);
            return;
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('author_name');
            $table->string('password')->nullable();
            $table->boolean('is_notice')->default(false);
            $table->boolean('is_secret')->default(false);
            $table->integer('view_count')->default(0);
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_notice', 'created_at']);
            $table->index('author_name');
        });
    }

    /**
     * 게시판 뷰 파일을 생성합니다.
     */
    private function createBoardViews(Board $board): void
    {
        // 뷰 파일 생성 로직
        // 실제 구현은 프로젝트 요구사항에 따라 달라질 수 있음
        Log::info('게시판 뷰 파일 생성', ['board_slug' => $board->slug]);
    }

    /**
     * 게시판 마이그레이션 파일을 생성합니다.
     */
    private function createBoardMigration(Board $board): void
    {
        // 마이그레이션 파일 생성 로직
        // 실제 구현은 프로젝트 요구사항에 따라 달라질 수 있음
        Log::info('게시판 마이그레이션 파일 생성', ['board_slug' => $board->slug]);
    }

    /**
     * 게시판 삭제 시 관련 파일들을 정리합니다.
     */
    public function cleanupBoardFiles(Board $board): void
    {
        try {
            // 테이블 삭제
            $this->dropBoardTable($board);

            // 스킨 파일 정리
            $this->cleanupSkinFiles($board);

            Log::info('게시판 파일 정리 완료', ['board_slug' => $board->slug]);
        } catch (\Exception $e) {
            Log::error('게시판 파일 정리 실패', [
                'board_slug' => $board->slug,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * 게시판 테이블을 삭제합니다.
     */
    private function dropBoardTable(Board $board): void
    {
        $tableName = 'board_posts_' . $board->slug;

        if (Schema::hasTable($tableName)) {
            Schema::dropIfExists($tableName);
        }
    }

    /**
     * 스킨 파일을 정리합니다.
     */
    private function cleanupSkinFiles(Board $board): void
    {
        // 스킨 파일 정리 로직
        // 실제 구현은 프로젝트 요구사항에 따라 달라질 수 있음
        Log::info('게시판 스킨 파일 정리', ['board_slug' => $board->slug]);
    }
}
