<?php

namespace App\Http\Controllers\Backoffice;

use App\Models\User;
use App\Models\Board;
use App\Models\BoardPost;
use App\Models\BoardComment;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    /**
     * 대시보드 메인 페이지
     */
    public function index()
    {
        $data = [
            'users' => User::latest()->take(5)->get(),
            'boards' => Board::latest()->take(5)->get(),
            'posts' => BoardPost::latest()->take(5)->get(),
        ];
        
        return $this->view('backoffice.dashboard', $data);
    }
}
