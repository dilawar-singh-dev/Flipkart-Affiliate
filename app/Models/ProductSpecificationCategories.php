<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecificationCategories extends Model
{
    use HasFactory;

    public function detailSpecifications(){
        return $this->hasMany(ProductSpecifications::class,'product_specification_category_id','id');
    }

    protected $hidden = [
        'id',
        'product_id',
        'created_at',
        'updated_at',
    ];
}
