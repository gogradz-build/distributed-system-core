<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'old_quantity',
        'new_quantity',
        'remark',
    ];

}
