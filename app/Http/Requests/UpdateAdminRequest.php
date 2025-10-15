<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UpdateAdminRequest extends FormRequest
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
        $adminId = $this->route('admin');
        
        // 라우트 모델 바인딩으로 User 인스턴스가 전달된 경우
        if ($adminId instanceof User) {
            $adminId = $adminId->id;
        }

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($adminId)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'boolean',
        ];
    }

    /**
     * 유효성 검사 메시지를 정의합니다.
     */
    public function messages(): array
    {
        return AdminValidationMessages::getUpdateMessages();
    }
}
