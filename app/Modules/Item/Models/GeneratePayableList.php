<?php

namespace App\Modules\Item\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GeneratePayableList extends Model
{
    use HasFactory;
    protected $table = 'generate_payable_list';

    protected $fillable = [
        'class_id',
        'academic_year_id',
        'status',
        'month_id',
        'date'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            if (Auth::check()) {
                $query->created_by = Auth::user()->id;
            }
        });
        static::updating(function ($query) {
            if (Auth::check()) {
                $query->updated_by = Auth::user()->id;
            }
        });
    }
}
