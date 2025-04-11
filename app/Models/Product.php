<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'image',
        'cost',
        'price',
        'category_id',
        'brand_id',
    ];

    public function variations()
    {
        return $this->belongsToMany(Variation::class, 'product_variation')
            ->withPivot('variation_value_id')
            ->withTimestamps();
    }

    public function productVariations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function variationList()
    {
        return $this->hasOneThrough(VariationList::class, ProductVariation::class, 'product_id', 'id', 'id', 'variation_list_id');
    }

    public function variationLists()
    {
        return $this->hasManyThrough(VariationList::class, ProductVariation::class, 'product_id', 'variation_id', 'id', 'id');
    }

    public function warehouseStocks()
    {
        return $this->hasMany(WarehouseStock::class);
    }


    public function brand()
    {
        return $this->BelongsTo(Brand::class);
    }

    public function category()
    {
        return $this->BelongsTo(Category::class);
    }
}
