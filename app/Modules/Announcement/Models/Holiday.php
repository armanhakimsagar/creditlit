<?php

namespace App\Modules\Announcement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;
    protected $table='holiday';
    
    protected $fillable = [
        'title',
        'from_date',
        'to_date',
        'photo',
        'details',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'is_trash',
    ];
}

