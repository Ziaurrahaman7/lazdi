<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    public function order_shipping(){
        return $this->belongsTo(OrderShipping::class, 'id', 'order_id');
    }

    public function order_products(){
        return $this->hasMany(OrderProduct::class, 'id', 'order_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function couriers()
    {
        return $this->hasMany(Courier::class,'id','courier_service');
    }

}
