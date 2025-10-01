<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrCenterController extends Controller
{
    /**
     * PLS 공지 목록 페이지
     */
    public function announcements()
    {
        return view('pr-center.announcements', [
            'gNum' => '04',
            'sNum' => '01',
            'gName' => '홍보센터',
            'sName' => 'PLS 공지'
        ]);
    }

    /**
     * PLS 소식 목록 페이지
     */
    public function news()
    {
        return view('pr-center.news', [
            'gNum' => '04',
            'sNum' => '02',
            'gName' => '홍보센터',
            'sName' => 'PLS 소식'
        ]);
    }

    /**
     * 오시는 길 페이지
     */
    public function location()
    {
        return view('pr-center.location', [
            'gNum' => '04',
            'sNum' => '03',
            'gName' => '홍보센터',
            'sName' => '오시는 길'
        ]);
    }
}
