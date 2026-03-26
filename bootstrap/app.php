<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// تأكد من استدعاء الكلاس الخاص بالميدل وير هنا
use App\Http\Middleware\TrackVisitors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // تسجيل الميدل وير ضمن مجموعة 'web'
        // هذا يضمن تشغيله فقط عند تصفح روابط routes/web.php
        $middleware->web(append: [
            TrackVisitors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
