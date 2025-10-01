<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardPost;

class PrCenterController extends Controller
{
    /**
     * PLS 공지 목록 페이지
     */
    public function announcements(Request $request)
    {
        $model = (new BoardPost)->setTableBySlug('notices');
        
        $searchType = $request->input('search_type', '제목');
        $searchKeyword = $request->input('search_keyword');
        
        $query = $model->newQuery()
            ->where('category', '국문')
            ->when($searchKeyword, function ($q) use ($searchType, $searchKeyword) {
                $column = $searchType === '제목' ? 'title' : 'content';
                return $q->where($column, 'like', '%' . $searchKeyword . '%');
            })
            ->orderBy('is_notice', 'desc')
            ->orderBy('created_at', 'desc');

        $posts = $query->paginate(10);
        $total = $model->newQuery()->where('category', '국문')->count();

        return view('pr-center.announcements', [
            'gNum' => '04',
            'sNum' => '01',
            'gName' => '홍보센터',
            'sName' => 'PLS 공지',
            'posts' => $posts,
            'total' => $total,
            'searchType' => $searchType,
            'searchKeyword' => $searchKeyword
        ]);
    }

    /**
     * PLS 공지 상세 페이지
     */
    public function announcementsShow($id)
    {
        $model = (new BoardPost)->setTableBySlug('notices');
        $post = $model->newQuery()->findOrFail($id);
        
        // 조회수 증가
        $post->increment('view_count');
        
        // 이전글/다음글
        $prevPost = $model->newQuery()
            ->where('id', '<', $id)
            ->orderBy('id', 'desc')
            ->first();
            
        $nextPost = $model->newQuery()
            ->where('id', '>', $id)
            ->orderBy('id', 'asc')
            ->first();

        return view('pr-center.announcements-show', [
            'gNum' => '04',
            'sNum' => '01',
            'gName' => '홍보센터',
            'sName' => 'PLS 공지',
            'post' => $post,
            'prevPost' => $prevPost,
            'nextPost' => $nextPost
        ]);
    }

    /**
     * PLS 소식 목록 페이지 (갤러리형)
     */
    public function news(Request $request)
    {
        $model = (new BoardPost)->setTableBySlug('gallerys');
        
        $searchType = $request->input('search_type', '제목');
        $searchKeyword = $request->input('search_keyword');
        
        $query = $model->newQuery()
            ->where('category', '국문')
            ->when($searchKeyword, function ($q) use ($searchType, $searchKeyword) {
                $column = $searchType === '제목' ? 'title' : 'content';
                return $q->where($column, 'like', '%' . $searchKeyword . '%');
            })
            ->orderBy('is_notice', 'desc')
            ->orderBy('created_at', 'desc');

        $posts = $query->paginate(9);
        $total = $model->newQuery()->where('category', '국문')->count();

        // 게시글 데이터 가공
        $posts->getCollection()->transform(function ($post) {
            $post->formatted_date = $post->created_at->format('Y.m.d');
            $post->is_new = $post->created_at->diffInDays(now()) < 7;
            return $post;
        });

        return view('pr-center.news', [
            'gNum' => '04',
            'sNum' => '02',
            'gName' => '홍보센터',
            'sName' => 'PLS 소식',
            'posts' => $posts,
            'total' => $total,
            'searchType' => $searchType,
            'searchKeyword' => $searchKeyword
        ]);
    }

    /**
     * PLS 소식 상세 페이지
     */
    public function newsShow($id)
    {
        $model = (new BoardPost)->setTableBySlug('gallerys');
        $post = $model->newQuery()->findOrFail($id);
        
        // 조회수 증가
        $post->increment('view_count');
        
        // 이전글/다음글
        $prevPost = $model->newQuery()
            ->where('id', '<', $id)
            ->orderBy('id', 'desc')
            ->first();
            
        $nextPost = $model->newQuery()
            ->where('id', '>', $id)
            ->orderBy('id', 'asc')
            ->first();

        return view('pr-center.news-show', [
            'gNum' => '04',
            'sNum' => '02',
            'gName' => '홍보센터',
            'sName' => 'PLS 소식',
            'post' => $post,
            'prevPost' => $prevPost,
            'nextPost' => $nextPost
        ]);
    }

    /**
     * 오시는 길 페이지
     */
    public function location()
    {
        return view('pr-center.location', [
            'gNum' => '04',
            'sNum' => '03',
            'gName' => '홍보센터',
            'sName' => '오시는 길'
        ]);
    }
}
