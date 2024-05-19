<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierBooking extends Model
{
    use HasFactory;
    protected $table = 'courier_bookings';

    protected $fillable = [
        'courier_id', 'order_id', 'invoice', 'consignment_id', 'tracking_code', 'details', 'status',
    ];

    protected $cast =['details'=>"array"];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

}
