<?php

namespace App\Http\Controllers;

use App\Models\Popup;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    /**
     * 팝업 표시 (일반팝업용)
     */
    public function show($id)
    {
        $popup = Popup::where('id', $id)
            ->where('is_active', true)
            ->where('popup_display_type', 'normal')
            ->first();

        if (!$popup) {
            abort(404, '팝업을 찾을 수 없습니다.');
        }

        // 게시기간 확인
        if ($popup->use_period) {
            $now = now();
            if ($popup->start_date && $now->lt($popup->start_date)) {
                abort(404, '아직 게시되지 않은 팝업입니다.');
            }
            if ($popup->end_date && $now->gt($popup->end_date)) {
                abort(404, '게시기간이 만료된 팝업입니다.');
            }
        }

        return view('popup.show', compact('popup'));
    }
}
