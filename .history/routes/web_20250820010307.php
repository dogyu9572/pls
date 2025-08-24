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

Route::get('/', function () {
    return view('welcome');
});

// 사용자 인증 라우트 직접 정의 (Auth::routes() 대신)
// 로그인 라우트
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 회원가입 라우트
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// 비밀번호 재설정 라우트
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// 사용자 프로필 라우트
Route::get('/profile', [FrontendUserController::class, 'profile'])->name('profile');
Route::post('/profile', [FrontendUserController::class, 'updateProfile'])->name('profile.update');

// 백오피스 로그인 라우트
Route::get('/backoffice/login', [AuthController::class, 'showLoginForm'])->name('backoffice.login');
Route::post('/backoffice/login', [AuthController::class, 'login']);
Route::get('/backoffice/logout', [AuthController::class, 'logout'])->name('backoffice.logout');

// 백오피스 라우트 그룹에 미들웨어 적용
Route::middleware('backoffice')->prefix('backoffice')->group(function () {
    // 백오피스 대시보드
    Route::get('/', [App\Http\Controllers\Backoffice\DashboardController::class, 'index'])->name('backoffice.dashboard');

    // 관리자 메뉴 관리
    Route::resource('menus', AdminMenuController::class, [
        'names' => 'backoffice.menus',
        'except' => ['show']
    ]);

    // 메뉴 순서 업데이트 라우트
    Route::post('menus/update-order', [AdminMenuController::class, 'updateOrder'])
        ->name('backoffice.menus.update-order');

    // 기본설정 관리 라우트
    Route::get('setting', [SettingController::class, 'index'])->name('backoffice.setting.index');
    Route::post('setting', [SettingController::class, 'update'])->name('backoffice.setting.update');

    // 게시판 관리 라우트
    Route::resource('boards', BoardManageController::class, [
        'names' => 'backoffice.boards'
    ]);

    // 게시판 스킨 관리 라우트
    Route::resource('board_skins', BoardSkinController::class, [
        'names' => 'backoffice.board_skins'
    ]);

    // 게시판 스킨 템플릿 편집 라우트
    Route::get('board_skins/{boardSkin}/template', [BoardSkinController::class, 'editTemplate'])
        ->name('backoffice.board_skins.edit_template');
    Route::post('board_skins/{boardSkin}/template', [BoardSkinController::class, 'updateTemplate'])
        ->name('backoffice.board_skins.update_template');

    // 게시글 관리 라우트
    Route::resource('posts', BoardPostController::class, [
        'names' => 'backoffice.posts'
    ]);

    // 회원 관리 라우트
    Route::resource('users', UserController::class, [
        'names' => 'backoffice.users'
    ]);

    // 접속 로그 관리 라우트
    Route::get('logs/access', [LogController::class, 'access'])->name('backoffice.logs.access');

    // 관리자 계정 관리 라우트
    Route::resource('admins', AdminController::class, [
        'names' => 'backoffice.admins'
    ]);

    // 게시판 뷰어 (관리자용)
    Route::prefix('board-viewer')->name('backoffice.board_viewer.')->group(function () {
        Route::get('/', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'index'])
            ->name('index');
        Route::get('/{slug}', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'showBoard'])
            ->name('show_board');
        Route::get('/{slug}/posts/{id}', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'showPost'])
            ->name('show_post');
        Route::get('/{slug}/create', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'createPost'])
            ->name('create_post');
        Route::post('/{slug}/create', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'storePost'])
            ->name('store_post');
        Route::get('/{slug}/posts/{id}/edit', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'editPost'])
            ->name('edit_post');
        Route::put('/{slug}/posts/{id}', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'updatePost'])
            ->name('update_post');
        Route::delete('/{slug}/posts/{id}', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'destroyPost'])
            ->name('destroy_post');
        Route::post('/{slug}/posts/{id}/comments', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'storeComment'])
            ->name('store_comment');
        Route::delete('/{slug}/posts/{id}/comments/{comment_id}', [App\Http\Controllers\Backoffice\AdminBoardViewController::class, 'destroyComment'])
            ->name('destroy_comment');
    });

    // 여기에 다른 백오피스 라우트 추가
});

// 프론트엔드 게시판 라우트
Route::prefix('boards')->name('boards.')->group(function () {
    // 게시판 목록
    Route::get('/{slug}', [BoardController::class, 'index'])->name('index');

    // 게시글 작성 폼
    Route::get('/{slug}/create', [BoardController::class, 'create'])->name('create');

    // 게시글 저장
    Route::post('/{slug}', [BoardController::class, 'store'])->name('store');

    // 게시글 조회
    Route::get('/{slug}/{id}', [BoardController::class, 'show'])->name('show');

    // 게시글 수정 폼
    Route::get('/{slug}/{id}/edit', [BoardController::class, 'edit'])->name('edit');

    // 게시글 업데이트
    Route::put('/{slug}/{id}', [BoardController::class, 'update'])->name('update');

    // 게시글 삭제
    Route::delete('/{slug}/{id}', [BoardController::class, 'destroy'])->name('destroy');

    // 비밀번호 확인 폼
    Route::get('/{slug}/{post_id}/password-check/{action}', [BoardController::class, 'showPasswordCheckForm'])
        ->name('password_check');

    // 비밀번호 검증
    Route::post('/{slug}/{post_id}/password-check/{action}', [BoardController::class, 'checkPassword'])
        ->name('check_password');

    // 댓글 작성
    Route::post('/{slug}/{post_id}/comments', [BoardController::class, 'storeComment'])
        ->name('comments.store');

    // 댓글 삭제
    Route::delete('/{slug}/{post_id}/comments/{comment_id}', [BoardController::class, 'destroyComment'])
        ->name('comments.destroy');

    // 댓글 비밀번호 확인 폼
    Route::get('/{slug}/{post_id}/comments/{comment_id}/password-check', [BoardController::class, 'showCommentPasswordCheckForm'])
        ->name('comments.password_check');

    // 댓글 비밀번호 검증
    Route::post('/{slug}/{post_id}/comments/{comment_id}/password-check', [BoardController::class, 'checkCommentPassword'])
        ->name('comments.check_password');
});
