<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_id',
        'sale_id',
        'shop_id',
        'user_id',
        'payment_status',
        'total_price',
        'reason',
        'by_credit',
    ];

    public function ref()
    {

        return $this->belongsTo(Ref::class, 'ref_id', 'id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleReturnItem::class, 'sale_return_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
