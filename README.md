# PLS Corp 웹사이트 개발 가이드

## 📋 프로젝트 개요
PLS Corp 공식 웹사이트로, 수입자동차 PDI 사업, 항만물류사업, 특장차 제조업을 소개하는 기업 홈페이지입니다.

## 🏗️ 프로젝트 구조

### 주요 디렉토리 구조
```
pls/
├── public/                 # 웹 접근 가능한 정적 파일들
│   ├── css/               # 스타일시트 파일들
│   ├── js/                # JavaScript 파일들
│   ├── images/            # 이미지 파일들
│   └── storage/           # 업로드된 파일들
├── resources/             # 뷰 템플릿과 에셋
│   └── views/             # Blade 템플릿 파일들
├── app/                   # PHP 애플리케이션 코드
├── routes/                # 라우팅 설정
└── database/              # 데이터베이스 관련 파일들
```

## 🎨 퍼블리싱 작업 가이드

### 1. CSS 파일 구조
```
public/css/
├── styles.css            # 메인 스타일시트 (PC 버전)
├── reactive.css          # 반응형 스타일시트 (모바일/태블릿)
├── popup.css             # 팝업 관련 스타일
├── slick.css             # 슬라이더 라이브러리 스타일
├── font.css              # 폰트 관련 스타일
├── common/               # 공통 컴포넌트 스타일
├── backoffice/           # 관리자 페이지 스타일
└── frontend/             # 프론트엔드 전용 스타일
```

### 2. JavaScript 파일 구조
```
public/js/
├── com.js                # 공통 JavaScript
├── popup.js              # 팝업 관련 스크립트
├── slick.js              # 슬라이더 라이브러리
├── jquery.js             # jQuery 라이브러리
├── common/               # 공통 스크립트
├── backoffice/           # 관리자 페이지 스크립트
└── frontend/             # 프론트엔드 전용 스크립트
```

### 3. 이미지 파일 구조
```
public/images/
├── logo.svg              # 로고 파일
├── favicon.ico           # 파비콘
├── icon_*.svg            # 아이콘 파일들
├── img_*.jpg             # 일반 이미지들
└── bg_*.jpg              # 배경 이미지들
```

## 📄 페이지별 템플릿 구조

### 1. 메인 페이지
- **파일 위치**: `resources/views/home/index.blade.php`
- **설명**: 메인 배너, 비즈니스 소개, 뉴스, 공지사항 등

### 2. 기업정보 페이지들
- **디렉토리**: `resources/views/information/`
- **포함 페이지**: CEO 인사말, 회사소개, 회사연혁, 품질/환경경영

### 3. 사업영역 페이지들
- **디렉토리**: `resources/views/business/`
- **포함 페이지**: 수입자동차 PDI, 항만물류, 특장차 제조

### 4. PR 센터 페이지들
- **디렉토리**: `resources/views/pr-center/`
- **포함 페이지**: 뉴스, 갤러리, 공지사항

### 5. 관리자 페이지들
- **디렉토리**: `resources/views/backoffice/`
- **포함 페이지**: 대시보드, 배너 관리, 메뉴 관리, 게시판 관리

## 🛠️ Laravel 파일 경로 찾기 가이드

#### 1. URL 패턴 분석
```
http://localhost/information/about-company
↓
URL 구조: /{카테고리}/{페이지명}
↓
파일 위치: resources/views/{카테고리}/{페이지명}.blade.php
```

#### 2. 실제 예시
| URL | 수정할 파일 위치 |
|-----|------------------|
| `http://localhost/` | `resources/views/home/index.blade.php` |
| `http://localhost/information/about-company` | `resources/views/information/about-company.blade.php` |
| `http://localhost/business/pdi` | `resources/views/business/pdi.blade.php` |
| `http://localhost/pr-center/news` | `resources/views/pr-center/news.blade.php` |
| `http://localhost/contact` | `resources/views/contact/index.blade.php` |

#### 3. 라우트 파일에서 확인하는 방법
```php
{{-- routes/web.php 또는 routes/backoffice.php 파일 열기 --}}

{{-- 예시: routes/web.php --}}
Route::get('/information/about-company', [InformationController::class, 'aboutCompany'])
    ->name('information.about-company');

{{-- URL 패턴: /information/about-company --}}
{{-- 컨트롤러: InformationController@aboutCompany --}}
{{-- 라우트명: information.about-company --}}
{{-- 파일 위치: resources/views/information/about-company.blade.php --}}
```

#### 4. 라우트 파일 읽는 방법
```php
{{-- 기본 패턴 --}}
Route::get('/경로', [컨트롤러::class, '메서드'])->name('라우트명');

{{-- 실제 프로젝트 예시 (routes/web.php) --}}
Route::get('/', [HomeController::class, 'index'])->name('home');
{{-- URL: / → 파일: resources/views/home/index.blade.php --}}

{{-- 그룹 라우트 (공통 경로) --}}
Route::prefix('information')->name('information.')->group(function () {
    Route::get('/about-company', [InformationController::class, 'aboutCompany'])->name('about-company');
    Route::get('/ceo-message', [InformationController::class, 'ceoMessage'])->name('ceo-message');
    Route::get('/history', [InformationController::class, 'history'])->name('history');
});
{{-- 결과: /information/about-company, /information/ceo-message, /information/history --}}

```

