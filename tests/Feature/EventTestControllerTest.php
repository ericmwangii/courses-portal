<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTestControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');

        $this->artisan('db:seed');

        // Create the required user, course, and video
        User::factory()->create();
        Course::factory()->create();
        Video::factory()->create();
    }

    /**
     * Test the /test-comment-event route.
     *
     * @return void
     */
    public function testCommentEvent()
    {
        $user = User::first();
        $course = Course::first();

        $this->actingAs($user);

        $response = $this->postJson(route('test-comment-event'), [
            'course_id' => $course->id,
            'content' => 'This is a test comment.',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'content' => 'This is a test comment.',
        ]);
    }

    /**
     * Test the /test-lesson-event route.
     *
     * @return void
     */
    public function testLessonEvent()
    {
        $user = User::first();
        $course = Course::first();
        $video = Video::first();

        $this->actingAs($user);

        $response = $this->postJson(route('test-lesson-event'), [
            'course_id' => $course->id,
            'video_id' => $video->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('watched_videos', [
            'video_id' => $video->id,
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }
}
