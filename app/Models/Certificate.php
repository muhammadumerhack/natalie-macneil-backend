<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'school',
        'candidate',
        'gender',
        'dob',
        'candidate_no',
        'image',
        'year',
        'institute_id',
    ];

}
