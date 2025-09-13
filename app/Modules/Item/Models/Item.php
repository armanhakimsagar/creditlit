<?php

namespace App\Modules\Item\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'brand_id',
        'category_id',
        'type_id',
        'model_id',
        'unit_id',
        'dealer_id',
        'name',
        'slug',
        'bin_number',
        'description',
        'list_price',
        'sell_price',
        'image',
        'articlecode',
        'code',
        'color',
        'size',
        'value',
        'isCustom',
        'isBundle',
        'status',
        'vat',
        'isVat_percent',
        'tax',
        'isTax_percent',
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
