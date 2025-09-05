<?php

namespace App\Providers;

use App\Events\ArticleSubmitted;
use App\Listeners\LogArticleSubmission;
use App\Listeners\LogLoginActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ArticleSubmitted::class => [
            LogArticleSubmission::class,
        ],
        
        Login::class => [
            LogLoginActivity::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false; // Disable auto-discovery to avoid duplicates
    }
}
