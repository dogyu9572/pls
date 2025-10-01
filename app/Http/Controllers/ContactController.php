<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Contact Us 페이지
     */
    public function index()
    {
        return view('contact.index', [
            'gNum' => '00',
            'gName' => 'Contact Us'
        ]);
    }
}
