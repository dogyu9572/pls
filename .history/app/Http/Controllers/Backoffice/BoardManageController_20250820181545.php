<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BoardSkin;
use App\Models\BoardSetting;
use App\Services\BoardSkinCopyService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class BoardManageController extends BaseController
{
    protected $boardSkinCopyService;
    
    public function __construct(BoardSkinCopyService $boardSkinCopyService)
    {
        $this->boardSkinCopyService = $boardSkinCopyService;
    }
    
    /**
     * 게시판 목록을 표시합니다.
     */
    public function index()
    {
        $boards = Board::with('skin')->orderBy('created_at', 'desc')->paginate(10);
        return $this->view('backoffice.boards.index', compact('boards'));
    }

    /**
     * 게시판 생성 폼을 표시합니다.
     */
    public function create()
    {
        $skins = BoardSkin::where('is_active', true)->get();
        return $this->view('backoffice.boards.create', compact('skins'));
    }

    /**
     * 새 게시판을 저장합니다.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'slug' => 'nullable|alpha_dash|max:50',
            'description' => 'nullable|max:255',
            'skin_id' => 'required|exists:board_skins,id',
            'is_active' => 'boolean',
            'list_count' => 'integer|min:5|max:100',
            'enable_notice' => 'boolean',
            'permission_read' => 'required|in:all,member,admin',
            'permission_write' => 'required|in:all,member,admin',
            'permission_comment' => 'required|in:all,member,admin',
        ]);
        
        // slug 중복 체크 (삭제된 데이터 제외)
        if (!empty($request->slug)) {
            if (!Board::isSlugAvailable($request->slug)) {
                $validator->errors()->add('slug', '이미 사용 중인 slug입니다.');
            }
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // slug 자동 생성 (비어있거나 중복일 경우)
        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        } else {
            // slug가 중복일 경우 자동으로 수정 (삭제된 데이터 제외)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (!Board::isSlugAvailable($data['slug'])) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // 게시판 생성
        $board = Board::create($data);

        // 스킨 파일들을 게시판별 디렉토리로 복사
        try {
            $skin = BoardSkin::find($request->skin_id);
            $this->boardSkinCopyService->copySkinToBoard($skin->directory, $board->slug);
        } catch (\Exception $e) {
            // 스킨 복사 실패 시 게시판 삭제
            $board->delete();
            return redirect()->back()
                ->withErrors(['skin_id' => '스킨 복사 중 오류가 발생했습니다: ' . $e->getMessage()])
                ->withInput();
        }

        // 기본 설정값 저장 (필요하다면)
        if ($request->has('settings')) {
            foreach ($request->settings as $key => $value) {
                $board->saveSetting($key, $value);
            }
        }

        // 게시판 생성 후 자동화 작업 실행
        try {
            // 동적 테이블 생성
            $this->createBoardTable($board);
            
            // 뷰 파일 생성
            $this->createBoardViews($board);
            
            // 마이그레이션 파일 생성
            $this->createBoardMigration($board);
            
        } catch (\Exception $e) {
            \Log::warning('게시판 자동 생성 실패', [
                'board_slug' => $board->slug,
                'error' => $e->getMessage()
            ]);
            
            // 실패 시 게시판 삭제
            $board->delete();
            return redirect()->back()
                ->withErrors(['error' => '게시판 생성 중 오류가 발생했습니다: ' . $e->getMessage()])
                ->withInput();
        }

        return redirect()->route('backoffice.boards.index')
            ->with('success', '게시판이 성공적으로 생성되었습니다.');
    }

    /**
     * 특정 게시판 정보를 표시합니다.
     */
    public function show(Board $board)
    {
        $board->load('skin');
        $settings = BoardSetting::getAllSettings($board->id);
        return $this->view('backoffice.boards.show', compact('board', 'settings'));
    }

    /**
     * 게시판 수정 폼을 표시합니다.
     */
    public function edit(Board $board)
    {
        $skins = BoardSkin::where('is_active', true)->get();
        $settings = BoardSetting::getAllSettings($board->id);
        return $this->view('backoffice.boards.edit', compact('board', 'skins', 'settings'));
    }

    /**
     * 게시판 정보를 업데이트합니다.
     */
    public function update(Request $request, Board $board)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'slug' => 'required|alpha_dash|max:50', // slug는 수정 불가능하므로 unique 검사 제거
            'description' => 'nullable|max:255',
            'skin_id' => 'required|exists:board_skins,id',
            'is_active' => 'boolean',
            'list_count' => 'integer|min:5|max:100',
            'enable_notice' => 'boolean',
            'permission_read' => 'required|in:all,member,admin',
            'permission_write' => 'required|in:all,member,admin',
            'permission_comment' => 'required|in:all,member,admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // 게시판 정보 업데이트
        $board->update($request->all());

        // 설정값 업데이트 (필요하다면)
        if ($request->has('settings')) {
            foreach ($request->settings as $key => $value) {
                $board->saveSetting($key, $value);
            }
        }

        return redirect()->route('backoffice.boards.index')
            ->with('success', '게시판이 성공적으로 업데이트되었습니다.');
    }

    /**
     * 게시판을 삭제합니다.
     */
    public function destroy(Board $board)
    {
        // 게시판별 스킨 파일들 삭제
        try {
            $this->boardSkinCopyService->deleteBoardSkin($board->slug);
        } catch (\Exception $e) {
            // 스킨 파일 삭제 실패는 로그만 남기고 계속 진행
            \Log::warning('게시판 스킨 파일 삭제 실패', [
                'board_slug' => $board->slug,
                'error' => $e->getMessage()
            ]);
        }

        // 게시판별 뷰 파일들 삭제
        try {
            $this->deleteBoardViews($board);
        } catch (\Exception $e) {
            // 뷰 파일 삭제 실패는 로그만 남기고 계속 진행
            \Log::warning('게시판 뷰 파일 삭제 실패', [
                'board_slug' => $board->slug,
                'error' => $e->getMessage()
            ]);
        }
        
        // 게시판에 연결된 게시글, 설정 등이 함께 삭제되도록 관계 설정 필요
        $board->delete();

        return redirect()->route('backoffice.boards.index')
            ->with('success', '게시판이 성공적으로 삭제되었습니다.');
    }
    
    /**
     * 고유한 slug를 생성합니다.
     */
    private function generateUniqueSlug($name)
    {
        // 한글과 특수문자를 제거하고 영문, 숫자, 하이픈만 남김
        $slug = Str::slug($name);
        
        // slug가 비어있으면 기본값 사용
        if (empty($slug)) {
            $slug = 'board-' . time();
        }
        
        // 중복 확인 및 수정 (삭제된 데이터 제외)
        $originalSlug = $slug;
        $counter = 1;
        
        while (!Board::isSlugAvailable($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * 게시판용 동적 테이블을 생성합니다.
     */
    private function createBoardTable($board)
    {
        $tableName = 'board_' . $board->slug;
        
        // 테이블이 이미 존재하는지 확인
        if (Schema::hasTable($tableName)) {
            throw new \Exception("테이블 '{$tableName}'이 이미 존재합니다.");
        }
        
        // 동적으로 테이블 생성
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('작성자 ID');
            $table->string('author_name', 100)->comment('작성자명');
            $table->string('password', 255)->nullable()->comment('비밀번호');
            $table->string('title', 255)->comment('제목');
            $table->text('content')->comment('내용');
            $table->string('category', 50)->nullable()->comment('카테고리 분류');
            $table->json('attachments')->nullable()->comment('첨부파일 정보');
            $table->boolean('is_notice')->default(false)->comment('공지사항 여부');
            $table->boolean('is_secret')->default(false)->comment('비밀글 여부');
            $table->integer('view_count')->default(0)->comment('조회수');
            $table->timestamp('published_at')->nullable()->comment('발행일');
            $table->timestamps();
            $table->softDeletes();
            
            // 인덱스 추가
            $table->index(['user_id', 'created_at']);
            $table->index(['is_notice', 'created_at']);
            $table->index(['category', 'created_at']);
        });
        
        // boards 테이블에 테이블 생성 완료 표시
        $board->update(['table_created' => true]);
        
        \Log::info("게시판 테이블 생성 완료: {$tableName}");
    }

    /**
     * 게시판용 마이그레이션 파일을 자동 생성합니다.
     */
    private function createBoardMigration($board)
    {
        $tableName = 'board_' . $board->slug;
        $migrationName = 'create_' . $tableName . '_table';
        $fileName = date('Y_m_d_His') . '_' . $migrationName . '.php';
        $filePath = database_path('migrations/' . $fileName);
        
        $content = $this->getMigrationContent($tableName, $board);
        
        if (file_put_contents($filePath, $content) === false) {
            throw new \Exception("마이그레이션 파일 생성에 실패했습니다: {$filePath}");
        }
        
        \Log::info("게시판 마이그레이션 파일 생성 완료: {$fileName}");
    }

    /**
     * 게시판용 기본 뷰 파일들을 자동 생성합니다.
     */
    private function createBoardViews($board)
    {
        $viewPath = resource_path("views/backoffice/boards/{$board->slug}");
        
        // 디렉토리가 없으면 생성
        if (!file_exists($viewPath)) {
            mkdir($viewPath, 0755, true);
        }

        // index.blade.php 생성
        $this->createIndexView($board, $viewPath);
        
        // create.blade.php 생성
        $this->createCreateView($board, $viewPath);
        
        // edit.blade.php 생성
        $this->createEditView($board, $viewPath);
        
        // show.blade.php 생성
        $this->createShowView($board, $viewPath);
    }

    /**
     * 게시글 목록 뷰 생성
     */
    private function createIndexView($board, $viewPath)
    {
        $content = $this->getIndexViewContent($board);
        file_put_contents("{$viewPath}/index.blade.php", $content);
    }

    /**
     * 게시글 작성 뷰 생성
     */
    private function createCreateView($board, $viewPath)
    {
        $content = $this->getCreateViewContent($board);
        file_put_contents("{$viewPath}/create.blade.php", $content);
    }

    /**
     * 게시글 수정 뷰 생성
     */
    private function createEditView($board, $viewPath)
    {
        $content = $this->getEditViewContent($board);
        file_put_contents("{$viewPath}/edit.blade.php", $content);
    }

    /**
     * 게시글 상세보기 뷰 생성
     */
    private function createShowView($board, $viewPath)
    {
        $content = $this->getShowViewContent($board);
        file_put_contents("{$viewPath}/show.blade.php", $content);
    }

    /**
     * 게시글 목록 뷰 콘텐츠 생성
     */
    private function getIndexViewContent($board)
    {
        return <<<HTML
@extends('backoffice.layouts.app')

@section('title', '{$board->name} - 게시글 관리')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{$board->name} - 게시글 관리</h4>
                    <div>
                        <a href="{{ route('backoffice.boards.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> 게시판 목록
                        </a>
                        <a href="{{ route('backoffice.boards.posts.create', '{$board->slug}') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> 새 게시글
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="60">번호</th>
                                    <th width="80">구분</th>
                                    <th>제목</th>
                                    <th width="120">작성자</th>
                                    <th width="100">조회수</th>
                                    <th width="120">작성일</th>
                                    <th width="120">관리</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\$posts as \$post)
                                <tr>
                                    <td>{{ \$post->id }}</td>
                                    <td>
                                        @if(\$post->is_notice)
                                            <span class="badge bg-warning">공지</span>
                                        @else
                                            <span class="badge bg-secondary">일반</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('backoffice.boards.posts.show', ['{$board->slug}', \$post->id]) }}" class="text-decoration-none">
                                            {{ \$post->title }}
                                        </a>
                                    </td>
                                    <td>{{ \$post->user->name ?? '알 수 없음' }}</td>
                                    <td>{{ \$post->view_count ?? 0 }}</td>
                                    <td>{{ \$post->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('backoffice.boards.posts.edit', ['{$board->slug}', \$post->id]) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('backoffice.boards.posts.destroy', ['{$board->slug}', \$post->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('정말 이 게시글을 삭제하시겠습니까?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p>등록된 게시글이 없습니다.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(\$posts->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ \$posts->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
HTML;
    }

    /**
     * 게시글 작성 뷰 콘텐츠 생성
     */
    private function getCreateViewContent($board)
    {
        return <<<HTML
@extends('backoffice.layouts.app')

@section('title', '{$board->name} - 게시글 작성')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{$board->name} - 게시글 작성</h4>
                    <a href="{{ route('backoffice.boards.posts.index', '{$board->slug}') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> 목록으로
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('backoffice.boards.posts.store', '{$board->slug}') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">제목 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">내용 <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_notice" name="is_notice" value="1">
                                <label class="form-check-label" for="is_notice">
                                    공지사항으로 등록
                                </label>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> 저장
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
HTML;
    }

    /**
     * 게시글 수정 뷰 콘텐츠 생성
     */
    private function getEditViewContent($board)
    {
        return <<<HTML
@extends('backoffice.layouts.app')

@section('title', '{$board->name} - 게시글 수정')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{$board->name} - 게시글 수정</h4>
                    <a href="{{ route('backoffice.boards.posts.show', ['{$board->slug}', \$post->id]) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> 상세보기
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('backoffice.boards.posts.update', ['{$board->slug}', \$post->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">제목 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ \$post->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">내용 <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" required>{{ \$post->content }}</textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_notice" name="is_notice" value="1" {{ \$post->is_notice ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_notice">
                                    공지사항으로 등록
                                </label>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> 수정
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
HTML;
    }

    /**
     * 게시글 상세보기 뷰 콘텐츠 생성
     */
    private function getShowViewContent($board)
    {
        return <<<HTML
@extends('backoffice.layouts.app')

@section('title', '{$board->name} - 게시글 상세보기')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{$board->name} - 게시글 상세보기</h4>
                    <div>
                        <a href="{{ route('backoffice.boards.posts.index', '{$board->slug}') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> 목록으로
                        </a>
                        <a href="{{ route('backoffice.boards.posts.edit', ['{$board->slug}', \$post->id]) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> 수정
                        </a>
                        <form action="{{ route('backoffice.boards.posts.destroy', ['{$board->slug}', \$post->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('정말 이 게시글을 삭제하시겠습니까?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> 삭제
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title">{{ \$post->title }}</h5>
                        <div class="text-muted">
                            <small>
                                작성자: {{ \$post->user->name ?? '알 수 없음' }} | 
                                작성일: {{ \$post->created_at->format('Y-m-d H:i') }} | 
                                조회수: {{ \$post->view_count ?? 0 }}
                                @if(\$post->is_notice)
                                    | <span class="badge bg-warning">공지</span>
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="card-text">
                        {!! nl2br(e(\$post->content)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
HTML;
    }

    /**
     * 게시판별 뷰 파일들을 삭제합니다.
     */
    private function deleteBoardViews($board)
    {
        $viewPath = resource_path("views/backoffice/boards/{$board->slug}");
        
        // 디렉토리가 존재하면 삭제
        if (file_exists($viewPath) && is_dir($viewPath)) {
            // 디렉토리 내의 모든 파일 삭제
            $files = glob("{$viewPath}/*");
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            
            // 빈 디렉토리 삭제
            rmdir($viewPath);
            
            \Log::info('게시판 뷰 파일 삭제 완료', [
                'board_slug' => $board->slug,
                'view_path' => $viewPath
            ]);
        }
    }
}
