<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // protected $table='products';
    public function getTotalPriceAttribute()
    {
        // Accessing the single price and quantity from the model instance
        $singlePrice = intval($this->final_cost);
        $quantity = intval($this->qty);

        // Calculate the total price
        $totalPrice = $quantity * $singlePrice;

        // Return the total price
        return $totalPrice;
    }
}
