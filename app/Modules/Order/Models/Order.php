<?php

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table='orders';
    protected $fillable = [
        'company_name',
        'country_id',
        'order_invoice_no',
        'cm_email',
        'cm_reg_no',
        'cm_website',
        'cm_phone',
        'cm_address',
        'cm_note',
        'customer_type',
        'bank_id',
        'bank_reference',
        'key_personnel_id',
        'selling_price',
        'pending_attachment',
        'processing_attachment',
        'query_attachment',
        'cancel_attachment',
        'complete_attachment',
        'supplier_id',
        'supplier_reference',
        'buying_price',
        'profit',
        'is_generate_invoice',
        'payment_status',
        'is_trash',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'order_status',
        'processing_note',
        'processing_at',
        'processing_by',
        'query_note',
        'query_at',
        'query_by',
        'cancel_note',
        'cancel_at',
        'cancel_by',
        'completed_note',
        'completed_at',
        'completed_by',
        'delivered_note',
        'delivered_at',
        'delivered_by'
    ];
}