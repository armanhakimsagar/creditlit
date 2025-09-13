<?php

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AccountList extends Model
{
    use HasFactory;

    protected $table = 'accountlists';

    protected $fillable = [
        'AccountId',
        'AccountTypeId',
        'AccountCategoryId',
        'ShortName',
        'BankName',
        'BankBranch',
        'AccountName',
        'AccountNumber',
        'ManagedByUserName',
        'ManagedbyUserId',
        'DefaultOutlet',
        'status'
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
