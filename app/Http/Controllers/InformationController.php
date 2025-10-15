<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardPost;

class InformationController extends Controller
{
    /**
     * CEO 인사말 페이지
     */
    public function ceoMessage()
    {
        $model = (new BoardPost)->setTableBySlug('greetings');
        $greeting = $model->newQuery()
            ->where('id', 1)
            ->first();

        return view('information.ceo-message', [
            'gNum' => '01',
            'sNum' => '01',
            'gName' => '기업정보',
            'sName' => 'CEO 인사말',
            'greeting' => $greeting
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
        $model = (new BoardPost)->setTableBySlug('company_history_ko');
        $histories = $model->newQuery()
            ->orderBy('sort_order', 'desc')
            ->orderBy('category', 'desc')
            ->get();

        // 실제 데이터가 있는 연도 범위 확인
        if ($histories->isEmpty()) {
            return view('information.history', [
                'gNum' => '01',
                'sNum' => '03',
                'gName' => '기업정보',
                'sName' => '회사연혁',
                'groupedByDecade' => [],
                'availableDecades' => []
            ]);
        }

        // 게시글의 연도를 10년 단위로 변환하여 수집
        $decades = $histories->map(function($item) {
            return floor($item->category / 10) * 10;
        })->unique()->sort()->reverse()->values();

        // 10년 단위 범위 동적 생성
        $decadeRanges = [];
        foreach ($decades as $decade) {
            $decadeRanges[$decade] = [
                'start' => $decade,
                'end' => $decade + 9
            ];
        }

        // 10년 단위로 그룹화
        $groupedByDecade = [];
        foreach ($decadeRanges as $decade => $range) {
            $groupedByDecade[$decade] = $histories->filter(function($item) use ($range) {
                return $item->category >= $range['start'] && $item->category <= $range['end'];
            })->groupBy('category');
        }

        return view('information.history', [
            'gNum' => '01',
            'sNum' => '03',
            'gName' => '기업정보',
            'sName' => '회사연혁',
            'groupedByDecade' => $groupedByDecade,
            'availableDecades' => $decades->toArray()
        ]);
    }

    /**
     * 품질/환경경영 페이지
     */
    public function qualityEnvironmental()
    {
        $model = (new BoardPost)->setTableBySlug('quality_environment');
        $certifications = $model->newQuery()
            ->where('category', '국문')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('information.quality-environmental', [
            'gNum' => '01',
            'sNum' => '04',
            'gName' => '기업정보',
            'sName' => '품질/환경경영',
            'certifications' => $certifications
        ]);
    }

    /**
     * 안전/보건경영 페이지
     */
    public function safetyHealth()
    {
        $model = (new BoardPost)->setTableBySlug('safety_health');
        $certifications = $model->newQuery()
            ->where('category', '국문')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('information.safety-health', [
            'gNum' => '01',
            'sNum' => '05',
            'gName' => '기업정보',
            'sName' => '안전/보건경영',
            'certifications' => $certifications
        ]);
    }

    /**
     * 윤리경영 페이지
     */
    public function ethical()
    {
        $model = (new BoardPost)->setTableBySlug('business_ethics');
        $ethics = $model->newQuery()->first();
        $customFields = $ethics ? $ethics->getCustomFieldsArray() : [];

        return view('information.ethical', [
            'gNum' => '01',
            'sNum' => '06',
            'gName' => '기업정보',
            'sName' => '윤리경영',
            'reportingEmail' => $customFields['email'] ?? ''
        ]);
    }
}
