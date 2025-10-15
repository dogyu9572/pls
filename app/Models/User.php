<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'login_id',
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
        'department',
        'position',
        'contact',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * 사용자가 관리자인지 확인
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * 사용자가 슈퍼 관리자인지 확인
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * 사용자가 활성화된 계정인지 확인
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * 로그인 ID로 사용자 찾기
     */
    public static function findByLoginId(string $loginId): ?self
    {
        return static::where('login_id', $loginId)->first();
    }

    /**
     * 활성화된 사용자만 조회하는 스코프
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 관리자만 조회하는 스코프
     */
    public function scopeAdmins($query)
    {
        return $query->whereIn('role', ['super_admin', 'admin']);
    }

    /**
     * 사용자의 메뉴 권한들과의 관계
     */
    public function menuPermissions()
    {
        return $this->hasMany(UserMenuPermission::class);
    }

    /**
     * 사용자가 접근 가능한 메뉴들
     */
    public function accessibleMenus()
    {
        return $this->belongsToMany(AdminMenu::class, 'user_menu_permissions', 'user_id', 'menu_id')
            ->wherePivot('granted', true)
            ->withPivot('granted')
            ->withTimestamps();
    }

    /**
     * 특정 메뉴에 대한 권한 확인
     */
    public function hasMenuPermission($menuId): bool
    {
        if ($this->isSuperAdmin()) {
            return true; // 슈퍼 관리자는 모든 메뉴 접근 가능
        }

        return $this->menuPermissions()
            ->where('menu_id', $menuId)
            ->where('granted', true)
            ->exists();
    }

    /**
     * 사용자의 모든 메뉴 권한 조회 (권한 부여 여부 포함)
     */
    public function getAllMenuPermissions(): array
    {
        // 슈퍼 관리자는 모든 메뉴에 권한이 있다고 반환
        if ($this->isSuperAdmin()) {
            $allMenus = \App\Models\AdminMenu::where('is_active', true)->get();
            $result = [];
            foreach ($allMenus as $menu) {
                $result[$menu->id] = true;
            }
            return $result;
        }

        $permissions = $this->menuPermissions()
            ->get()
            ->pluck('granted', 'menu_id')
            ->toArray();

        // 모든 메뉴에 대해 권한 정보 생성 (권한이 없는 메뉴는 false)
        $allMenus = \App\Models\AdminMenu::where('is_active', true)->get();
        $result = [];

        foreach ($allMenus as $menu) {
            $result[$menu->id] = $permissions[$menu->id] ?? false;
        }

        return $result;
    }
}
