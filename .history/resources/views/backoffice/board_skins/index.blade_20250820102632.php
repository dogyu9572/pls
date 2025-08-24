@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/board_skins.css') }}">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <h1>스킨 관리</h1>
        <a href="{{ route('backoffice.board_skins.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> 새 스킨 추가
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="board-card">
        <div class="board-card-header">
            <h6>스킨 목록</h6>
        </div>
        <div class="board-card-body">
            @if ($skins->isEmpty())
                <div class="no-data">
                    <p>등록된 스킨이 없습니다.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="board-table">
                        <thead>
                            <tr>
                                <th>이름</th>
                                <th>디렉토리</th>
                                <th>설명</th>
                                <th>상태</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($skins as $skin)
                            <tr>
                                <td>
                                    <div class="skin-name-container">
                                        <span class="skin-name">{{ $skin->name }}</span>
                                        @if($skin->is_default)
                                            <span class="skin-default-badge">기본</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $skin->directory }}</td>
                                <td>{{ $skin->description ?: '-' }}</td>
                                <td>
                                    <span class="status-badge {{ $skin->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $skin->is_active ? '활성화' : '비활성화' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="board-btn-group">
                                        <a href="{{ route('backoffice.board_skins.edit', $skin) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> 수정
                                        </a>
                                        <form action="{{ route('backoffice.board_skins.destroy', $skin) }}" method="POST" class="d-inline" onsubmit="return confirm('정말 이 스킨을 삭제하시겠습니까?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> 삭제
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
