<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Comment;
use App\Models\WatchedVideo;
use App\Models\Course;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\DB;

class UserAchievementSeeder extends Seeder
{
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');


        User::truncate();
        DB::table('achievements')->truncate();
        DB::table('badges')->truncate();
        DB::table('user_achievement')->truncate();
        DB::table('user_badge')->truncate();
        DB::table('comments')->truncate();
        DB::table('watched_videos')->truncate();


        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seedAchievements();


        $this->seedBadges();


        $courses = Course::factory()->count(2)->create();

        $this->createUsersWithAchievements($courses);
    }

    private function seedAchievements()
    {
        Achievement::create(['name' => 'First Comment Written', 'type' => 'comment', 'threshold' => 1]);
        Achievement::create(['name' => '3 Comments Written', 'type' => 'comment', 'threshold' => 3]);
        Achievement::create(['name' => '5 Comments Written', 'type' => 'comment', 'threshold' => 5]);
        Achievement::create(['name' => '10 Comments Written', 'type' => 'comment', 'threshold' => 10]);
        Achievement::create(['name' => '20 Comments Written', 'type' => 'comment', 'threshold' => 20]);

        Achievement::create(['name' => 'First Video Watched', 'type' => 'lesson', 'threshold' => 1]);
        Achievement::create(['name' => '5 Videos Watched', 'type' => 'lesson', 'threshold' => 5]);
        Achievement::create(['name' => '10 Videos Watched', 'type' => 'lesson', 'threshold' => 10]);
        Achievement::create(['name' => '25 Videos Watched', 'type' => 'lesson', 'threshold' => 25]);
        Achievement::create(['name' => '50 Videos Watched', 'type' => 'lesson', 'threshold' => 50]);
    }

    private function seedBadges()
    {
        Badge::create(['name' => 'Beginner', 'threshold' => 0]);
        Badge::create(['name' => 'Intermediate', 'threshold' => 4]);
        Badge::create(['name' => 'Advanced', 'threshold' => 8]);
        Badge::create(['name' => 'Master', 'threshold' => 10]);
    }

    private function createUsersWithAchievements($courses)
    {
        $users = User::factory()->count(4)->create();


        $achievementSets = [
            1 => ['First Comment Written'], // 1 achievement
            2 => ['First Comment Written', 'First Video Watched', '3 Comments Written', '5 Videos Watched'], // 4 achievements
            3 => ['First Comment Written', 'First Video Watched', '3 Comments Written', '5 Videos Watched', '5 Comments Written', '10 Videos Watched', '10 Comments Written', '25 Videos Watched'], // 8 achievements
            4 => ['First Comment Written', 'First Video Watched', '3 Comments Written', '5 Videos Watched', '5 Comments Written', '10 Videos Watched', '10 Comments Written', '25 Videos Watched', '20 Comments Written', '50 Videos Watched'] // 10 achievements
        ];

        foreach ($users as $index => $user) {
            $set = $achievementSets[$index + 1];
            $achievements = Achievement::whereIn('name', $set)->get();


            foreach ($achievements as $achievement) {
                UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                ]);
            }


            $this->createComments($user, $courses->first(), $index + 1);
            $this->createWatchedVideos($user, $courses, $index + 1);


            $badges = Badge::orderBy('threshold')->get();
            foreach ($badges as $badge) {
                if ($user->achievements()->count() >= $badge->threshold) {
                    $user->badges()->attach($badge);
                }
            }
        }
    }

    private function createComments($user, $course, $count)
    {
        for ($i = 0; $i < $count * 5; $i++) {
            Comment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'content' => 'This is a test comment ' . ($i + 1),
            ]);
        }
    }

    private function createWatchedVideos($user, $courses, $count)
    {
        for ($i = 0; $i < $count * 5; $i++) {
            WatchedVideo::create([
                'user_id' => $user->id,
                'course_id' => $courses[$i % $courses->count()]->id,
            ]);
        }
    }
}
