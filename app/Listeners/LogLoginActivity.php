<?php

namespace App\Listeners;

use App\Models\LoginActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogLoginActivity
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
    public function handle(Login $event): void
    {
        // Prevent duplicate entries by checking if a login was already recorded
        // for this user in the last few seconds
        $recentLogin = LoginActivity::where('user_id', $event->user->getAuthIdentifier())
            ->where('login_at', '>=', now()->subSeconds(5))
            ->exists();
            
        if ($recentLogin) {
            return;
        }
        
        LoginActivity::create([
            'user_id' => $event->user->getAuthIdentifier(),
            'ip_address' => request()->ip(),
            'login_at' => now(),
        ]);
    }
}
