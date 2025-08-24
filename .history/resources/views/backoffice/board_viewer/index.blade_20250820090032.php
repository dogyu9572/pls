@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/boards.css') }}">
<style>
    .board-list {
        margin-top: 20px;
    }
    .board-item {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.2s;
    }
    .board-item:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .board-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .board-description {
        color: #666;
        margin-bottom: 15px;
    }
    .board-meta {
        font-size: 13px;
        color: #888;
    }
    .board-actions {
        margin-top: 10px;
    }
    .btn-view {
        background-color: #4a69bd;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 3px;
        font-size: 14px;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    .btn-view:hover {
        background-color: #1e3799;
        color: white;
        text-decoration: none;
    }
    .empty-boards {
        text-align: center;
        padding: 50px 0;
        color: #888;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>게시판 목록</h5>
                    <p class="text-muted">관리자 화면에서 게시판을 조회할 수 있습니다.</p>
                </div>
                <div class="card-body">
                    @if($boards->isEmpty())
                        <div class="empty-boards">
                            <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                            <h4>등록된 게시판이 없습니다</h4>
                            <p>관리자 메뉴의 '게시판 관리'에서 새 게시판을 추가하세요.</p>
                        </div>
                    @else
                        <div class="board-list">
                            <div class="row">
                                @foreach($boards as $board)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="board-item">
                                            <div class="board-title">
                                                {{ $board->name }}
                                                @if($board->is_active)
                                                    <span class="badge badge-success">활성</span>
                                                @else
                                                    <span class="badge badge-secondary">비활성</span>
                                                @endif
                                            </div>
                                            <div class="board-description">
                                                {{ $board->description ?: '설명이 없습니다.' }}
                                            </div>
                                            <div class="board-meta">
                                                <div>게시글: {{ $board->posts_count ?? $board->posts()->count() }}개</div>
                                                <div>슬러그: {{ $board->slug }}</div>
                                            </div>
                                            <div class="board-actions mt-3">
                                                <a href="{{ route('backoffice.board_viewer.show_board', $board->slug) }}" class="btn-view">
                                                    <i class="fas fa-eye"></i> 게시판 보기
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
