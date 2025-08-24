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
        $viewPath = resource_path("views/backoffice/board-posts/{$board->slug}");
        
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
<div class=\"board-container\">
    <div class=\"board-header\">
        <h2>{$board->name}</h2>
        <p>{$board->description}</p>
    </div>

    <div class=\"board-search\">
        <form action=\"{{ route('boards.index', '{$board->slug}') }}\" method=\"GET\">
            <select name=\"search_field\" class=\"board-form-control\">
                <option value=\"title\" {{ request('search_field') == 'title' ? 'selected' : '' }}>제목</option>
                <option value=\"content\" {{ request('search_field') == 'content' ? 'selected' : '' }}>내용</option>
                <option value=\"author\" {{ request('search_field') == 'author' ? 'selected' : '' }}>작성자</option>
            </select>
            <input type=\"text\" name=\"search_query\" value=\"{{ request('search_query') }}\" class=\"board-form-control\" placeholder=\"검색어를 입력하세요\">
            <button type=\"submit\" class=\"btn btn-primary\">검색</button>
        </form>
    </div>

    <div class=\"board-list\">
        <table class=\"board-table\">
            <thead>
                <tr>
                    <th>번호</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>작성일</th>
                    <th>조회</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\$posts as \$post)
                <tr>
                    <td>{{ \$post->id }}</td>
                    <td>
                        <a href=\"{{ route('boards.show', ['{$board->slug}', \$post->id]) }}\" class=\"board-post-title-link\">
                            @if(\$post->is_notice)
                                <span class=\"notice-badge\">[공지]</span>
                            @endif
                            {{ \$post->title }}
                            @if(\$post->comments_count > 0)
                                <span class=\"comment-count\">[{{ \$post->comments_count }}]</span>
                            @endif
                        </a>
                    </td>
                    <td>{{ \$post->author_name }}</td>
                    <td>{{ \$post->created_at->format('Y-m-d') }}</td>
                    <td>{{ \$post->view_count }}</td>
                </tr>
                @endforeach

                @if(\$posts->isEmpty())
                <tr>
                    <td colspan=\"5\" class=\"empty-list\">게시물이 없습니다.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class=\"board-pagination\">
        {{ \$posts->links() }}
    </div>

    <div class=\"board-buttons\">
        @if(auth()->check() || '{$board->permission_write}' == 'all')
            <a href=\"{{ route('boards.create', '{$board->slug}') }}\" class=\"btn btn-success\">
                <i class=\"fas fa-plus\"></i> 글쓰기
            </a>
        @endif
    </div>
</div>
@endsection";
    }

    /**
     * create 뷰 내용을 생성합니다.
     */
    private function getCreateViewContent(Board $board): string
    {
        return "@extends('layouts.app')

@section('title', '{$board->name} - 게시글 작성')

@section('content')
<div class=\"board-container\">
    <div class=\"board-header\">
        <h2>{$board->name} - 게시글 작성</h2>
        <a href=\"{{ route('boards.index', '{$board->slug}') }}\" class=\"btn btn-secondary\">
            <i class=\"fas fa-arrow-left\"></i> 목록으로
        </a>
    </div>

    <div class=\"board-card\">
        <div class=\"board-card-body\">
            <form action=\"{{ route('boards.store', '{$board->slug}') }}\" method=\"POST\" enctype=\"multipart/form-data\">
                @csrf
                
                <div class=\"board-form-group\">
                    <label for=\"title\" class=\"board-form-label\">제목 <span class=\"required\">*</span></label>
                    <input type=\"text\" class=\"board-form-control\" id=\"title\" name=\"title\" required>
                </div>

                <div class=\"board-form-group\">
                    <label for=\"content\" class=\"board-form-label\">내용 <span class=\"required\">*</span></label>
                    <textarea class=\"board-form-control board-form-textarea\" id=\"content\" name=\"content\" rows=\"15\" required></textarea>
                </div>

                <div class=\"board-form-group\">
                    <label for=\"category\" class=\"board-form-label\">카테고리</label>
                    <select class=\"board-form-control\" id=\"category\" name=\"category\">
                        <option value=\"\">카테고리를 선택하세요</option>
                        <option value=\"일반\">일반</option>
                        <option value=\"공지\">공지</option>
                        <option value=\"안내\">안내</option>
                    </select>
                </div>

                <div class=\"board-form-actions\">
                    <button type=\"submit\" class=\"btn btn-primary\">
                        <i class=\"fas fa-save\"></i> 저장
                    </button>
                    <a href=\"{{ route('boards.index', '{$board->slug}') }}\" class=\"btn btn-secondary\">취소</a>
                </div>
            </form>
        </div>
    </div>
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
