<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_id',
        'bank_name',
        'cheque_number',
        'amount',
        'expire_date',
    ];
}
