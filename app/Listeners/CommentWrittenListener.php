<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Models\Achievement;
use App\Models\Badge;

class CommentWrittenListener
{
    public function handle(CommentWritten $event)
    {
        $user = $event->user;
        $commentsWritten = $user->comments()->count();

        $this->checkCommentAchievements($user, $commentsWritten);
    }

    private function checkCommentAchievements($user, $commentsWritten)
    {
        $achievements = Achievement::where('type', 'comment')->get();

        foreach ($achievements as $achievement) {
            if ($commentsWritten >= $achievement->threshold && !$user->achievements->contains($achievement)) {
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
