<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_code',
        'economics',
        'islamic_studies',
        'mathemetics',
        'agriculture_science',
        'biology',
        'chemistry',
        'physics',
    ];

}
