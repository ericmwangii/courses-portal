<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\Badge;

class LessonWatchedListener
{
    public function handle(LessonWatched $event)
    {
        $user = $event->user;
        $lessonsWatched = $user->watched()->count();

        $this->checkLessonAchievements($user, $lessonsWatched);
    }

    private function checkLessonAchievements($user, $lessonsWatched)
    {
        $achievements = Achievement::where('type', 'lesson')->get();

        foreach ($achievements as $achievement) {
            if ($lessonsWatched >= $achievement->threshold && !$user->achievements->contains($achievement)) {
                $this->unlockAchievement($user, $achievement);
            }
        }
    }

    private function unlockAchievement($user, $achievement)
    {
        $user->achievements()->attach($achievement);
        AchievementUnlocked::dispatch($achievement->name, $user);

        $this->checkForBadgeUpgrade($user);
    }

    private function checkForBadgeUpgrade($user)
    {
        $achievementsCount = $user->achievements()->count();
        $badges = Badge::orderBy('threshold')->get();

        foreach ($badges as $badge) {
            if ($achievementsCount >= $badge->threshold && !$user->badges->contains('name', $badge->name)) {
                $user->badges()->attach($badge);
                BadgeUnlocked::dispatch($badge->id, $badge->name, $user);
            }
        }
    }
}
