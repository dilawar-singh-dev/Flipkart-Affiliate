<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'products_url',
        'created_at',
        'updated_at',
    ];
}
