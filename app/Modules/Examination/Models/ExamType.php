<?php

namespace App\Modules\Examination\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExamType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
        'is_trash',
    ];


     // boot() function used to insert logged user_id at 'created_by' & 'updated_by'
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