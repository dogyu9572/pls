<?php

namespace App\Services;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoardValidationService
{
    /**
     * 게시판 생성 유효성 검사를 수행합니다.
     */
    public function validateCreate(Request $request): array
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
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * 게시판 수정 유효성 검사를 수행합니다.
     */
    public function validateUpdate(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'slug' => 'required|alpha_dash|max:50',
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
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * slug 사용 가능 여부를 확인합니다.
     */
    public function isSlugAvailable(string $slug): bool
    {
        return Board::isSlugAvailable($slug);
    }
}