#### 5. 라우트 파일 위치
- **프론트엔드 라우트**: `routes/web.php`
- **관리자 라우트**: `routes/backoffice.php`
- **API 라우트**: `routes/api.php`

### 🎯 Laravel 특화 수정 포인트

### 🔍 파일 찾기

#### 1. 공통 레이아웃 파일
- **헤더/푸터**: `resources/views/layouts/app.blade.php`
- **CSS/JS 연결**: `resources/views/layouts/app.blade.php` 상단

#### 2. CSS/JS 파일 위치
- **메인 CSS**: `public/css/styles.css` (PC)
- **반응형 CSS**: `public/css/reactive.css` (모바일)
- **공통 JS**: `public/js/com.js`

#### 3. 이미지 파일 위치
- **정적 이미지**: `public/images/`
- **업로드 이미지**: `public/storage/`

## 🎯 Laravel Blade 핵심 문법

### 1. 기본 출력
```blade
{{ $변수명 }}                    {{-- 변수 출력 (HTML 이스케이프) --}}
{!! $변수명 !!}                  {{-- 변수 출력 (HTML 허용) --}}
{{ $name ?? '기본값' }}          {{-- 기본값 설정 --}}
{{-- 주석 --}}
```

### 2. 조건문
```blade
@if($조건) ... @endif
@if($조건) ... @else ... @endif
@if($조건) ... @elseif($조건2) ... @else ... @endif
@isset($변수) ... @endisset      {{-- 변수 존재 확인 --}}
@empty($배열) ... @endempty      {{-- 배열 비어있는지 확인 --}}
```

### 3. 반복문
```blade
@foreach($배열 as $항목) ... @endforeach
@forelse($배열 as $항목) ... @empty ... @endforelse
@for($i=0; $i<10; $i++) ... @endfor
```

### 4. 파일 포함
```blade
@include('components.header')     {{-- 헤더/푸터 포함 --}}
@include('components.footer')
@include('components.sidebar', ['data' => $data])  {{-- 데이터 전달 --}}
```

### 5. 에셋 파일 연결
```blade
{{ asset('css/styles.css') }}    {{-- CSS/JS/이미지 --}}
{{ asset('storage/' . $image) }} {{-- 업로드 파일 --}}
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<script src="{{ asset('js/app.js') }}"></script>
```

### 6. 라우트 연결
```blade
{{ route('home') }}              {{-- 라우트 URL --}}
{{ route('posts.show', $id) }}   {{-- 파라미터 있는 라우트 --}}
<a href="{{ route('home') }}">홈</a>
```

### 7. 레이아웃 상속
```blade
{{-- 페이지에서 레이아웃 상속 --}}
@extends('layouts.app')          {{-- 레이아웃 상속 --}}
@section('title', '페이지 제목')
@section('content') ... @endsection
@section('scripts') @parent ... @endsection  {{-- 기존 섹션에 추가 --}}
```

#### 레이아웃 파일 위치와 수정 방법
```blade
{{-- 레이아웃 파일: resources/views/layouts/app.blade.php --}}

<head>
    <title>PLS Corp</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/reactive.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/popup.css') }}" media="all">
    <script src="{{ asset('js/com.js') }}"></script>
    <script src="{{ asset('js/popup.js') }}"></script>
</head>
<body>
    {{-- 헤더 (레이아웃 파일에 직접 포함) --}}
    <div class="header">
        <a href="/" class="logo">
            <img src="{{ asset('images/logo.svg') }}" alt="logo">
            <h1>PLS Corp</h1>
        </a>        
    </div>
    
    {{-- 페이지 내용이 들어갈 자리 --}}
    @yield('content')   
    
    {{-- 페이지별 스크립트 --}}
    @yield('scripts')
</body>
```

#### 수정해야 할 파일들
- **레이아웃 전체**: `resources/views/layouts/app.blade.php` (헤더/푸터 포함)
- **페이지 내용**: `resources/views/각페이지.blade.php`
- **컴포넌트**: `resources/views/components/pagination.blade.php` (페이지네이션 등)

### 8. 폼과 CSRF
```blade
<form method="POST" action="{{ route('store') }}">
    @csrf                        {{-- CSRF 토큰 필수 --}}
    @method('PUT')               {{-- PUT/PATCH/DELETE --}}
    <input type="text" name="title" value="{{ old('title') }}">
</form>
```

### 9. 에러 처리
```blade
@error('field') {{ $message }} @enderror
@if($errors->any()) ... @endif
<input value="{{ old('name') }}">  {{-- 이전 입력값 --}}
```

### 10. 유용한 헬퍼
```blade
{{ $date->format('Y-m-d') }}     {{-- 날짜 포맷 --}}
{{ Str::limit($text, 100) }}     {{-- 텍스트 자르기 --}}
{{ number_format($price) }}      {{-- 숫자 포맷 --}}
```

## 🔧 관리자 페이지 가이드

### 1. 메뉴 관리
- **경로**: `/backoffice/admin-menus`
- **기능**: 사이트 메뉴 구조 관리
- **지원 기능**: 드래그 앤 드롭, 계층 구조

### 2. 게시판 관리
- **경로**: `/backoffice/board-posts`
- **기능**: 뉴스, 갤러리, 공지사항 등 게시글 관리
- **지원 기능**: 썸네일, 첨부파일, 카테고리

**프로젝트 버전**: Laravel 12.x