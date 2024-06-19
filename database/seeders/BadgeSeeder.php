<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Badge::create(['name' => 'Beginner', 'threshold' => 0]);
        Badge::create(['name' => 'Intermediate', 'threshold' => 4]);
        Badge::create(['name' => 'Advanced', 'threshold' => 8]);
        Badge::create(['name' => 'Master', 'threshold' => 10]);
    }


}
