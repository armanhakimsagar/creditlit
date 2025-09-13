<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $table='classes';
    
    protected $fillable = [
        'name',
        'status',
        'weight',
        'created_by',
        'updated_by',
        'is_trash',
    ];

}
