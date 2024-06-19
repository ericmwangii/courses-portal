<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Badge;
use Illuminate\Support\Facades\Log;

class AchievementUnlockedListener
{
    public function handle(AchievementUnlocked $event)
    {
        $user = $event->user;
        Log::info("Achievement Unlocked: {$event->achievement_name} for user {$user->id}");

        $this->checkAndUnlockBadge($user);
    }

    private function checkAndUnlockBadge($user)
    {
        $achievementsCount = $user->achievements()->count();
        $badges = Badge::orderBy('threshold')->get();

        foreach ($badges as $badge) {
            if ($achievementsCount >= $badge->threshold && !$user->badges->contains($badge->id)) {
                event(new BadgeUnlocked($badge->id, $badge->name, $user));
            }
        }
    }
}
