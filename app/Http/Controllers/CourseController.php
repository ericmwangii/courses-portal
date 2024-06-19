<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Course;
use App\Models\Video;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    /**
     * Mark a video as watched by the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\JsonResponse
     */
    public function watch(Request $request, Video $video)
    {
        try {
            $user = $request->user();

            if ($user->watchedVideos()->where('video_id', $video->id)->exists()) {
                return response()->json(['message' => 'Video already watched'], 200);
            }

            $user->watchedVideos()->attach($video);

            event(new LessonWatched($user, $video));

            return response()->json(['message' => 'Video watched and event fired'], 201);
        } catch (Exception $e) {
            Log::error('Error marking video as watched: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to mark video as watched. Please try again later.'], 500);
        }
    }

    /**
     * Add a comment to a course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(Request $request, Course $course)
    {
        try {
            $user = $request->user();
            $content = $request->input('content');

            // Validate the request
            $request->validate([
                'content' => 'required|string',
            ]);

            $comment = $course->comments()->create([
                'user_id' => $user->id,
                'content' => $content,
            ]);

            event(new CommentWritten($user, $course));

            return response()->json(['message' => 'Comment added and event fired', 'comment' => $comment], 201);
        } catch (Exception $e) {

            Log::error('Error adding comment: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to add comment. Please try again later.'], 500);
        }
    }
}
