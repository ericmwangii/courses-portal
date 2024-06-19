<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $courses = [
            ['title' => 'Course One', 'description' => 'This is the first test course.'],
            ['title' => 'Course Two', 'description' => 'This is the second test course.'],
            ['title' => 'Course Three', 'description' => 'This is the third test course.'],
            ['title' => 'Course Four', 'description' => 'This is the fourth test course.'],
        ];

        foreach ($courses as $courseData) {
            Course::create($courseData);
        }
    }
}
