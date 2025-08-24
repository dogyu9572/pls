<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * 요청에 대한 권한을 확인합니다.
     */
    public function authorize(): bool
    {
        return \Illuminate\Support\Facades\Auth::check();
    }

    /**
     * 유효성 검사 규칙을 정의합니다.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore(\Illuminate\Support\Facades\Auth::id())
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * 유효성 검사 메시지를 정의합니다.
     */
    public function messages(): array
    {
        return [
            'name.required' => '이름을 입력해주세요.',
            'name.max' => '이름은 255자를 초과할 수 없습니다.',
            'email.required' => '이메일을 입력해주세요.',
            'email.email' => '올바른 이메일 형식이 아닙니다.',
            'email.max' => '이메일은 255자를 초과할 수 없습니다.',
            'email.unique' => '이미 사용 중인 이메일입니다.',
            'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
        ];
    }
}
