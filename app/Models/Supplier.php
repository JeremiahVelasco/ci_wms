<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'address',
        'contact'
    ];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
