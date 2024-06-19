<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentWrittenListener
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
    public function handle(CommentWritten $event)
    {
        $user = $event->user;
        $commentsWritten = $user->comments()->count();

        $this->checkCommentAchievements($user, $commentsWritten);
    }

    private function checkCommentAchievements($user, $commentsWritten)
    {
        $achievements = [
            1 => 'First Comment Written',
            3 => '3 Comments Written',
            5 => '5 Comments Written',
            10 => '10 Comments Written',
            20 => '20 Comments Written',
        ];

        foreach ($achievements as $threshold => $achievement) {
            if ($commentsWritten >= $threshold && !$user->achievements->contains('name', $achievement)) {
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
