<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\RecruitmentController;

// =============================================================================
// 기본 라우트 파일
// =============================================================================

// 메인 페이지
Route::get('/', [HomeController::class, 'index'])->name('home');

// 기업정보 관련 라우트
Route::prefix('information')->name('information.')->group(function () {
    Route::get('/ceo-message', [InformationController::class, 'ceoMessage'])->name('ceo-message');
    Route::get('/about-company', [InformationController::class, 'aboutCompany'])->name('about-company');
    Route::get('/history', [InformationController::class, 'history'])->name('history');
    Route::get('/quality-environmental', [InformationController::class, 'qualityEnvironmental'])->name('quality-environmental');
    Route::get('/safety-health', [InformationController::class, 'safetyHealth'])->name('safety-health');
    Route::get('/ethical', [InformationController::class, 'ethical'])->name('ethical');
});

// 사업분야 관련 라우트
Route::prefix('business')->name('business.')->group(function () {
    Route::get('/imported-automobiles', [BusinessController::class, 'importedAutomobiles'])->name('imported-automobiles');
    Route::get('/port-logistics', [BusinessController::class, 'portLogistics'])->name('port-logistics');
    Route::get('/special-vehicle', [BusinessController::class, 'specialVehicle'])->name('special-vehicle');
});

// 인재채용 관련 라우트
Route::prefix('recruitment')->name('recruitment.')->group(function () {
    Route::get('/ideal-employee', [RecruitmentController::class, 'idealEmployee'])->name('ideal-employee');
    Route::get('/personnel', [RecruitmentController::class, 'personnel'])->name('personnel');
    Route::get('/welfare', [RecruitmentController::class, 'welfare'])->name('welfare');
    Route::get('/information', [RecruitmentController::class, 'information'])->name('information');
});

// 인증 관련 라우트
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
// 분리된 라우트 파일들 포함
// =============================================================================

// 백오피스 라우트 (관리자 전용)
require __DIR__.'/backoffice.php';