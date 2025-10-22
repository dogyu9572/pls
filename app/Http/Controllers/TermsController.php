<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardPost;

class TermsController extends Controller
{
    /**
     * 개인정보처리방침
     */
    public function privacyPolicy()
    {
        $model = (new BoardPost)->setTableBySlug('privacy_policy');
        $policy = $model->newQuery()->first();

        return view('terms.privacy-policy', [
            'gNum' => '00',
            'gName' => '(주)피엘에스 개인정보처리방침',
            'policy' => $policy
        ]);
    }

    /**
     * 이메일 무단수집 거부
     */
    public function email()
    {
        $model = (new BoardPost)->setTableBySlug('email_rejection');
        $emailRejection = $model->newQuery()->first();

        return view('terms.email', [
            'gNum' => '00',
            'gName' => '이메일 무단수집 거부',
            'emailRejection' => $emailRejection
        ]);
    }

    /**
     * 피엘에스 윤리규범
     */
    public function ethic()
    {
        $model = (new BoardPost)->setTableBySlug('business_ethics');
        $ethic = $model->newQuery()->first();

        return view('terms.ethic', [
            'gNum' => '00',
            'gName' => '피엘에스 윤리규범',
            'ethic' => $ethic
        ]);
    }

    /**
     * 내부신고제도 운영규정
     */
    public function internalReporting()
    {
        $model = (new BoardPost)->setTableBySlug('business_ethics');
        $ethic = $model->newQuery()->first();
        $customFields = $ethic ? $ethic->getCustomFieldsArray() : [];

        return view('terms.internal-reporting', [
            'gNum' => '00',
            'gName' => '내부신고제도 운영규정',
            'reportingRules' => $customFields['reporting_rules'] ?? '',
            'enactmentDate' => $customFields['enactment_date'] ?? ''
        ]);
    }

    // =============================================================================
    // 영문 약관 메서드들
    // =============================================================================

    /**
     * 영문 개인정보처리방침
     */
    public function privacyPolicyEng()
    {
        $model = (new BoardPost)->setTableBySlug('privacy_policy_eng');
        $policy = $model->newQuery()->first();

        return view('terms.privacy-policy-eng', [
            'gNum' => '00',
            'gName' => 'PLS Corp Privacy Policy',
            'policy' => $policy
        ]);
    }

    /**
     * 영문 이메일 무단수집 거부
     */
    public function emailEng()
    {
        $model = (new BoardPost)->setTableBySlug('email_rejection_eng');
        $emailRejection = $model->newQuery()->first();

        return view('terms.email-eng', [
            'gNum' => '00',
            'gName' => 'Email Collection Refusal',
            'emailRejection' => $emailRejection
        ]);
    }

    /**
     * 영문 피엘에스 윤리규범
     */
    public function ethicEng()
    {
        $model = (new BoardPost)->setTableBySlug('business_ethics_eng');
        $ethic = $model->newQuery()->first();

        return view('terms.ethic-eng', [
            'gNum' => '00',
            'gName' => 'PLS Code of Ethics',
            'ethic' => $ethic
        ]);
    }

    /**
     * 영문 내부신고제도 운영규정
     */
    public function internalReportingEng()
    {
        $model = (new BoardPost)->setTableBySlug('business_ethics_eng');
        $ethic = $model->newQuery()->first();
        $customFields = $ethic ? $ethic->getCustomFieldsArray() : [];

        return view('terms.internal-reporting-eng', [
            'gNum' => '00',
            'gName' => 'Internal Reporting System Operation Regulations',
            'reportingRules' => $customFields['reporting_rules_eng'] ?? '',
            'enactmentDate' => $customFields['enactment_date_eng'] ?? ''
        ]);
    }
}
