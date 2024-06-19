<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserBadge extends Pivot
{
    protected $table = 'user_badge';

    protected $fillable = [
        'user_id',
        'badge_id',
        'created_at',
        'updated_at'
    ];
}
