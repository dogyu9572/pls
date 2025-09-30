<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $gNum = "main";
        $gName = "";
        $sName = "";
        
        // gallerys 게시판 최신글 4개
        $galleryPosts = $this->getLatestPosts('gallerys', 4);
        
        // notices 게시판 최신글 4개  
        $noticePosts = $this->getLatestPosts('notices', 4);
        
        return view('home.index', compact('gNum', 'gName', 'sName', 'galleryPosts', 'noticePosts'));
    }
    
    /**
     * 특정 게시판의 최신글을 가져옵니다.
     */
    private function getLatestPosts($boardSlug, $limit = 4)
    {
        try {
            $board = Board::where('slug', $boardSlug)->first();
            if (!$board) {
                return collect();
            }
            
            $tableName = "board_{$boardSlug}";
            
            // 테이블 존재 여부 확인
            if (!DB::getSchemaBuilder()->hasTable($tableName)) {
                return collect();
            }
            
            return DB::table($tableName)
                ->select('id', 'title', 'created_at', 'thumbnail')
                ->where('deleted_at', null)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($post) use ($boardSlug) {
                    return (object) [
                        'id' => $post->id,
                        'title' => $post->title,
                        'created_at' => $post->created_at,
                        'thumbnail' => $post->thumbnail,
                        'url' => route('backoffice.board-posts.show', [$boardSlug, $post->id])
                    ];
                });
                
        } catch (\Exception $e) {
            \Log::error("게시판 데이터 조회 오류: " . $e->getMessage());
            return collect();
        }
    }
}