<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AcademicYear extends Model
{
    use HasFactory;
    protected $table = 'academic_years';

    protected $fillable = [
        'start_date',
        'end_date',
        'year',
        'status',
        'is_trash',
        'is_current'
    ];
    public static function boot(){
        parent::boot();
        static::creating(function($query){
            if(Auth::check()){
                $query->created_by = Auth::user()->id;
            }
        });
        static::updating(function($query){
            if(Auth::check()){
                $query->updated_by = Auth::user()->id;
            }
        });
    }
}
