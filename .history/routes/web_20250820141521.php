<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backoffice\AuthController;
use App\Http\Controllers\Backoffice\AdminMenuController;
use App\Http\Controllers\Backoffice\SettingController;
use App\Http\Controllers\Backoffice\BoardManageController;
use App\Http\Controllers\Backoffice\BoardSkinController;
use App\Http\Controllers\Backoffice\BoardPostController;
use App\Http\Controllers\Backoffice\UserController;
use App\Http\Controllers\Backoffice\LogController;
use App\Http\Controllers\Backoffice\AdminController;
use App\Http\Controllers\BoardController;
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
    Route::resource('menus', AdminMenuController::class, [
        'names' => 'backoffice.menus',
        'except' => ['show']
    ]);
    
    // 메뉴 순서 업데이트
    Route::post('menus/update-order', [AdminMenuController::class, 'updateOrder'])
        ->name('backoffice.menus.update-order');
    
    // 모달 세션 제거
    Route::post('menus/clear-modal', [AdminMenuController::class, 'clearModal'])
        ->name('backoffice.menus.clear-modal');

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
    
    // 게시판 관리
    Route::resource('boards', BoardManageController::class, [
        'names' => 'backoffice.boards'
    ]);

    // 게시판 스킨 관리
    Route::resource('board_skins', BoardSkinController::class, [
        'names' => 'backoffice.board_skins'
    ]);
    
    // 게시판 스킨 템플릿 편집
    Route::prefix('board_skins/{boardSkin}')->name('backoffice.board_skins.')->group(function () {
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

    // -------------------------------------------------------------------------
    // 게시판 뷰어 (관리자용)
    // -------------------------------------------------------------------------
    Route::prefix('board-viewer')->name('backoffice.board_viewer.')->group(function () {
        Route::get('/', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'index'])
            ->name('index');
        Route::get('/{slug}', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'showBoard'])
            ->name('show_board');
        
        // 게시글 관리
        Route::prefix('{slug}/posts')->name('posts.')->group(function () {
            Route::get('/{id}', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'showPost'])
                ->name('show');
            Route::get('/create', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'createPost'])
                ->name('create');
            Route::post('/create', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'storePost'])
                ->name('store');
            Route::get('/{id}/edit', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'editPost'])
                ->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'updatePost'])
                ->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'destroyPost'])
                ->name('destroy');
        });
        
        // 댓글 관리
        Route::prefix('{slug}/posts/{post_id}/comments')->name('comments.')->group(function () {
            Route::post('/', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'storeComment'])
                ->name('store');
            Route::delete('/{comment_id}', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'destroyComment'])
                ->name('destroy');
        });
    });
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

// 게시판
Route::prefix('boards')->name('boards.')->group(function () {
    // 게시판 목록
    Route::get('/{slug}', [BoardController::class, 'index'])
        ->name('index');
    
    // 게시글 관리
    Route::prefix('{slug}')->group(function () {
        Route::get('/create', [BoardController::class, 'create'])
            ->name('create');
        Route::post('/', [BoardController::class, 'store'])
            ->name('store');
        Route::get('/{id}', [BoardController::class, 'show'])
            ->name('show');
        Route::get('/{id}/edit', [BoardController::class, 'edit'])
            ->name('edit');
        Route::put('/{id}', [BoardController::class, 'update'])
            ->name('update');
        Route::delete('/{id}', [BoardController::class, 'destroy'])
            ->name('destroy');
        
        // 비밀번호 확인
        Route::prefix('{post_id}/password-check')->name('password_check.')->group(function () {
            Route::get('/{action}', [BoardController::class, 'showPasswordCheckForm'])
                ->name('show');
            Route::post('/{action}', [BoardController::class, 'checkPassword'])
                ->name('check');
        });
        
        // 댓글 관리
        Route::prefix('{post_id}/comments')->name('comments.')->group(function () {
            Route::post('/', [BoardController::class, 'storeComment'])
                ->name('store');
            Route::delete('/{comment_id}', [BoardController::class, 'destroyComment'])
                ->name('destroy');
            
            // 댓글 비밀번호 확인
            Route::prefix('{comment_id}/password-check')->name('password_check.')->group(function () {
                Route::get('/', [BoardController::class, 'showCommentPasswordCheckForm'])
                    ->name('show');
                Route::post('/', [BoardController::class, 'checkCommentPassword'])
                    ->name('check');
            });
        });
    });
});
