<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Learning extends Model
{
    use HasFactory;

    protected $table = 'learnings';

    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'video_link',
        'course_id',
        'status'
    ];

}
