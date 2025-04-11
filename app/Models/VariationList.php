<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationList extends Model
{
    use HasFactory;
    protected $fillable = [
        'variation_values',
        'variation_id',
    ];


    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
