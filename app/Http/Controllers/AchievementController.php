<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\User;

class AchievementController extends Controller
{
    public function show(User $user)
    {
        $unlocked_achievements = $user->achievements()->pluck('name');
        $next_available_achievements = $this->getNextAvailableAchievements($user);
        $current_badge = $this->getCurrentBadge($user);
        $next_badge = $this->getNextBadge($user);
        $remaining_to_unlock_next_badge = $this->getRemainingToUnlockNextBadge($user);

        return response()->json([
            'unlocked_achievements' => $unlocked_achievements,
            'next_available_achievements' => $next_available_achievements,
            'current_badge' => $current_badge,
            'next_badge' => $next_badge,
            'remaining_to_unlock_next_badge' => $remaining_to_unlock_next_badge,
        ]);
    }

    private function getNextAvailableAchievements($user)
    {

        $achievements = Achievement::all();
        $next_available = [];

        foreach ($achievements as $achievement) {
            if (!$user->achievements->contains($achievement)) {
                if ($achievement->type === 'lesson' && $user->watched()->count() < $achievement->threshold) {
                    $next_available[] = $achievement->name;
                } elseif ($achievement->type === 'comment' && $user->comments()->count() < $achievement->threshold) {
                    $next_available[] = $achievement->name;
                }
            }
        }

        return $next_available;
    }

    private function getCurrentBadge($user)
    {

        $achievements_count = $user->achievements()->count();

        if ($achievements_count >= 10) {
            return 'Master';
        } elseif ($achievements_count >= 8) {
            return 'Advanced';
        } elseif ($achievements_count >= 4) {
            return 'Intermediate';
        } else {
            return 'Beginner';
        }
    }

    private function getNextBadge($user)
    {

        $achievements_count = $user->achievements()->count();

        if ($achievements_count < 4) {
            return 'Intermediate';
        } elseif ($achievements_count < 8) {
            return 'Advanced';
        } elseif ($achievements_count < 10) {
            return 'Master';
        } else {
            return 'Master';
        }
    }

    private function getRemainingToUnlockNextBadge($user)
    {

        $achievements_count = $user->achievements()->count();

        if ($achievements_count < 4) {
            return 4 - $achievements_count;
        } elseif ($achievements_count < 8) {
            return 8 - $achievements_count;
        } elseif ($achievements_count < 10) {
            return 10 - $achievements_count;
        } else {
            return 0;
        }
    }
}
