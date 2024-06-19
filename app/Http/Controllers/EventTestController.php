<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventTestController extends Controller
{
    public function testCommentEvent(Request $request)
    {
        try {
            $user = User::find(1);
            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            $comment = $user->comments()->create([
                'course_id' => $request->input('course_id'),
                'content' => $request->input('content'),
                'created_at' => now(),
            ]);

            event(new CommentWritten($user, $comment));

            return response()->json(['message' => 'Comment Written event dispatched.']);
        } catch (\Exception $e) {
            Log::error('Error dispatching CommentWritten event: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to dispatch Comment Written event.'], 500);
        }
    }

    public function testLessonEvent(Request $request)
    {
        try {
            $user = User::find(1);
            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            $watchedVideo = $user->watched()->create([
                'course_id' => $request->input('course_id'),
                'video_id' => $request->input('video_id'),
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
