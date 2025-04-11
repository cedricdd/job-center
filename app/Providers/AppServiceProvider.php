<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\ParallelTesting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paginator::useBootstrapFive();

        Authenticate::redirectUsing(fn () => route('sessions.create')); 

        ParallelTesting::tearDownProcess(function (int $token) {
            Log::debug("Dropping database {$token}");

            DB::statement("drop database if exists `testing_test_{$token}`;");
        });
    }
}
