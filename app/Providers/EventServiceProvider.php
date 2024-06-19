<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Listeners\CommentWrittenListener;
use App\Listeners\LessonWatchedListener;
use App\Listeners\AchievementUnlockedListener;
use App\Listeners\BadgeUnlockedListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        LessonWatched::class => [
            LessonWatchedListener::class,
        ],
        CommentWritten::class => [
            CommentWrittenListener::class,
        ],
        AchievementUnlocked::class => [
            AchievementUnlockedListener::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
