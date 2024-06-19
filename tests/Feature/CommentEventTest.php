<?php


namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\CommentWritten;

class CommentEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test firing the CommentWritten event.
     *
     * @return void
     */
    public function testCommentWrittenEventIsFired()
    {

        Event::fake();


        $user = User::factory()->create();

        $this->actingAs($user);


        $course = Course::factory()->create();

        $response = $this->postJson("/courses/{$course->id}/comments", [
            'content' => 'This is a test comment.',
        ]);


        $response->assertStatus(201);

        Event::assertDispatched(CommentWritten::class, function ($event) use ($user, $course) {
            return $event->user->id === $user->id && $event->course->id === $course->id;
        });
    }
}
