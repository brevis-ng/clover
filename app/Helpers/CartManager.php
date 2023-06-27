<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Cart;
use App\Models\Product;

class CartManager
{
    protected static $session_key = 'clover_carts';

    public static function items()
    {
        $items = session()->get(static::$session_key);

        if (!$items || !$items instanceof Collection) {
            return new Collection([]);
        }

        return $items;
    }

    public static function item(Product $product)
    {
        $current_items = static::items();

        $item = $current_items->where("id", $product->id)->first();

        return $item;
    }

    public static function add(Product $product, $quantity = 1)
    {
        $items = static::items();
        $item = static::item($product);

        if (!$item) {
            $item = Cart::create($product, $quantity);

            $items->put($item->id, $item);
        } else {
            $item->quantity += $quantity;
        }

        session()->put(static::$session_key, $items);

        return $item;
    }

    public static function update($id, $quantity = 1)
    {
        $items = static::items();
        $item = $items->where("id", $id)->first();

        if ($item) {
            $item->quantity += $quantity;

            if ($item->quantity <= 0) {
                return static::remove($id);
            } else {
                $items->put($id, $item);
                session()->put(static::$session_key, $items);
            }

            return $item;
        }
        return null;
    }

    public static function remove($id)
    {
        $items = static::items();
        $item = $items->where("id", $id)->first();

        if ($item) {
            $items->forget($id);
            session()->put(static::$session_key, $items);
            return true;
        }

        return false;
    }

    public static function clear()
    {
        session()->remove(static::$session_key);

        return true;
    }

    public static function count()
    {
        return count(static::items());
    }

    public static function subtotal()
    {
        return static::items()->sum("subtotal");
    }

    public static function total()
    {
        return static::subtotal();
    }
}
