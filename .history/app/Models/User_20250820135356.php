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
}
