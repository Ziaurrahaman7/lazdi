<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'name', 'api_url', 'api_key', 'secret_key', 'value', 'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class,'id','courier_service');
    }

}
