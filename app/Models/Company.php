<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'website',
        'description',
        'keyword',
        'address',
        'contact',
        'facebook',
        'email',
        'twitter',
        'youtube',
        'owner'
    ];
}
