<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'property_id', 'quantity'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
