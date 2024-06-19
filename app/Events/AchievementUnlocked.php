<?php

namespace App\Events;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AchievementUnlocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $achievement_name;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct($achievement_name, User $user)
    {
        //
        $this->achievement_name = $achievement_name;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

    public function handleLessonWatched(LessonWatched $event)
    {
        $user = $event->user;
        $lessonsWatched = $user->watched()->count();

        $this->checkLessonAchievements($user, $lessonsWatched);
    }

    public function handleCommentWritten(CommentWritten $event)
    {
        $user = $event->user;
        $commentsWritten = $user->comments()->count();

        $this->checkCommentAchievements($user, $commentsWritten);
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
        $user->achievements()->attach(Achievement::where('name', $achievement)->first());
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
