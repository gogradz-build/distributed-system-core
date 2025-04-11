<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ref extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'address',
        'email',
        'nic_number',
        'register_number',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
