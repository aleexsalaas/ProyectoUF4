<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'property_id', 'quantity', 'price'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
