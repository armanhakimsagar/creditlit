<?php

namespace App\Modules\Announcement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weekend extends Model
{
    use HasFactory;
    protected $table='weekend_configurations';
    
    protected $fillable = [
        'day_name',
        'is_weekend',
        'created_at',
        'updated_at'
    ];
}
