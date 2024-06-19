<?php


namespace App\Events;

use App\Models\WatchedVideo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class LessonWatched
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $watched;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, WatchedVideo $watched)
    {
        $this->user = $user;
        $this->watched = $watched;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
