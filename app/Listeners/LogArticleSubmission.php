<?php

namespace App\Listeners;

use App\Events\ArticleSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogArticleSubmission
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ArticleSubmitted $event): void
    {
        Log::info("Article #{$event->article->id} titled '{$event->article->title}' has been submitted for review by {$event->article->user->name}.");
    }
}
