<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonWatched extends Model
{
    use HasFactory;


    protected $table = 'lessons_watched';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}




