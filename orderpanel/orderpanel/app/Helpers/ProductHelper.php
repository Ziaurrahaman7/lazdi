<?php
namespace App\Helpers;

use App\Models\Product;
use App\Models\ProductImage;

class ProductHelper
{
    public static function getProductImages($productId)
    {
        // Retrieve the first image for each product_id
        $imageData = ProductImage::whereIn('id', function ($query) {
            $query->selectRaw('MIN(id)')
                  ->from('images')
                  ->groupBy('product_id');
        })
        ->first();

        // Return the images as an array
        return $imageData->toArray();
    }
}
?>
