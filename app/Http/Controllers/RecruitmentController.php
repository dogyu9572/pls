<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardPost;

class RecruitmentController extends Controller
{
    /**
     * 인재상 페이지
     */
    public function idealEmployee()
    {
        return view('recruitment.ideal-employee', [
            'gNum' => '03',
            'sNum' => '01',
            'gName' => '인재채용',
            'sName' => '인재상'
        ]);
    }

    /**
     * 인사제도 페이지
     */
    public function personnel()
    {
        return view('recruitment.personnel', [
            'gNum' => '03',
            'sNum' => '02',
            'gName' => '인재채용',
            'sName' => '인사제도'
        ]);
    }

    /**
     * 복지제도 페이지
     */
    public function welfare()
    {
        return view('recruitment.welfare', [
            'gNum' => '03',
            'sNum' => '03',
            'gName' => '인재채용',
            'sName' => '복지제도'
        ]);
    }

    /**
     * 채용안내 페이지
     */
    public function information()
    {
        $model = (new BoardPost)->setTableBySlug('recruitments');
        $recruitment = $model->newQuery()->first();
        $customFields = $recruitment ? $recruitment->getCustomFieldsArray() : [];

        return view('recruitment.information', [
            'gNum' => '03',
            'sNum' => '04',
            'gName' => '인재채용',
            'sName' => '채용안내',
            'recruitmentTel' => $customFields['tel'] ?? '',
            'recruitmentMail' => $customFields['mail'] ?? '',
            'recruitmentLink' => $customFields['link'] ?? ''
        ]);
    }
}
