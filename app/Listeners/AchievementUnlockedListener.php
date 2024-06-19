<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Badge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AchievementUnlockedListener
{
    protected $badges = [
        'Beginner' => 0,
        'Intermediate' => 4,
        'Advanced' => 8,
        'Master' => 10,
    ];

    public function __construct()
    {
        //
    }

    public function handle(AchievementUnlocked $event): void
    {
        $user = $event->user;

        Log::info("Achievement Unlocked: {$event->achievement_name} for user {$user->id}");

        $this->checkAndUnlockBadge($user);
    }

    private function checkAndUnlockBadge($user)
    {
        $achievementsCount = $user->achievements()->count();
        Log::info("Checking badges for user {$user->id}. Achievements Count: {$achievementsCount}");

        $badges = Badge::orderBy('threshold')->get();

        foreach ($badges as $badge) {
            if ($achievementsCount >= $badge->threshold && !$user->badges->contains('name', $badge->name)) {
                $user->badges()->attach($badge);
                Log::info("Badge Unlocked: {$badge->name} for user {$user->id}");
                event(new BadgeUnlocked($badge->name, $user));
            }
        }
    }
}
