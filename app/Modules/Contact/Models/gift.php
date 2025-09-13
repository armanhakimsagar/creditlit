<?php

namespace App\Modules\Contact\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;
    protected $table='gifts';
    
    protected $fillable = [
        'name',
        'type',
        'cost',
        'customer_type',
        'bank_id',
        'branch_id',
        'key_personnel',
        'delivered_by',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'is_trash',
        'status'
    ];
}
