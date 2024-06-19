<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LessonWatchedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(LessonWatched $event)
    {
        $user = $event->user;
        $lessonsWatched = $user->watchedVideos()->count();

        $this->checkLessonAchievements($user, $lessonsWatched);
    }

    private function checkLessonAchievements($user, $lessonsWatched)
    {
        $achievements = Achievement::where('type', 'lesson')->orderBy('threshold')->get();

        foreach ($achievements as $achievement) {
            if ($lessonsWatched >= $achievement->threshold && !$user->achievements->contains($achievement)) {
                $this->unlockAchievement($user, $achievement);
            }
        }
    }

    private function unlockAchievement($user, $achievement)
    {
        $user->achievements()->attach($achievement);
        event(new AchievementUnlocked($achievement->name, $user));
    }
}
