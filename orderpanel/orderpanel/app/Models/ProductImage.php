<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    public $table = 'images';
    // Define the relationship between ProductImage and Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
