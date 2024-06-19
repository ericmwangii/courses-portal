<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\EventTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('users/{user}/achievements', [AchievementController::class, 'show']);
Route::get('/test-comment-event', [EventTestController::class, 'testCommentEvent']);
Route::get('/test-lesson-event', [EventTestController::class, 'testLessonEvent']);
