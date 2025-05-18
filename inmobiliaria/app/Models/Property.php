<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model {
    protected $fillable = ['name', 'description', 'price', 'type', 'location', 'user_id', 'status', 'buyer_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
