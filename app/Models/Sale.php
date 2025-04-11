<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'shop_id',
        'ref_id',
        'total_price',
        'received_amount',
        'order_status',
        'payment_type',
        'status_update_at',
    ];

    public function ref()
    {
        
        return $this->belongsTo(Ref::class, 'ref_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class, 'sale_id', 'id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'sale_id', 'id');
    }
}
