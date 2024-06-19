<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('users/{user}/achievements', [AchievementController::class, 'show']);
Route::post('courses/{course}/comments', [CourseController::class, 'comment']);
Route::post('courses/{course}/video', [CourseController::class, 'watch']);
