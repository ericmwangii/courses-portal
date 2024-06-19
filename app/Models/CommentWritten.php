<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentWritten extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comments_written';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



