<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LessonWatchedListener
{

    use InteractsWithQueue;


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
    public function handle(LessonWatched $event)
    {
        $user = $event->user;
        $lessonsWatched = $user->watched()->count();

        $this->checkLessonAchievements($user, $lessonsWatched);
    }

    private function checkLessonAchievements($user, $lessonsWatched)
    {
        $achievements = [
            1 => 'First Lesson Watched',
            5 => '5 Lessons Watched',
            10 => '10 Lessons Watched',
            25 => '25 Lessons Watched',
            50 => '50 Lessons Watched',
        ];

        foreach ($achievements as $threshold => $achievement) {
            if ($lessonsWatched >= $threshold && !$user->achievements->contains('name', $achievement)) {
                $this->unlockAchievement($user, $achievement);
            }
        }
    }

    private function unlockAchievement($user, $achievement)
    {
        $achievementModel = Achievement::where('name', $achievement)->first();
        $user->achievements()->attach($achievementModel);
        AchievementUnlocked::dispatch($achievement, $user);

        $this->checkForBadgeUpgrade($user);
    }

    private function checkForBadgeUpgrade($user)
    {
        $achievementsCount = $user->achievements()->count();

        $badges = [
            4 => 'Intermediate',
            8 => 'Advanced',
            10 => 'Master',
        ];

        foreach ($badges as $threshold => $badge) {
            if ($achievementsCount >= $threshold && !$user->badges->contains('name', $badge)) {
                BadgeUnlocked::dispatch($badge, $user);
            }
        }
    }

}
