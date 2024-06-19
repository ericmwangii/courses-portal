<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\EventTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('users/{user}/achievements', [AchievementController::class, 'show']);
Route::post('/test-comment-event', [EventTestController::class, 'testCommentEvent'])->name('test-comment-event');
Route::post('/test-lesson-event', [EventTestController::class, 'testLessonEvent'])->name('test-lesson-event');
