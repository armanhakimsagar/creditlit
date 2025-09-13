<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTypes extends Model
{
    use HasFactory;

    protected $table='student_type';
    protected $fillable = [
        'name',
        'status',
        'is_trash',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by'
    ];
}
