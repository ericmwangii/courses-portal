<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Badge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'threshold'];

    /**
     * The users that belong to the badge.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badge')->withTimestamps();
    }
}
