<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardSkinRequest extends FormRequest
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
            'name' => 'required|max:100',
            'directory' => 'required|alpha_dash|max:50|unique:board_skins',
            'description' => 'nullable|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'options' => 'nullable|json',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    /**
     * 유효성 검사 메시지를 정의합니다.
     */
    public function messages(): array
    {
        return [
            'name.required' => '스킨 이름을 입력해주세요.',
            'name.max' => '스킨 이름은 100자를 초과할 수 없습니다.',
            'directory.required' => '디렉토리명을 입력해주세요.',
            'directory.alpha_dash' => '디렉토리명은 영문, 숫자, 하이픈, 언더스코어만 사용 가능합니다.',
            'directory.max' => '디렉토리명은 50자를 초과할 수 없습니다.',
            'directory.unique' => '이미 사용 중인 디렉토리명입니다.',
            'description.max' => '설명은 255자를 초과할 수 없습니다.',
            'thumbnail.image' => '썸네일은 이미지 파일이어야 합니다.',
            'thumbnail.mimes' => '썸네일은 jpeg, png, jpg, gif 형식만 지원합니다.',
            'thumbnail.max' => '썸네일은 2MB 이하여야 합니다.',
            'options.json' => '옵션은 올바른 JSON 형식이어야 합니다.',
        ];
    }
}
