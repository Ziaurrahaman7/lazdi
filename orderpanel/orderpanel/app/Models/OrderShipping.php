<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipping extends Model
{
    use HasFactory;
    public $table = 'order_shipping';


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
