<?php

namespace Database\Seeders;

use App\Models\LessonWatched;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonWatchedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::first();

        // Create lessons watched by the user
        for ($i = 0; $i < 5; $i++) {
            LessonWatched::create(['user_id' => $user->id]);
        }
    }
}
