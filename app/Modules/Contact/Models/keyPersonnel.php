<?php

namespace App\Modules\Contact\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class keyPersonnel extends Model
{
    use HasFactory;
    protected $table='key_personnel';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'father_name',
        'mother_name',
        'cp_phone_no',
        'cp_email',
        'date_of_birth',
        'nid',
        'nationality',
        'marital_status',
        'spouse_name',
        'child_name',
        'current_job_place',
        'previous_job_place',
        'gender',
        'address',
        'status',
        'photo',
    ];

}
