<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BoardSkin;
use App\Models\BoardSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BoardManageController extends BaseController
{
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
            'slug' => 'required|alpha_dash|max:50|unique:boards',
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

        // 게시판 생성
        $board = Board::create($request->all());

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
        return view('backoffice.boards.show', compact('board', 'settings'));
    }

    /**
     * 게시판 수정 폼을 표시합니다.
     */
    public function edit(Board $board)
    {
        $skins = BoardSkin::where('is_active', true)->get();
        $settings = BoardSetting::getAllSettings($board->id);
        return view('backoffice.boards.edit', compact('board', 'skins', 'settings'));
    }

    /**
     * 게시판 정보를 업데이트합니다.
     */
    public function update(Request $request, Board $board)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'slug' => ['required', 'alpha_dash', 'max:50', Rule::unique('boards')->ignore($board->id)],
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
        // 게시판에 연결된 게시글, 설정 등이 함께 삭제되도록 관계 설정 필요
        $board->delete();

        return redirect()->route('backoffice.boards.index')
            ->with('success', '게시판이 성공적으로 삭제되었습니다.');
    }
}
