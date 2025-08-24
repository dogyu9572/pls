@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('content')
<div class="board-container">
    <div class="board-header">
        <h1>{{ $board->name }} 게시판</h1>
        <div>
            <a href="{{ route('backoffice.boards.edit', $board) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> 수정
            </a>
            <a href="{{ route('backoffice.boards.index') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-arrow-left"></i> 목록으로
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="board-content">
        <div class="board-main">
            <div class="board-card">
                <div class="board-card-header">
                    <h6>게시판 정보</h6>
                </div>
                <div class="board-card-body">
                    <div class="table-responsive">
                        <table class="board-table">
                            <tr>
                                <th class="board-name-column">게시판 이름</th>
                                <td>{{ $board->name }}</td>
                            </tr>
                            <tr>
                                <th>슬러그 (URL)</th>
                                <td>{{ $board->slug }}</td>
                            </tr>
                            <tr>
                                <th>설명</th>
                                <td>{{ $board->description ?: '설명이 없습니다.' }}</td>
                            </tr>
                            <tr>
                                <th>스킨</th>
                                <td>{{ $board->skin ? $board->skin->name : '없음' }}</td>
                            </tr>
                            <tr>
                                <th>페이지당 글 수</th>
                                <td>{{ $board->list_count ?? '15' }}</td>
                            </tr>
                            <tr>
                                <th>공지 기능</th>
                                <td>
                                    @if($board->enable_notice)
                                        <span class="badge badge-success">활성</span>
                                    @else
                                        <span class="badge badge-secondary">비활성</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>읽기 권한</th>
                                <td>
                                    @if($board->permission_read == 'all')
                                        <span class="badge badge-primary">모두</span>
                                    @elseif($board->permission_read == 'member')
                                        <span class="badge badge-info">회원만</span>
                                    @elseif($board->permission_read == 'admin')
                                        <span class="badge badge-dark">관리자만</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>쓰기 권한</th>
                                <td>
                                    @if($board->permission_write == 'all')
                                        <span class="badge badge-primary">모두</span>
                                    @elseif($board->permission_write == 'member')
                                        <span class="badge badge-info">회원만</span>
                                    @elseif($board->permission_write == 'admin')
                                        <span class="badge badge-dark">관리자만</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>댓글 작성 권한</th>
                                <td>
                                    @if($board->permission_comment == 'all')
                                        <span class="badge badge-primary">모두</span>
                                    @elseif($board->permission_comment == 'member')
                                        <span class="badge badge-info">회원만</span>
                                    @elseif($board->permission_comment == 'admin')
                                        <span class="badge badge-dark">관리자만</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>사용 여부</th>
                                <td>
                                    @if($board->is_active)
                                        <span class="badge badge-success">활성</span>
                                    @else
                                        <span class="badge badge-secondary">비활성</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>생성일</th>
                                <td>{{ $board->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th>마지막 수정일</th>
                                <td>{{ $board->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('boards.index', $board->slug) }}" target="_blank" class="btn btn-sm btn-success">
                            <i class="fas fa-external-link-alt"></i> 게시판 보기
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">게시판 통계</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="small-box bg-info p-3 text-center text-white mb-3">
                                <div class="inner">
                                    <h3>{{ $board->posts->count() }}</h3>
                                    <p class="mb-0">총 게시글</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="small-box bg-success p-3 text-center text-white mb-3">
                                <div class="inner">
                                    <h3>{{ $board->comments->count() }}</h3>
                                    <p class="mb-0">총 댓글</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h6 class="font-weight-bold">최근 활동</h6>
                        <p>최신 게시글:
                            @if($board->posts->count() > 0)
                                {{ $board->posts->sortByDesc('created_at')->first()->created_at->diffForHumans() }}
                            @else
                                없음
                            @endif
                        </p>
                        <p>최신 댓글:
                            @if($board->comments->count() > 0)
                                {{ $board->comments->sortByDesc('created_at')->first()->created_at->diffForHumans() }}
                            @else
                                없음
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">관리</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('backoffice.boards.destroy', $board) }}" method="POST" onsubmit="return confirm('이 게시판을 삭제하시겠습니까? 관련된 모든 데이터가 함께 삭제됩니다.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> 게시판 삭제
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
