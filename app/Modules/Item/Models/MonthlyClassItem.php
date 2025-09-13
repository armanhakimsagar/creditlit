<?php

namespace App\Modules\Item\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MonthlyClassItem extends Model
{
    use HasFactory;

    protected $table = 'monthly_class_item';

    protected $fillable = [
        'class_id',
        'academic_year_id',
        'item_id',
        'status',
        'is_trash',
        'item_price',
        'month_id'
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
