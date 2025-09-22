<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backoffice\AuthController;
use App\Http\Controllers\Backoffice\AdminMenuController;
use App\Http\Controllers\Backoffice\SettingController;
use App\Http\Controllers\Backoffice\BoardController;
use App\Http\Controllers\Backoffice\BoardSkinController;
use App\Http\Controllers\Backoffice\BoardPostController;
use App\Http\Controllers\Backoffice\UserController;
use App\Http\Controllers\Backoffice\LogController;
use App\Http\Controllers\Backoffice\AdminController;
use App\Http\Controllers\Backoffice\BannerController;

use App\Http\Controllers\UserController as FrontendUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
// use Illuminate\Support\Facades\Auth;

// =============================================================================
// 메인 페이지
// =============================================================================
Route::get('/', function () {
    return view('welcome');
});

// =============================================================================
// 인증 관련 라우트 (로그인/회원가입)
// =============================================================================

// 사용자 인증
Route::prefix('auth')->name('auth.')->group(function () {
    // 로그인
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    // 회원가입
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // 비밀번호 재설정
    Route::prefix('password')->name('password.')->group(function () {
        Route::get('/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('request');
        Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('email');
        Route::get('/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
            ->name('reset');
        Route::post('/reset', [ResetPasswordController::class, 'reset'])
            ->name('update');
    });
});

// =============================================================================
// 백오피스 인증 라우트
// =============================================================================
Route::prefix('backoffice')->name('backoffice.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

// =============================================================================
// 백오피스 라우트 그룹 (인증된 관리자만 접근)
// =============================================================================
Route::middleware('backoffice')->prefix('backoffice')->group(function () {

    // -------------------------------------------------------------------------
    // 대시보드
    // -------------------------------------------------------------------------
    Route::get('/', [App\Http\Controllers\Backoffice\DashboardController::class, 'index'])
        ->name('backoffice.dashboard');

    // -------------------------------------------------------------------------
    // 시스템 관리
    // -------------------------------------------------------------------------

    // 메뉴 관리
    Route::resource('admin-menus', AdminMenuController::class, [
        'names' => 'backoffice.admin-menus',
        'parameters' => ['admin-menus' => 'menu'],
        'except' => ['show']
    ]);

    // 메뉴 순서 업데이트
    Route::post('admin-menus/update-order', [AdminMenuController::class, 'updateOrder'])
        ->name('backoffice.admin-menus.update-order');


    // 기본설정 관리
    Route::get('setting', [SettingController::class, 'index'])
        ->name('backoffice.setting.index');
    Route::post('setting', [SettingController::class, 'update'])
        ->name('backoffice.setting.update');

    // 접속 로그 관리
    Route::get('logs/access', [LogController::class, 'access'])
        ->name('backoffice.logs.access');

    // 관리자 계정 관리
    Route::resource('admins', AdminController::class, [
        'names' => 'backoffice.admins'
    ]);

    // -------------------------------------------------------------------------
    // 콘텐츠 관리
    // -------------------------------------------------------------------------

    // 이미지 업로드
    Route::post('upload-image', function (Request $request) {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('uploads/editor', 'public');

            return response()->json([
                'uploaded' => true,
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'uploaded' => false,
            'error' => ['message' => '이미지 업로드에 실패했습니다.']
        ]);
    });

    // 게시글 관리 (특정 게시판) - 먼저 정의
    Route::prefix('board-posts/{slug}')->name('backoffice.board-posts.')->group(function () {
        Route::get('/', [BoardPostController::class, 'index'])->name('index');
        Route::get('/create', [BoardPostController::class, 'create'])->name('create');
        Route::post('/', [BoardPostController::class, 'store'])->name('store');
        Route::get('/{post}', [BoardPostController::class, 'show'])->name('show');
        Route::get('/{post}/edit', [BoardPostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [BoardPostController::class, 'update'])->name('update');
        Route::delete('/{post}', [BoardPostController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-destroy', [BoardPostController::class, 'bulkDestroy'])->name('bulk_destroy');
    });

    // 게시판 관리 - 나중에 정의
    Route::resource('boards', BoardController::class, [
        'names' => 'backoffice.boards'
    ])->except(['show']); // show는 제외 (게시글 목록과 충돌)

    // 게시판 스킨 관리
    Route::resource('board-skins', BoardSkinController::class, [
        'names' => 'backoffice.board-skins',
        'parameters' => ['board-skins' => 'boardSkin']
    ]);

    // 게시판 스킨 템플릿 편집
    Route::prefix('board-skins/{boardSkin}')->name('backoffice.board-skins.')->group(function () {
        Route::get('template', [BoardSkinController::class, 'editTemplate'])
            ->name('edit_template');
        Route::post('template', [BoardSkinController::class, 'updateTemplate'])
            ->name('update_template');
    });

    // 게시글 관리
    Route::resource('posts', BoardPostController::class, [
        'names' => 'backoffice.posts'
    ]);

    // 회원 관리
    Route::resource('users', UserController::class, [
        'names' => 'backoffice.users'
    ]);

    // 배너 관리
    Route::resource('banners', BannerController::class, [
        'names' => 'backoffice.banners'
    ]);
    Route::post('banners/update-order', [BannerController::class, 'updateOrder'])->name('backoffice.banners.update-order');

});

// =============================================================================
// 프론트엔드 라우트 (일반 사용자 접근)
// =============================================================================

// 사용자 프로필
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [FrontendUserController::class, 'profile'])
        ->name('index');
    Route::post('/', [FrontendUserController::class, 'updateProfile'])
        ->name('update');
});


