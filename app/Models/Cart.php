<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $incrementing = false;

    protected $attributes = ['product', 'quantity', 'amount'];

    public static function create($product, $quantity = 1)
    {
        $cart = new Cart();
        $cart->product = $product;
        $cart->quantity = $quantity;

        $cart->id = $product['id'];

        $cart->amount = $product['price'] * $quantity;

        return $cart;
    }

    public function amount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->product['price'] * $this->quantity,
        );
    }
}
