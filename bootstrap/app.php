<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\BackOfficeAuth;
use App\Http\Middleware\TrackVisitor;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 백오피스 경로에 대해 BackOfficeAuth 미들웨어 등록
        $middleware->group('backoffice', [
            BackOfficeAuth::class,
        ]);
        
        // 방문자 추적 미들웨어를 글로벌로 등록 (최상위에서 실행)
        $middleware->append(TrackVisitor::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
