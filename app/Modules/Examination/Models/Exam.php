<?php

namespace App\Modules\Examination\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_name',
        'percentage_for_final',
        'class_id',
        'exam_type_id',
        'academic_year_id',
        'status',
        'is_trash',
    ];
}
