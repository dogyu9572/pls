<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckPasswordRequest extends FormRequest
{
    /**
     * 요청에 대한 권한을 확인합니다.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 유효성 검사 규칙을 정의합니다.
     */
    public function rules(): array
    {
        return [
            'password' => 'required',
        ];
    }

    /**
     * 유효성 검사 메시지를 정의합니다.
     */
    public function messages(): array
    {
        return [
            'password.required' => '비밀번호를 입력해주세요.',
        ];
    }
}
