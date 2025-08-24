<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBoardPostRequest extends FormRequest
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
        $rules = [
            'title' => 'required|max:255',
            'content' => 'required',
        ];

        // 비회원인 경우 이름과 비밀번호 필수
        if (!Auth::check()) {
            $rules['author_name'] = 'required|max:50';
            $rules['password'] = 'required|min:4';
        }

        return $rules;
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
            'author_name.required' => '이름을 입력해주세요.',
            'author_name.max' => '이름은 50자를 초과할 수 없습니다.',
            'password.required' => '비밀번호를 입력해주세요.',
            'password.min' => '비밀번호는 최소 4자 이상이어야 합니다.',
        ];
    }
}
