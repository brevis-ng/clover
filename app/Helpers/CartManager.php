<?php

namespace App\Helpers;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Cart;
use App\Models\Product;

class CartManager
{
    protected static $session_key = "clover_carts";
    protected static $customer_sskey = "clover_customer_session";

    public static function customer(): Customer|null
    {
        return session()->get(static::$customer_sskey);
    }

    public static function storeCustomer($data)
    {
        $customer = static::customer();

        if (!$customer) {

            $customer = Customer::firstOrCreate(
                ["id" => intval($data["id"])],
                [
                    "name" => $data["first_name"] . " " . $data["last_name"],
                    "username" => $data["username"],
                ]
            );

            session()->put(static::$customer_sskey, $customer);
        }

        return $customer;
    }

    public static function clearCustomer()
    {
        session()->forget(static::$customer_sskey);

        return true;
    }

    public static function order($customer_id)
    {
        $order = new Order();
        $order->order_number = generateOrderNumber();
        $order->customer_id = $customer_id;
        $order->status = OrderStatus::PENDING;
        $order->shipping_amount = 0;
        $order->total_amount = static::subtotal() + $order->shipping_amount;

        return $order;
    }

    public static function items()
    {
        $items = session()->get(static::$session_key);

        if (!$items || !$items instanceof Collection) {
            return new Collection([]);
        }

        return $items;
    }

    public static function item($item_id)
    {
        $current_items = static::items();

        $item = $current_items->where("id", $item_id)->first();

        return $item;
    }

    public static function add($product, $quantity = 1)
    {
        $items = static::items();
        $item = static::item($product["id"]);

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
        session()->forget(static::$session_key);

        return true;
    }

    public static function count()
    {
        return count(static::items());
    }

    public static function subtotal()
    {
        return static::items()->sum("amount");
    }
}
