<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Board;

class CreateBoardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // 권한은 미들웨어에서 처리
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
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
            'custom_fields' => 'nullable|array',
            'custom_fields.*.name' => 'required|string|max:30|regex:/^[a-zA-Z0-9_]+$/',
            'custom_fields.*.label' => 'required|string|max:50',
            'custom_fields.*.type' => 'required|in:text,select,checkbox,radio,date,editor',
            'custom_fields.*.max_length' => 'nullable|integer|min:1|max:255',
            'custom_fields.*.required' => 'boolean',
            'custom_fields.*.options' => 'nullable|string|max:500',
            'custom_fields.*.placeholder' => 'nullable|string|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => '게시판 이름은 필수입니다.',
            'name.max' => '게시판 이름은 100자를 초과할 수 없습니다.',
            'slug.alpha_dash' => '게시판 식별자는 영문, 숫자, 하이픈, 언더스코어만 사용 가능합니다.',
            'slug.max' => '게시판 식별자는 50자를 초과할 수 없습니다.',
            'description.max' => '게시판 설명은 255자를 초과할 수 없습니다.',
            'skin_id.required' => '게시판 스킨을 선택해주세요.',
            'skin_id.exists' => '선택한 스킨이 존재하지 않습니다.',
            'list_count.integer' => '목록 개수는 숫자여야 합니다.',
            'list_count.min' => '목록 개수는 최소 5개 이상이어야 합니다.',
            'list_count.max' => '목록 개수는 최대 100개까지 가능합니다.',
            'permission_read.required' => '읽기 권한을 선택해주세요.',
            'permission_read.in' => '읽기 권한이 올바르지 않습니다.',
            'permission_write.required' => '쓰기 권한을 선택해주세요.',
            'permission_write.in' => '쓰기 권한이 올바르지 않습니다.',
            'permission_comment.required' => '댓글 권한을 선택해주세요.',
            'permission_comment.in' => '댓글 권한이 올바르지 않습니다.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // slug 중복 체크 (삭제된 데이터 제외)
            if (!empty($this->slug)) {
                if (!Board::isSlugAvailable($this->slug)) {
                    $validator->errors()->add('slug', '이미 사용 중인 게시판 식별자입니다.');
                }
            }
        });
    }
}
