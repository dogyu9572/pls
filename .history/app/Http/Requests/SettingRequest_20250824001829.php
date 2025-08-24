<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
        return [
            'site_title' => 'required|string|max:255',
            'site_url' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:255',
            'company_tel' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
            'footer_text' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'site_title.required' => '사이트 제목은 필수입니다.',
            'site_title.max' => '사이트 제목은 255자를 초과할 수 없습니다.',
            'site_url.required' => '사이트 URL은 필수입니다.',
            'site_url.max' => '사이트 URL은 255자를 초과할 수 없습니다.',
            'admin_email.required' => '관리자 이메일은 필수입니다.',
            'admin_email.email' => '올바른 이메일 형식이 아닙니다.',
            'admin_email.max' => '관리자 이메일은 255자를 초과할 수 없습니다.',
            'company_name.max' => '회사명은 255자를 초과할 수 없습니다.',
            'company_address.max' => '회사 주소는 255자를 초과할 수 없습니다.',
            'company_tel.max' => '회사 전화번호는 20자를 초과할 수 없습니다.',
            'logo.image' => '로고는 이미지 파일이어야 합니다.',
            'logo.mimes' => '로고는 JPEG, PNG, JPG, GIF 형식만 가능합니다.',
            'logo.max' => '로고 파일 크기는 2MB를 초과할 수 없습니다.',
            'favicon.image' => '파비콘은 이미지 파일이어야 합니다.',
            'favicon.mimes' => '파비콘은 ICO, PNG, JPG, JPEG 형식만 가능합니다.',
            'favicon.max' => '파비콘 파일 크기는 1MB를 초과할 수 없습니다.',
        ];
    }
}
