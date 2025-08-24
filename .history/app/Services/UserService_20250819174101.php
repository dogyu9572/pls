<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * 현재 로그인한 사용자를 가져옵니다.
     */
    public function getCurrentUser(): User
    {
        return Auth::user();
    }

    /**
     * 사용자 프로필을 업데이트합니다.
     */
    public function updateProfile(User $user, array $data): bool
    {
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        return $user->save();
    }
}
