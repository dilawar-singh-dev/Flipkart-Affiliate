<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecifications extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'product_specification_category_id',
        'created_at',
        'updated_at',
    ];

}
