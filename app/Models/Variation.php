<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function types()
    {
        return $this->hasMany(VariationType::class);
    }

    public function variationLists()
    {
        return $this->hasMany(VariationList::class);
    }
}
