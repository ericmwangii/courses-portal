<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $user->watched()->create([]);
        }

        for ($i = 0; $i < 3; $i++) {
            $user->comments()->create([]);
        }
    }
}
