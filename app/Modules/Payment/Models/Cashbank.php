<?php

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cashbank extends Model
{
    use HasFactory;

    protected $table = 'cash_banks';

    protected $fillable = [

        'invoice_date',
        'invoice_no',
        'cheque_no',
        'payment_type',
        'amount',
        'note',
        'dealer_id',
        'status',
        'customer_id',
        'source_flag'
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
