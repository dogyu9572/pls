<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\CreateBoardRequest;
use App\Http\Requests\Backoffice\UpdateBoardRequest;
use App\Models\Board;
use App\Models\BoardSetting;
use App\Services\BoardService;
use App\Services\BoardFileGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BoardManageController extends BaseController
{
    protected $boardService;
    protected $boardFileGeneratorService;

    public function __construct(
        BoardService $boardService,
        BoardFileGeneratorService $boardFileGeneratorService
    ) {
        $this->boardService = $boardService;
        $this->boardFileGeneratorService = $boardFileGeneratorService;
    }

    /**
     * 게시판 목록을 표시합니다.
     */
    public function index()
    {
        $boards = $this->boardService->getBoards();
        return $this->view('backoffice.boards.index', compact('boards'));
    }

    /**
     * 게시판 생성 폼을 표시합니다.
     */
    public function create()
    {
        $skins = $this->boardService->getActiveSkins();
        return $this->view('backoffice.boards.create', compact('skins'));
    }

    /**
     * 새 게시판을 저장합니다.
     */
    public function store(CreateBoardRequest $request)
    {
        try {
            $data = $request->validated();
            
            // max_length를 한글 기준으로 변환 (한글 1글자 = 3바이트, 영문 1글자 = 1바이트)
            if (isset($data['custom_fields']) && is_array($data['custom_fields'])) {
                foreach ($data['custom_fields'] as $index => $field) {
                    if (isset($field['max_length']) && !empty($field['max_length']) && in_array($field['type'], ['text'])) {
                        // 사용자가 입력한 값을 한글 기준 글자 수로 변환
                        $data['custom_fields'][$index]['max_length'] = $field['max_length'] * 2;
                    }
                }
            }
            
            // 게시판 생성
            $board = $this->boardService->createBoard($data);

            // 스킨 파일들을 게시판별 디렉토리로 복사
            try {
                $skin = $board->skin;
                $this->boardFileGeneratorService->generateBoardFiles($board);
            } catch (\Exception $e) {
                // 스킨 복사 실패 시 게시판 삭제
                $this->boardService->deleteBoard($board);
                return redirect()->back()
                    ->withErrors(['skin_id' => '스킨 복사 중 오류가 발생했습니다: ' . $e->getMessage()])
                    ->withInput();
            }

            return redirect()->route('backoffice.boards.index')
                ->with('success', '게시판이 성공적으로 생성되었습니다.');

        } catch (\Exception $e) {
            Log::error('게시판 생성 실패', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return redirect()->back()
                ->withErrors(['error' => '게시판 생성 중 오류가 발생했습니다: ' . $e->getMessage()])
                ->withInput();
        }
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
        $skins = $this->boardService->getActiveSkins();
        $settings = BoardSetting::getAllSettings($board->id);
        return $this->view('backoffice.boards.edit', compact('board', 'skins', 'settings'));
    }

    /**
     * 게시판 정보를 업데이트합니다.
     */
    public function update(UpdateBoardRequest $request, Board $board)
    {
        try {
            $data = $request->validated();
            
            // max_length를 한글 기준으로 변환 (한글 1글자 = 3바이트, 영문 1글자 = 1바이트)
            if (isset($data['custom_fields']) && is_array($data['custom_fields'])) {
                foreach ($data['custom_fields'] as $index => $field) {
                    if (isset($field['max_length']) && !empty($field['max_length']) && in_array($field['type'], ['text'])) {
                        // 사용자가 입력한 값을 한글 기준 글자 수로 변환
                        $data['custom_fields'][$index]['max_length'] = $field['max_length'] * 2;
                    }
                }
            }
            
            $this->boardService->updateBoard($board, $data);

            return redirect()->route('backoffice.boards.index')
                ->with('success', '게시판이 성공적으로 업데이트되었습니다.');

        } catch (\Exception $e) {
            Log::error('게시판 수정 실패', [
                'board_id' => $board->id,
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return redirect()->back()
                ->withErrors(['error' => '게시판 수정 중 오류가 발생했습니다: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * 게시판을 삭제합니다.
     */
    public function destroy(Board $board)
    {
        try {
            if ($this->boardService->deleteBoard($board)) {
                return redirect()->route('backoffice.boards.index')
                    ->with('success', '게시판이 성공적으로 삭제되었습니다.');
            } else {
                return redirect()->back()
                    ->withErrors(['error' => '게시판 삭제에 실패했습니다.']);
            }
        } catch (\Exception $e) {
            Log::error('게시판 삭제 실패', [
                'board_id' => $board->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withErrors(['error' => '게시판 삭제 중 오류가 발생했습니다: ' . $e->getMessage()]);
        }
    }
}
