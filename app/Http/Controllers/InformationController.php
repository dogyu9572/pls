<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * CEO 인사말 페이지
     */
    public function ceoMessage()
    {
        return view('information.ceo-message', [
            'gNum' => '01',
            'sNum' => '01',
            'gName' => '기업정보',
            'sName' => 'CEO 인사말'
        ]);
    }

    /**
     * 회사소개 페이지
     */
    public function aboutCompany()
    {
        return view('information.about-company', [
            'gNum' => '01',
            'sNum' => '02',
            'gName' => '기업정보',
            'sName' => '회사소개'
        ]);
    }

    /**
     * 회사연혁 페이지
     */
    public function history()
    {
        return view('information.history', [
            'gNum' => '01',
            'sNum' => '03',
            'gName' => '기업정보',
            'sName' => '회사연혁'
        ]);
    }

    /**
     * 품질/환경경영 페이지
     */
    public function qualityEnvironmental()
    {
        return view('information.quality-environmental', [
            'gNum' => '01',
            'sNum' => '04',
            'gName' => '기업정보',
            'sName' => '품질/환경경영'
        ]);
    }

    /**
     * 안전/보건경영 페이지
     */
    public function safetyHealth()
    {
        return view('information.safety-health', [
            'gNum' => '01',
            'sNum' => '05',
            'gName' => '기업정보',
            'sName' => '안전/보건경영'
        ]);
    }

    /**
     * 윤리경영 페이지
     */
    public function ethical()
    {
        return view('information.ethical', [
            'gNum' => '01',
            'sNum' => '06',
            'gName' => '기업정보',
            'sName' => '윤리경영'
        ]);
    }
}
