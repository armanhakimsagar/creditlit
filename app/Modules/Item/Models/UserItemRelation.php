<?php

namespace App\Modules\Item\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserItemRelation extends Model
{
    use HasFactory;

    protected $table = 'user_product_relation';

    protected $fillable = [

        'product_relation_id',
        'auth_id',
        'name',
        'parent_id',
        'type',
        'slug',
        'status',
        'is_trash'
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
