<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user');
        
        // 라우트 모델 바인딩으로 User 인스턴스가 전달된 경우
        if ($userId instanceof User) {
            $userId = $userId->id;
        }

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId)
            ],
            'login_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'boolean',
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
            'email.unique' => '이미 사용 중인 이메일입니다.',
            'login_id.unique' => '이미 사용 중인 아이디입니다.',
            'login_id.max' => '아이디는 255자를 초과할 수 없습니다.',
            'department.max' => '부서명은 255자를 초과할 수 없습니다.',
            'position.max' => '직위는 255자를 초과할 수 없습니다.',
            'contact.max' => '연락처는 50자를 초과할 수 없습니다.',
            'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
        ];
    }
}
