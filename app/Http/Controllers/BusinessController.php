<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusinessController extends Controller
{
    /**
     * 수입자동차 PDI사업 페이지
     */
    public function importedAutomobiles()
    {
        return view('business.imported-automobiles', [
            'gNum' => '02',
            'sNum' => '01',
            'gName' => '사업분야',
            'sName' => '수입자동차 PDI사업'
        ]);
    }

    /**
     * 항만물류사업 페이지
     */
    public function portLogistics()
    {
        return view('business.port-logistics', [
            'gNum' => '02',
            'sNum' => '02',
            'gName' => '사업분야',
            'sName' => '항만물류사업'
        ]);
    }

    /**
     * 특장차 제조사업 페이지
     */
    public function specialVehicle()
    {
        return view('business.special-vehicle', [
            'gNum' => '02',
            'sNum' => '03',
            'gName' => '사업분야',
            'sName' => '특장차 제조사업'
        ]);
    }
}
