<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class BadgeUnlocked
{
    use Dispatchable, SerializesModels;

    public $badge_id;
    public $badge_name;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param int $badge_id
     * @param string $badge_name
     * @param User $user
     * @return void
     */
    public function __construct($badge_id, $badge_name, User $user)
    {
        $this->badge_id = $badge_id;
        $this->badge_name = $badge_name;
        $this->user = $user;
    }
}
