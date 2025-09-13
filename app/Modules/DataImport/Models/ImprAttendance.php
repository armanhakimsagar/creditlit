<?php

namespace App\Modules\DataImport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprAttendance extends Model
{
    protected $table = 'impr_attendance_table';

    protected $fillable = [
        'name', 'punch_date', 'card_no', 'in_gate_name', 'in_time', 'out_gate_name', 'out_time', 'status', 'type', 'batch_id'
    ];
}
