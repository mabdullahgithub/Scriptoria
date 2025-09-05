<?php

namespace App\Providers;

use App\Events\ArticleSubmitted;
use App\Listeners\LogArticleSubmission;
use App\Listeners\LogLoginActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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
        // Register event listeners
        Event::listen(
            ArticleSubmitted::class,
            LogArticleSubmission::class,
        );

        Event::listen(
            Login::class,
            LogLoginActivity::class,
        );
    }
}
