<?php

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OthersPayment extends Model
{
    use HasFactory;

    protected $table = 'other_payment';

    protected $fillable = [
        'dealer_id',
        'sales_chart_id',
        'payment_amount',
        'transaction_type',
        'payment_invoice',
        'payment_date',
        'AccountTypeId',
        'AccountCategoryId',
        'AccountId',
        'receive_type',
        'status',
        'enum_adjustment_type_id',
        'sales_id',
        'payment_id',
        'note',
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
