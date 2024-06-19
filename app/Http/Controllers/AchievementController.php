<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class AchievementController extends Controller
{
    /**
     * Display the user's achievements and badges.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        try {

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
        } catch (Exception $e) {
            Log::error('Error retrieving achievements: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to retrieve achievements. Please try again later.'], 500);
        }
    }

    /**
     * Get the next available achievements for the user.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    private function getNextAvailableAchievements(User $user)
    {
        try {
            $achievements = Achievement::all();
            $next_available = [];

            foreach ($achievements as $achievement) {
                if (!$user->achievements->contains($achievement)) {
                    if ($achievement->type === 'lesson' && $user->watchedVideos()->count() < $achievement->threshold) {
                        $next_available[] = $achievement->name;
                    } elseif ($achievement->type === 'comment' && $user->comments()->count() < $achievement->threshold) {
                        $next_available[] = $achievement->name;
                    }
                }
            }

            return $next_available;
        } catch (Exception $e) {
            Log::error('Error calculating next available achievements: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get the current badge of the user.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    private function getCurrentBadge(User $user)
    {
        try {
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
        } catch (Exception $e) {
            Log::error('Error determining current badge: ' . $e->getMessage());
            return 'Beginner';
        }
    }

    /**
     * Get the next badge the user can earn.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    private function getNextBadge(User $user)
    {
        try {
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
        } catch (Exception $e) {
            // Log the exception message
            Log::error('Error determining next badge: ' . $e->getMessage());
            return 'Intermediate';
        }
    }

    /**
     * Get the number of additional achievements needed to unlock the next badge.
     *
     * @param  \App\Models\User  $user
     * @return int
     */
    private function getRemainingToUnlockNextBadge(User $user)
    {
        try {
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
        } catch (Exception $e) {
            Log::error('Error calculating remaining achievements for next badge: ' . $e->getMessage());
            return 0;
        }
    }
}
