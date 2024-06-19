<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');


        User::truncate();
        Achievement::truncate();
        Badge::truncate();
        DB::table('user_achievement')->truncate();
        DB::table('user_badge')->truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            UserSeeder::class,
            AchievementsSeeder::class,
            BadgeSeeder::class,
            CourseSeeder::class,
            VideoSeeder::class
        ]);
    }
}
