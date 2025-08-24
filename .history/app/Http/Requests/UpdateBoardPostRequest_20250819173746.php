<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoardPostRequest extends FormRequest
{
    /**
     * 요청에 대한 권한을 확인합니다.
     */
    public function authorize(): bool
    {
        return true; // 컨트롤러에서 권한 체크
    }

    /**
     * 유효성 검사 규칙을 정의합니다.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'content' => 'required',
        ];
    }

    /**
     * 유효성 검사 메시지를 정의합니다.
     */
    public function messages(): array
    {
        return [
            'title.required' => '제목을 입력해주세요.',
            'title.max' => '제목은 255자를 초과할 수 없습니다.',
            'content.required' => '내용을 입력해주세요.',
        ];
    }
}
