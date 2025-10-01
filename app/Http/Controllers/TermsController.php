<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsController extends Controller
{
    /**
     * 개인정보처리방침
     */
    public function privacyPolicy()
    {
        return view('terms.privacy-policy', [
            'gNum' => '00',
            'gName' => '개인정보처리방침'
        ]);
    }

    /**
     * 이메일 무단수집 거부
     */
    public function email()
    {
        return view('terms.email', [
            'gNum' => '00',
            'gName' => '이메일 무단수집 거부'
        ]);
    }

    /**
     * 피엘에스 윤리규범
     */
    public function ethic()
    {
        return view('terms.ethic', [
            'gNum' => '00',
            'gName' => '피엘에스 윤리규범'
        ]);
    }

    /**
     * 내부신고제도 운영규정
     */
    public function internalReporting()
    {
        return view('terms.internal-reporting', [
            'gNum' => '00',
            'gName' => '내부신고제도 운영규정'
        ]);
    }
}
