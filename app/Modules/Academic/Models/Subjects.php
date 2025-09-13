<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    use HasFactory;
    protected $table='subjects';
    
    protected $fillable = [
        'name',
        'sub_code',
        'status',
        'created_by',
        'updated_by',
        'is_trash',
    ];
}
