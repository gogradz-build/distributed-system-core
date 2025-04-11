<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationValue extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'variation_type_id'];

    public function type()
    {
        return $this->belongsTo(VariationType::class);
    }
}
