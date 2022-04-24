<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductShortSpecifications extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'product_id',
        'created_at',
        'updated_at',
    ];
}
