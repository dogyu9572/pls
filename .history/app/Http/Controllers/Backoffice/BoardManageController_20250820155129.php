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
            $this->boardSkinCopyService->copySkinToBoard($skin->directory, $board->table_name);
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
            'table_name' => ['required', 'alpha_dash', 'max:50', Rule::unique('boards')->ignore($board->id)],
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
            $this->boardSkinCopyService->deleteBoardSkin($board->table_name);
        } catch (\Exception $e) {
            // 스킨 파일 삭제 실패는 로그만 남기고 계속 진행
            Log::warning('게시판 스킨 파일 삭제 실패', [
                'board_table_name' => $board->table_name,
                'error' => $e->getMessage()
            ]);
        }
        
        // 게시판에 연결된 게시글, 설정 등이 함께 삭제되도록 관계 설정 필요
        $board->delete();

        return redirect()->route('backoffice.boards.index')
            ->with('success', '게시판이 성공적으로 삭제되었습니다.');
    }
    
    /**
     * 고유한 table_name을 생성합니다.
     */
    private function generateUniqueTableName($name)
    {
        // 한글과 특수문자를 제거하고 영문, 숫자, 하이픈만 남김
        $tableName = Str::slug($name);
        
        // table_name이 비어있으면 기본값 사용
        if (empty($tableName)) {
            $tableName = 'board-' . time();
        }
        
        // 중복 확인 및 수정 (삭제된 데이터 제외)
        $originalTableName = $tableName;
        $counter = 1;
        
        while (!Board::isTableNameAvailable($tableName)) {
            $tableName = $originalTableName . '-' . $counter;
            $counter++;
        }
        
        return $tableName;
    }
}
