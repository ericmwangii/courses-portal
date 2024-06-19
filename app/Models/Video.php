<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'url',
        'created_at',
        'deleted_at',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function watched()
    {
        return $this->hasMany(WatchedVideo::class);
    }
}
