<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_title',
        'site_url',
        'admin_email',
        'company_name',
        'company_address',
        'company_tel',
        'logo_path',
        'favicon_path',
        'footer_text',
    ];

    /**
     * 설정 값을 키로 가져옵니다.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::first();
        return $setting ? ($setting->$key ?? $default) : $default;
    }

    /**
     * 기본 설정 데이터를 생성하거나 업데이트합니다.
     *
     * @param array $data
     * @return \App\Models\Setting
     */
    public static function updateOrCreateSettings(array $data)
    {
        $setting = self::first();

        if ($setting) {
            $setting->update($data);
        } else {
            $setting = self::create($data);
        }

        return $setting;
    }
}
