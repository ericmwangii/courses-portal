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
        $lessonsWatched = $user->watchedVideos()->count();

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
