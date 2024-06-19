<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'type', 'threshold'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievement')
            ->using(UserAchievement::class)
            ->withTimestamps();
    }
}
