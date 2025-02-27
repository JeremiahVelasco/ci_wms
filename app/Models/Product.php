<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'item',
        'description',
        'brand',
        'stock',
        'min_stock'
    ];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
