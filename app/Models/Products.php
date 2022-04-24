<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    public function category(){
        return $this->belongsTo(ProductCategories::class,'product_category_id','id');
    }

    public function shortSpecifications(){
        return $this->hasMany(ProductShortSpecifications::class,'product_id','id');
    }

    public function productSpecificationCategories(){
        return $this->hasMany(ProductSpecificationCategories::class,'product_id','id');
    }
}
