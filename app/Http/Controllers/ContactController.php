<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardPost;

class ContactController extends Controller
{
    /**
     * Contact Us 페이지
     */
    public function index()
    {
        $model = (new BoardPost)->setTableBySlug('contact_us');
        $contact = $model->newQuery()->first();
        $customFields = $contact ? $contact->getCustomFieldsArray() : [];

        return view('contact.index', [
            'gNum' => '00',
            'gName' => 'Contact Us',
            'contact' => $customFields
        ]);
    }

    /**
     * 영문 Contact Us 페이지
     */
    public function indexEng()
    {
        $model = (new BoardPost)->setTableBySlug('contact_us_eng');
        $contact = $model->newQuery()->first();
        $customFields = $contact ? $contact->getCustomFieldsArray() : [];

        return view('contact.index-eng', [
            'gNum' => '00',
            'gName' => 'Contact Us',
            'contact' => $customFields
        ]);
    }
}
