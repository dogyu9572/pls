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

---

## 📐 퍼블리싱 작업 가이드

### 1. 파일 구조

#### CSS 파일
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

#### JavaScript 파일
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

#### 이미지 파일
```
public/images/
├── logo.svg              # 로고 파일
├── favicon.ico           # 파비콘
├── icon_*.svg            # 아이콘 파일들
├── img_*.jpg             # 일반 이미지들
└── bg_*.jpg              # 배경 이미지들
```

### 2. 파일 찾기

#### URL로 파일 위치 찾기
```
http://localhost/information/about-company
↓
파일 위치: resources/views/information/about-company.blade.php
```

#### 실제 예시
| URL | 수정할 파일 위치 |
|-----|------------------|
| `http://localhost/` | `resources/views/home/index.blade.php` |
| `http://localhost/information/about-company` | `resources/views/information/about-company.blade.php` |
| `http://localhost/business/pdi` | `resources/views/business/pdi.blade.php` |
| `http://localhost/pr-center/news` | `resources/views/pr-center/news.blade.php` |
| `http://localhost/contact` | `resources/views/contact/index.blade.php` |

#### 공통 파일 위치
- **헤더/푸터**: `resources/views/layouts/app.blade.php`
- **메인 CSS**: `public/css/styles.css` (PC)
- **반응형 CSS**: `public/css/reactive.css` (모바일)
- **공통 JS**: `public/js/com.js`

### 3. 페이지 접속 방법

**⚠️ 중요: HTML 파일만 만들어도 바로 접속 안 됨**

- HTML 파일을 만들어도 라우트 설정 없이는 페이지 접속 불가
- 라우트 파일 위치: `routes/web.php` (프론트엔드), `routes/backoffice.php` (관리자)

#### 라우트 설정 예시

**routes/web.php**
```php
Route::get('/information/about-company', [InformationController::class, 'aboutCompany']);
```

**app/Http/Controllers/InformationController.php**
```php
public function aboutCompany()
{
    return view('information.about-company', [
        'gNum' => '01',
        'sNum' => '02',
    ]);
}
```

→ 위 설정 완료하면 `http://localhost/information/about-company` 접속 가능

### 4. Blade 템플릿 기본 문법

#### 변수 출력
```blade
{{ $변수명 }}                    {{-- 변수 출력 --}}
{{ $name ?? '기본값' }}          {{-- 기본값 설정 --}}
{{-- 주석 --}}
```

#### CSS/JS/이미지 연결
```blade
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<script src="{{ asset('js/com.js') }}"></script>
<img src="{{ asset('images/logo.svg') }}" alt="로고">
```

#### 링크 연결
```blade
<a href="{{ route('home') }}">홈</a>
```

#### 레이아웃 상속
```blade
@extends('layouts.app')          {{-- 레이아웃 상속 --}}
@section('content')
    ... 페이지 내용 ...
@endsection
```

### 5. 작업 순서

```
1. 라우트/컨트롤러 세팅 요청
   - routes/web.php
   - app/Http/Controllers/해당컨트롤러.php
   
2. Blade 파일 작업
   - resources/views/폴더명/파일명.blade.php
   - public/css/styles.css
   - public/js/파일명.js
   - public/images/
   
3. 브라우저 확인
```

---

## 🎯 Laravel Blade 핵심 문법

### 1. 조건문
```blade
@if($조건) ... @endif
@if($조건) ... @else ... @endif
@if($조건) ... @elseif($조건2) ... @else ... @endif
@isset($변수) ... @endisset      {{-- 변수 존재 확인 --}}
@empty($배열) ... @endempty      {{-- 배열 비어있는지 확인 --}}
```

### 2. 반복문
```blade
@foreach($배열 as $항목) ... @endforeach
@forelse($배열 as $항목) ... @empty ... @endforelse
@for($i=0; $i<10; $i++) ... @endfor
```

### 3. 파일 포함
```blade
@include('components.header')     {{-- 헤더/푸터 포함 --}}
@include('components.footer')
@include('components.sidebar', ['data' => $data])  {{-- 데이터 전달 --}}
```

