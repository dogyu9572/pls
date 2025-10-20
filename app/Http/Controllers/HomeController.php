<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\Popup;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        
        // 활성화된 팝업 조회
        $popups = Popup::where('is_active', true)
            ->where(function($query) {
                $query->where('use_period', false)
                      ->orWhere(function($q) {
                          $q->where('use_period', true)
                            ->where(function($periodQuery) {
                                $periodQuery->whereNull('start_date')
                                           ->orWhere('start_date', '<=', now());
                            })
                            ->where(function($periodQuery) {
                                $periodQuery->whereNull('end_date')
                                           ->orWhere('end_date', '>=', now());
                            });
                      });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // 활성화된 배너 조회
        $banners = Banner::active()
            ->inPeriod()
            ->ordered()
            ->get();
        
        return view('home.index', compact('gNum', 'gName', 'sName', 'galleryPosts', 'noticePosts', 'popups', 'banners'));
    }

    public function eng_index()
    {
        $gNum = "main";
        $gName = "";
        $sName = "";
        
        // gallerys 게시판 최신글 4개
        $galleryPosts = $this->getLatestPosts('gallerys', 4);
        
        // notices 게시판 최신글 4개  
        $noticePosts = $this->getLatestPosts('notices', 4);
        
        // 활성화된 팝업 조회
        $popups = Popup::where('is_active', true)
            ->where(function($query) {
                $query->where('use_period', false)
                      ->orWhere(function($q) {
                          $q->where('use_period', true)
                            ->where(function($periodQuery) {
                                $periodQuery->whereNull('start_date')
                                           ->orWhere('start_date', '<=', now());
                            })
                            ->where(function($periodQuery) {
                                $periodQuery->whereNull('end_date')
                                           ->orWhere('end_date', '>=', now());
                            });
                      });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // 활성화된 배너 조회
        $banners = Banner::active()
            ->inPeriod()
            ->ordered()
            ->get();
        
        return view('home.eng_index', compact('gNum', 'gName', 'sName', 'galleryPosts', 'noticePosts', 'popups', 'banners'));
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
                ->select('id', 'title', 'created_at', 'thumbnail', 'category')
                ->where('deleted_at', null)
                ->where('category', '국문')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($post) use ($boardSlug) {
                    // 게시판 슬러그에 따라 사용자용 라우트 설정
                    $url = match ($boardSlug) {
                        'gallerys' => route('pr-center.news.show', $post->id),
                        'notices' => route('pr-center.announcements.show', $post->id),
                        default => route('backoffice.board-posts.show', [$boardSlug, $post->id])
                    };
                    
                    return (object) [
                        'id' => $post->id,
                        'title' => $post->title,
                        'created_at' => $post->created_at,
                        'thumbnail' => $post->thumbnail,
                        'url' => $url
                    ];
                });
                
        } catch (\Exception $e) {
            Log::error("게시판 데이터 조회 오류: " . $e->getMessage());
            return collect();
        }
    }
}