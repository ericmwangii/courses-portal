<?php

namespace Database\Seeders;

use App\Models\CommentWritten;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentWrittenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::first();

        // Create comments written by the user
        for ($i = 0; $i < 3; $i++) {
            CommentWritten::create(['user_id' => $user->id]);
        }
    }
}
