<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use App\Models\Video;

class VideoSeeder extends Seeder
{
    public function run()
    {

        $courses = Course::all();

        foreach ($courses as $course) {
            Video::create([
                'course_id' => $course->id,
                'title' => 'Introduction to ' . $course->title,
                'description' => 'This is a test video for ' . $course->title,
                'url' => 'http://test.com/videos/'
            ]);

            Video::create([
                'course_id' => $course->id,
                'title' => 'Advanced ' . $course->title,
                'description' => 'This is a test video for ' . $course->title,
                'url' => 'http://tets.com/videos-two/'
            ]);
        }
    }
}
