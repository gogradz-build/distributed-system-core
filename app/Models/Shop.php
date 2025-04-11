<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'credit_limit',
        'credit',
        'ref_id',
        'address',
        'owner_name',
        'email',
        'telephone_number',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
