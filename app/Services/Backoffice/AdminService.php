<?php

namespace App\Services\Backoffice;

use App\Models\User;
use App\Models\UserMenuPermission;
use App\Models\AdminMenu;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class AdminService
{
    /**
     * 관리자 목록을 가져옵니다.
     */
    public function getAdmins(): Collection
    {
        return User::whereIn('role', ['super_admin', 'admin'])->get();
    }

    /**
     * 필터링된 관리자 목록을 가져옵니다.
     */
    public function getAdminsWithFilters(\Illuminate\Http\Request $request)
    {
        $query = User::whereIn('role', ['super_admin', 'admin']);
        
        // 이름 검색
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        // 이메일 검색
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        
        // 권한 필터
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // 상태 필터
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        // 등록일 필터
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }
        
        // 목록 개수 설정
        $perPage = $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * 관리자를 생성합니다.
     */
    public function createAdmin(array $data): User
    {
        $adminData = [
            'login_id' => $data['login_id'] ?? null,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
            'is_active' => true,
            'department' => $data['department'] ?? null,
            'position' => $data['position'] ?? null,
            'contact' => $data['contact'] ?? null,
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
        $admin->department = $data['department'] ?? null;
        $admin->position = $data['position'] ?? null;
        $admin->contact = $data['contact'] ?? null;

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

    /**
     * 관리자의 메뉴 권한을 저장합니다.
     */
    public function saveMenuPermissions(int $userId, array $permissions): void
    {
        $user = User::findOrFail($userId);
        
        // 슈퍼 관리자는 권한 설정을 저장하지 않음 (항상 모든 권한)
        if ($user->isSuperAdmin()) {
            return;
        }

        foreach ($permissions as $menuId => $granted) {
            UserMenuPermission::updateOrCreate(
                [
                    'user_id' => $userId,
                    'menu_id' => $menuId
                ],
                [
                    'granted' => (bool) $granted
                ]
            );
        }
    }

    /**
     * 관리자의 메뉴 권한을 가져옵니다.
     */
    public function getMenuPermissions(int $userId): array
    {
        $user = User::findOrFail($userId);
        return $user->getAllMenuPermissions();
    }

    /**
     * 모든 메뉴 목록을 가져옵니다.
     */
    public function getAllMenus(): Collection
    {
        return AdminMenu::getPermissionMenuTree();
    }
}
