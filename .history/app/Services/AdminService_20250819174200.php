<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class AdminService
{
    /**
     * 관리자 목록을 가져옵니다.
     */
    public function getAdmins(): Collection
    {
        return User::where('is_admin', true)
            ->orWhere('role', 'admin')
            ->get();
    }

    /**
     * 관리자를 생성합니다.
     */
    public function createAdmin(array $data): User
    {
        $adminData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => true,
            'role' => 'admin',
        ];

        return User::create($adminData);
    }

    /**
     * 관리자를 가져옵니다.
     */
    public function getAdmin(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * 관리자 정보를 업데이트합니다.
     */
    public function updateAdmin(User $admin, array $data): bool
    {
        $admin->name = $data['name'];
        $admin->email = $data['email'];

        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        return $admin->save();
    }

    /**
     * 관리자를 삭제합니다.
     */
    public function deleteAdmin(User $admin): bool
    {
        return $admin->delete();
    }
}