### 4. 레이아웃 상속 (고급)
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

### 5. 폼과 CSRF
```blade
<form method="POST" action="{{ route('store') }}">
    @csrf                        {{-- CSRF 토큰 필수 --}}
    @method('PUT')               {{-- PUT/PATCH/DELETE --}}
    <input type="text" name="title" value="{{ old('title') }}">
</form>
```

### 6. 에러 처리
```blade
@error('field') {{ $message }} @enderror
@if($errors->any()) ... @endif
<input value="{{ old('name') }}">  {{-- 이전 입력값 --}}
```

### 7. 유용한 헬퍼
```blade
{{ $date->format('Y-m-d') }}     {{-- 날짜 포맷 --}}
{{ Str::limit($text, 100) }}     {{-- 텍스트 자르기 --}}
{{ number_format($price) }}      {{-- 숫자 포맷 --}}
```

---

## 🔧 관리자 페이지 가이드

### 1. 메뉴 관리
- **경로**: `/backoffice/admin-menus`
- **기능**: 사이트 메뉴 구조 관리
- **지원 기능**: 드래그 앤 드롭, 계층 구조

### 2. 게시판 관리
- **경로**: `/backoffice/board-posts`
- **기능**: 뉴스, 갤러리, 공지사항 등 게시글 관리
- **지원 기능**: 썸네일, 첨부파일, 카테고리

---

## 🚀 프로젝트 관리 및 배포 가이드

### 📋 권장 개발 워크플로우
- **로컬에서 작업 후 GitHub 자동 배포를 통한 배포를 권장합니다**
- **GitHub Actions를 통한 자동 배포가 설정되어 있습니다**

### 🔄 자동 배포 사용 방법

#### 1. 로컬 개발 환경 설정
```bash
# 1. 프로젝트 클론
git clone [저장소 URL]
cd pls

# 2. 의존성 설치
composer install
npm install

# 3. 환경 설정
cp .env.example .env
php artisan key:generate
```

#### 2. 개발 워크플로우
```bash
# 1. 최신 코드 가져오기
git pull origin main

# 2. 새 브랜치 생성 (작업명으로)
git checkout -b feature/작업명

# 3. 파일 수정 후 커밋
git add .
git commit -m "feat: 작업 내용 설명"

# 4. GitHub에 푸시
git push origin feature/작업명

# 5. GitHub에서 Pull Request 생성
# 6. PR 승인 후 main 브랜치에 머지
# 7. 자동으로 운영서버에 배포됨
```

### ⚠️ 운영서버 직접 수정 시 대응 방법

#### 상황: 다른 팀원이 운영서버에서 직접 파일을 수정한 경우

##### 1. 운영서버에서 Git 커밋 (권장)
```bash
# SSH로 운영서버 접속
ssh user@서버주소

# 운영서버에서 변경사항을 Git에 반영
cd /path/to/project
git add .
git commit -m "feat: 운영서버에서 수정된 내용"
git push origin main
```

##### 2. 로컬에서 작업하기 전
```bash
# 로컬에서 최신 코드 받기
git pull origin main

# 그 다음에 작업 시작
git checkout -b feature/내작업
```

##### 3. Git 충돌 발생 시 해결
```bash
# 충돌 파일 확인
git status

# 충돌 파일을 수동으로 해결
# <<<<<<< HEAD (내 변경사항)
# ======= (운영서버 변경사항)
# >>>>>>> 운영서버버전

# 충돌 해결 후
git add .
git commit -m "resolve: 충돌 해결"
git push origin main
```

### 💡 권장사항
- **가능하면 로컬에서 작업 후 자동 배포 사용**
- **운영서버 직접 수정 시 반드시 Git에 커밋**
- **작업 시작 전에 항상 `git pull origin main`으로 최신 상태 확인**
- **팀 전체가 Git 사용법을 익히는 것이 최선**

**프로젝트 버전**: Laravel 12.x
