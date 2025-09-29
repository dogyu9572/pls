@extends('backoffice.layouts.app')

@section('title', $board->name ?? '게시판')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success board-hidden-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="board-container">
        <div class="board-page-header">
            <div class="board-page-buttons">
                <a href="{{ route('backoffice.board-posts.create', $board->slug ?? 'notice') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> 새 게시글
                </a>              
            </div>
        </div>

        <div class="board-card">
            <div class="board-card-header">
                <div class="board-page-card-title">
                    <h6>Contact us 관리</h6>                 
                </div>
            </div>
            <div class="board-card-body">
                @if($posts && count($posts) > 0)
                    @foreach($posts as $post)
                        @php
                            $customFields = $post->custom_fields ? json_decode($post->custom_fields, true) : [];
                        @endphp
                        <div class="contact-us-container">
                            <!-- 경영지원 인사총무팀 -->
                            <div class="contact-section">
                                <h5 class="contact-section-title">[경영지원 인사총무팀]</h5>
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <strong>담당자:</strong> {{ $customFields['hr_manager'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>전화:</strong> {{ $customFields['hr_phone'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>E-MAIL:</strong> {{ $customFields['hr_email'] ?? '' }}
                                    </div>
                                </div>
                            </div>

                            <!-- 경영지원 재무팀 -->
                            <div class="contact-section">
                                <h5 class="contact-section-title">[경영지원 재무팀]</h5>
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <strong>담당자:</strong> {{ $customFields['finance_manager'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>전화:</strong> {{ $customFields['finance_phone'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>E-MAIL:</strong> {{ $customFields['finance_email'] ?? '' }}
                                    </div>
                                </div>
                            </div>

                            <!-- 영업 PDI사업 -->
                            <div class="contact-section">
                                <h5 class="contact-section-title">[영업 PDI사업]</h5>
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <strong>담당자 1:</strong> {{ $customFields['pdi_manager1'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>전화 1:</strong> {{ $customFields['pdi_phone1'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>담당자 2:</strong> {{ $customFields['pdi_manager2'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>전화 2:</strong> {{ $customFields['pdi_phone2'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>E-MAIL:</strong> {{ $customFields['pdi_email'] ?? '' }}
                                    </div>
                                </div>
                            </div>

                            <!-- 영업 하역/운송사업 -->
                            <div class="contact-section">
                                <h5 class="contact-section-title">[영업 하역/운송사업]</h5>
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <strong>담당자:</strong> {{ $customFields['logistics_manager'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>전화:</strong> {{ $customFields['logistics_phone'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>E-MAIL:</strong> {{ $customFields['logistics_email'] ?? '' }}
                                    </div>
                                </div>
                            </div>

                            <!-- 영업 특장차 제조사업 -->
                            <div class="contact-section">
                                <h5 class="contact-section-title">[영업 특장차 제조사업]</h5>
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <strong>담당자:</strong> {{ $customFields['vehicle_manager'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>전화:</strong> {{ $customFields['vehicle_phone'] ?? '' }}
                                    </div>
                                    <div class="contact-item">
                                        <strong>E-MAIL:</strong> {{ $customFields['vehicle_email'] ?? '' }}
                                    </div>
                                </div>
                            </div>

                            <!-- 수정 버튼 -->
                            <div class="contact-actions">
                                <a href="{{ route('backoffice.board-posts.edit', [$board->slug ?? 'notice', $post->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> 수정
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="board-empty-state">
                        <div class="board-empty-icon">
                            <i class="fas fa-address-book"></i>
                        </div>
                        <h5>Contact us 정보가 없습니다</h5>
                        <p>새로운 Contact us 정보를 등록해주세요.</p>
                        <a href="{{ route('backoffice.board-posts.create', $board->slug ?? 'notice') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Contact us 등록
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .contact-us-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .contact-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        
        .contact-section-title {
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }
        
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 10px;
        }
        
        .contact-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .contact-item:last-child {
            border-bottom: none;
        }
        
        .contact-item strong {
            color: #555;
            margin-right: 10px;
            min-width: 80px;
            display: inline-block;
        }
        
        .contact-actions {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
        }
        
        .board-empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .board-empty-icon {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .board-empty-state h5 {
            color: #666;
            margin-bottom: 10px;
        }
        
        .board-empty-state p {
            color: #999;
            margin-bottom: 30px;
        }
    </style>
@endsection