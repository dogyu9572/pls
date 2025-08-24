<?php

namespace App\Http\Controllers\Backoffice;

use App\Models\User;
use App\Models\Board;
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
            'totalPosts' => $this->getTotalPostsCount(),
            'totalComments' => $this->getTotalCommentsCount(),
        ];
        
        return $this->view('backoffice.dashboard', $data);
    }

    /**
     * 전체 게시글 수 계산 (실시간 집계)
     */
    private function getTotalPostsCount(): int
    {
        $boards = Board::all();
        $totalPosts = 0;
        
        foreach ($boards as $board) {
            $tableName = 'board_' . $board->slug;
            try {
                $count = \Illuminate\Support\Facades\DB::table($tableName)->count();
                $totalPosts += $count;
            } catch (\Exception $e) {
                // 테이블이 존재하지 않는 경우 무시
                continue;
            }
        }
        
        return $totalPosts;
    }

    /**
     * 전체 댓글 수 계산 (실시간 집계)
     */
    private function getTotalCommentsCount(): int
    {
        $boards = Board::all();
        $totalComments = 0;
        
        foreach ($boards as $board) {
            $tableName = 'board_' . $board->slug . '_comments';
            try {
                $count = \Illuminate\Support\Facades\DB::table($tableName)->count();
                $totalComments += $count;
            } catch (\Exception $e) {
                // 테이블이 존재하지 않는 경우 무시
                continue;
            }
        }
        
        return $totalComments;
    }
}
