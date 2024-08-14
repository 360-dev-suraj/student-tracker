<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAvailability extends Model
{
    use HasFactory;
    protected $table = "student_availability";
    protected $fillable = [
        'student_id',
        'year',
        'month',
        'week',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];
}
