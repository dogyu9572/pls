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
     * 모든 회원을 가져옵니다.
     */
    public function getAllUsers()
    {
        return User::latest()->paginate(15);
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

    /**
     * 회원 정보를 업데이트합니다.
     */
    public function updateUser(User $user, array $data): bool
    {
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if (isset($data['is_active'])) {
            $user->is_active = (bool) $data['is_active'];
        }

        return $user->save();
    }

    /**
     * 회원을 삭제합니다.
     */
    public function deleteUser(User $user): bool
    {
        // 관리자는 삭제할 수 없음
        if ($user->is_admin) {
            return false;
        }

        return $user->delete();
    }
}
