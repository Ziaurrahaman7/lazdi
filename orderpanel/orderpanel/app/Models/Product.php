<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $table = 'products';
    // Define the relationship between Product and ProductImage
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

}
