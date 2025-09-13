<?php

namespace App\Modules\Contact\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table='contacts';
    
    protected $fillable = [
        'full_name',
        'cp_phone_no',
        'cp_email',
        'address',
        'status',
        'type',
        'key_personnel_id'
    ];
}
