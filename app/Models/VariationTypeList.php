<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationTypeList extends Model
{
    use HasFactory;

    protected $fillable = ['variation_list_id', 'variation_id', 'variation_type_id','variation_value_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

    public function value()
    {
        return $this->belongsTo(VariationValue::class, 'variation_value_id');
    }
}
