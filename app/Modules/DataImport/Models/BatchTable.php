<?php

namespace App\Modules\DataImport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchTable extends Model
{
    protected $table = 'batch_import_table';

    protected $fillable = [
        'name','type'
    ];

}
