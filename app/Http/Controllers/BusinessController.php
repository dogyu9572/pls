<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardPost;

class BusinessController extends Controller
{
    /**
     * 수입자동차 PDI사업 페이지
     */
    public function importedAutomobiles()
    {
        $brandsModel = (new BoardPost)->setTableBySlug('pdi_brands');
        $brands = $brandsModel->newQuery()
            ->orderBy('sort_order', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $inquiryModel = (new BoardPost)->setTableBySlug('business_inquiries');
        $inquiry = $inquiryModel->newQuery()->first();
        $customFields = $inquiry ? $inquiry->getCustomFieldsArray() : [];

        return view('business.imported-automobiles', [
            'gNum' => '02',
            'sNum' => '01',
            'gName' => '사업분야',
            'sName' => '수입자동차 PDI사업',
            'brands' => $brands,
            'pdiTel' => $customFields['pdi_tel'] ?? '',
            'pdiMail' => $customFields['pdi_mail'] ?? ''
        ]);
    }

    /**
     * 항만물류사업 페이지
     */
    public function portLogistics()
    {
        $partnersModel = (new BoardPost)->setTableBySlug('logistics_partners');
        $partners = $partnersModel->newQuery()
            ->orderBy('created_at', 'desc')
            ->get();

        $inquiryModel = (new BoardPost)->setTableBySlug('business_inquiries');
        $inquiry = $inquiryModel->newQuery()->first();
        $customFields = $inquiry ? $inquiry->getCustomFieldsArray() : [];

        return view('business.port-logistics', [
            'gNum' => '02',
            'sNum' => '02',
            'gName' => '사업분야',
            'sName' => '항만물류사업',
            'partners' => $partners,
            'logisticsTel' => $customFields['logistics_tel'] ?? '',
            'logisticsMail' => $customFields['logistics_mail'] ?? ''
        ]);
    }

    /**
     * 특장차 제조사업 페이지
     */
    public function specialVehicle()
    {
        $inquiryModel = (new BoardPost)->setTableBySlug('business_inquiries');
        $inquiry = $inquiryModel->newQuery()->first();
        $customFields = $inquiry ? $inquiry->getCustomFieldsArray() : [];

        $brochureUrl = '';
        $brochureFileName = '';
        if ($inquiry && $inquiry->attachments) {
            $attachments = is_array($inquiry->attachments) ? $inquiry->attachments : [];
            if (!empty($attachments) && isset($attachments[0]['path'])) {
                $brochureUrl = asset('storage/' . $attachments[0]['path']);
                $brochureFileName = $attachments[0]['name'] ?? 'brochure.pdf';
            }
        }

        return view('business.special-vehicle', [
            'gNum' => '02',
            'sNum' => '03',
            'gName' => '사업분야',
            'sName' => '특장차 제조사업',
            'specialVehicleTel' => $customFields['special_vehicle_tel'] ?? '',
            'specialVehicleMail' => $customFields['special_vehicle_mail'] ?? '',
            'brochureUrl' => $brochureUrl,
            'brochureFileName' => $brochureFileName
        ]);
    }

    // =============================================================================
    // 영문 사업분야 메서드들
    // =============================================================================

    /**
     * 영문 수입자동차 PDI사업 페이지
     */
    public function importedAutomobilesEng()
    {
        $brandsModel = (new BoardPost)->setTableBySlug('pdi_brands');
        $brands = $brandsModel->newQuery()
            ->orderBy('sort_order', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $inquiryModel = (new BoardPost)->setTableBySlug('business_inquiries_eng');
        $inquiry = $inquiryModel->newQuery()->first();
        $customFields = $inquiry ? $inquiry->getCustomFieldsArray() : [];

        return view('business.imported-automobiles-eng', [
            'gNum' => '02',
            'sNum' => '01',
            'gName' => 'Business Areas',
            'sName' => 'Imported Vehicle PDI Business',
            'brands' => $brands,
            'pdiTel' => $customFields['pdi_tel'] ?? '',
            'pdiMail' => $customFields['pdi_mail'] ?? ''
        ]);
    }

    /**
     * 영문 항만물류사업 페이지
     */
    public function portLogisticsEng()
    {
        $partnersModel = (new BoardPost)->setTableBySlug('logistics_partners');
        $partners = $partnersModel->newQuery()
            ->orderBy('created_at', 'desc')
            ->get();

        $inquiryModel = (new BoardPost)->setTableBySlug('business_inquiries_eng');
        $inquiry = $inquiryModel->newQuery()->first();
        $customFields = $inquiry ? $inquiry->getCustomFieldsArray() : [];

        return view('business.port-logistics-eng', [
            'gNum' => '02',
            'sNum' => '02',
            'gName' => 'Business Areas',
            'sName' => 'Port Logistics Business',
            'partners' => $partners,
            'logisticsTel' => $customFields['logistics_tel'] ?? '',
            'logisticsMail' => $customFields['logistics_mail'] ?? ''
        ]);
    }

    /**
     * 영문 특장차 제조사업 페이지
     */
    public function specialVehicleEng()
    {
        $inquiryModel = (new BoardPost)->setTableBySlug('business_inquiries_eng');
        $inquiry = $inquiryModel->newQuery()->first();
        $customFields = $inquiry ? $inquiry->getCustomFieldsArray() : [];

        $brochureUrl = '';
        $brochureFileName = '';
        if ($inquiry && $inquiry->attachments) {
            $attachments = is_array($inquiry->attachments) ? $inquiry->attachments : [];
            if (!empty($attachments) && isset($attachments[0]['path'])) {
                $brochureUrl = asset('storage/' . $attachments[0]['path']);
                $brochureFileName = $attachments[0]['name'] ?? 'brochure.pdf';
            }
        }

        return view('business.special-vehicle-eng', [
            'gNum' => '02',
            'sNum' => '03',
            'gName' => 'Business Areas',
            'sName' => 'Special Vehicle Manufacturing Business',
            'specialVehicleTel' => $customFields['special_vehicle_tel'] ?? '',
            'specialVehicleMail' => $customFields['special_vehicle_mail'] ?? '',
            'brochureUrl' => $brochureUrl,
            'brochureFileName' => $brochureFileName
        ]);
    }
}
