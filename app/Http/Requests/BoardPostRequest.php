<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @method string route(string $param)
 */
class BoardPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_notice' => 'nullable|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240', // 10MB
            'sort_order' => 'nullable|integer|min:0'
        ];

        // 커스텀 필드 검증 규칙 추가
        $customRules = $this->getCustomFieldRules();
        
        return array_merge($rules, $customRules);
    }

    /**
     * 커스텀 필드 검증 규칙 생성
     */
    private function getCustomFieldRules(): array
    {
        $rules = [];
        
        // 게시판 정보 가져오기
        $slug = $this->route('slug');
        $board = \App\Models\Board::where('slug', $slug)->first();
        
        if (!$board || !$board->custom_fields_config) {
            return $rules;
        }

        foreach ($board->custom_fields_config as $fieldConfig) {
            $fieldName = $fieldConfig['name'];
            $fieldRules = [];
            
            // 필수 필드 검증
            if ($fieldConfig['required']) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }
            
            // 문자열 필드
            if ($fieldConfig['type'] === 'text' || $fieldConfig['type'] === 'textarea') {
                $fieldRules[] = 'string';
                
                // 최대 길이 검증
                if (isset($fieldConfig['max_length'])) {
                    $fieldRules[] = 'max:' . $fieldConfig['max_length'];
                }
            }
            
            // 숫자 필드
            if ($fieldConfig['type'] === 'number') {
                $fieldRules[] = 'numeric';
                
                // 최소값 검증
                if (isset($fieldConfig['min_value'])) {
                    $fieldRules[] = 'min:' . $fieldConfig['min_value'];
                }
                
                // 최대값 검증
                if (isset($fieldConfig['max_value'])) {
                    $fieldRules[] = 'max:' . $fieldConfig['max_value'];
                }
            }
            
            // 이메일 필드
            if ($fieldConfig['type'] === 'email') {
                $fieldRules[] = 'email';
            }
            
            // URL 필드
            if ($fieldConfig['type'] === 'url') {
                $fieldRules[] = 'url';
            }
            
            // 파일 필드
            if ($fieldConfig['type'] === 'file') {
                $fieldRules[] = 'file';
                
                // 파일 크기 제한
                if (isset($fieldConfig['max_size'])) {
                    $fieldRules[] = 'max:' . $fieldConfig['max_size'];
                }
                
                // 파일 타입 제한
                if (isset($fieldConfig['allowed_types'])) {
                    $fieldRules[] = 'mimes:' . $fieldConfig['allowed_types'];
                }
            }
            
            $rules["custom_field_{$fieldName}"] = $fieldRules;
        }
        
        return $rules;
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        $messages = [
            'title.required' => '제목은 필수 입력 항목입니다.',
            'title.max' => '제목은 최대 255자까지 입력 가능합니다.',
            'content.required' => '내용은 필수 입력 항목입니다.',
            'thumbnail.image' => '썸네일은 이미지 파일이어야 합니다.',
            'thumbnail.mimes' => '썸네일은 jpeg, png, jpg, gif 형식만 지원합니다.',
            'thumbnail.max' => '썸네일은 최대 5MB까지 업로드 가능합니다.',
            'attachments.*.file' => '첨부파일은 파일이어야 합니다.',
            'attachments.*.max' => '첨부파일은 최대 10MB까지 업로드 가능합니다.'
        ];

        // 커스텀 필드 에러 메시지 추가
        $customMessages = $this->getCustomFieldMessages();
        
        return array_merge($messages, $customMessages);
    }

    /**
     * 커스텀 필드 에러 메시지 생성
     */
    private function getCustomFieldMessages(): array
    {
        $messages = [];
        
        // 게시판 정보 가져오기
        $slug = $this->route('slug');
        $board = \App\Models\Board::where('slug', $slug)->first();
        
        if (!$board || !$board->custom_fields_config) {
            return $messages;
        }

        foreach ($board->custom_fields_config as $fieldConfig) {
            $fieldName = $fieldConfig['name'];
            $fieldLabel = $fieldConfig['label'] ?? $fieldName;
            
            // 필수 필드 메시지
            if ($fieldConfig['required']) {
                $messages["custom_field_{$fieldName}.required"] = "{$fieldLabel}은(는) 필수 입력 항목입니다.";
            }
            
            // 최대 길이 메시지
            if (isset($fieldConfig['max_length'])) {
                $messages["custom_field_{$fieldName}.max"] = "{$fieldLabel}은(는) 최대 {$fieldConfig['max_length']}자까지 입력 가능합니다.";
            }
            
            // 숫자 범위 메시지
            if ($fieldConfig['type'] === 'number') {
                if (isset($fieldConfig['min_value'])) {
                    $messages["custom_field_{$fieldName}.min"] = "{$fieldLabel}은(는) 최소 {$fieldConfig['min_value']} 이상이어야 합니다.";
                }
                if (isset($fieldConfig['max_value'])) {
                    $messages["custom_field_{$fieldName}.max"] = "{$fieldLabel}은(는) 최대 {$fieldConfig['max_value']} 이하여야 합니다.";
                }
            }
            
            // 이메일 메시지
            if ($fieldConfig['type'] === 'email') {
                $messages["custom_field_{$fieldName}.email"] = "{$fieldLabel}은(는) 유효한 이메일 형식이어야 합니다.";
            }
            
            // URL 메시지
            if ($fieldConfig['type'] === 'url') {
                $messages["custom_field_{$fieldName}.url"] = "{$fieldLabel}은(는) 유효한 URL 형식이어야 합니다.";
            }
            
            // 파일 메시지
            if ($fieldConfig['type'] === 'file') {
                $messages["custom_field_{$fieldName}.file"] = "{$fieldLabel}은(는) 파일이어야 합니다.";
                
                if (isset($fieldConfig['max_size'])) {
                    $messages["custom_field_{$fieldName}.max"] = "{$fieldLabel}은(는) 최대 {$fieldConfig['max_size']}KB까지 업로드 가능합니다.";
                }
                
                if (isset($fieldConfig['allowed_types'])) {
                    $messages["custom_field_{$fieldName}.mimes"] = "{$fieldLabel}은(는) {$fieldConfig['allowed_types']} 형식만 지원합니다.";
                }
            }
        }
        
        return $messages;
    }
}
