<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use Illuminate\Support\Facades\Log;

class BadgeUnlockedListener
{
    public function handle(BadgeUnlocked $event)
    {

        $event->user->badges()->attach($event->badge_id, ['created_at' => now(), 'updated_at' => now()]);
        Log::info("Badge Unlocked: {$event->badge_name} for user ID: {$event->user->id}");
    }
}
