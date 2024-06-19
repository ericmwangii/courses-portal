<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EventTestController extends Controller
{
    public function testCommentEvent()
    {
        try {
            $user = User::find(1);
            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            $comment = $user->comments()->create([
                'course_id' => 1,
                'content' => 'This is a test comment.',
                'created_at' => now(),
            ]);

            event(new CommentWritten($user, $comment));

            return response()->json(['message' => 'Comment Written event dispatched.']);
        } catch (\Exception $e) {
            Log::error('Error dispatching CommentWritten event: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to dispatch Comment Written event.'], 500);
        }
    }

    public function testLessonEvent()
    {
        try {
            $user = User::find(1);
            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            $watchedVideo = $user->watched()->create([
                'course_id' => 1,
                'video_id' => 1,
                'created_at' => now(),
            ]);

            event(new LessonWatched($user, $watchedVideo));

            return response()->json(['message' => 'Lesson Watched event dispatched.']);
        } catch (\Exception $e) {
            Log::error('Error dispatching LessonWatched event: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to dispatch Lesson Watched event.'], 500);
        }
    }
}
