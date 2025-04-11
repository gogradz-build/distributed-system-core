<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'variation_id',
    ];

    public function values()
    {
        return $this->hasMany(VariationValue::class);
    }
}
