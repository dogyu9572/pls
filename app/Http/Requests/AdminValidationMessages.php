<?php

namespace App\Http\Requests;

/**
 * 관리자 관련 유효성 검사 메시지 공통 클래스
 */
class AdminValidationMessages
{
    /**
     * 공통 유효성 검사 메시지
     */
    public static function getCommonMessages(): array
    {
        return [
            'login_id.unique' => '이미 사용 중인 로그인 ID입니다.',
            'login_id.max' => '로그인 ID는 255자를 초과할 수 없습니다.',
            'name.required' => '이름을 입력해주세요.',
            'name.max' => '이름은 255자를 초과할 수 없습니다.',
            'email.required' => '이메일을 입력해주세요.',
            'email.email' => '올바른 이메일 형식이 아닙니다.',
            'email.unique' => '이미 사용 중인 이메일입니다.',
            'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
            'department.max' => '부서명은 255자를 초과할 수 없습니다.',
            'position.max' => '직위는 255자를 초과할 수 없습니다.',
            'contact.max' => '연락처는 50자를 초과할 수 없습니다.',
            'permissions.array' => '권한 설정이 올바르지 않습니다.',
        ];
    }

    /**
     * 생성 시 추가 메시지
     */
    public static function getStoreMessages(): array
    {
        return array_merge(self::getCommonMessages(), [
            'password.required' => '비밀번호를 입력해주세요.',
        ]);
    }

    /**
     * 수정 시 추가 메시지
     */
    public static function getUpdateMessages(): array
    {
        return self::getCommonMessages();
    }
}
