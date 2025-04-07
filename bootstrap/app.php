<?php

use App\Http\Middleware\JobSorting;
use Illuminate\Foundation\Application;
use App\Http\Middleware\EmployerSorting;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'jobSorting' => JobSorting::class,
            'employerSorting' => EmployerSorting::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
