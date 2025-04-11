<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'discount',
        'quantity',
        'paid_amount',
        'return',
    ];


public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'id');
}

public function purchase()
{
    return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
}


}
