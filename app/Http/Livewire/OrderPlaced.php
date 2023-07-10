<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
use App\Models\Customer;
use Livewire\Component;

class OrderPlaced extends Component
{
    public $name;
    public $phone;
    public $address;
    public $payment = "cod";

    protected $rules = [
        "name" => "required|min:6",
        "phone" => "required|numeric|min_digits:10",
        "address" => "required|max:255",
        "payment" => "required",
    ];

    public function mount()
    {
        $this->name = CartManager::customer()?->name;
    }

    public function submit()
    {
        $this->validate();

        $customer = CartManager::customer();
        if (!$customer) {
            $customer = Customer::create([
                "name" => $this->name,
                "phone" => $this->phone,
                "address" => $this->address,
            ]);
        } else {
            $customer->name = $this->name;
            $customer->phone = $this->phone;
            $customer->address = $this->address;
            $customer->save();
        }

        $order = CartManager::order($customer->id);
        $order->payment_method = $this->payment;
        $order->save();

        foreach (CartManager::items() as $item) {
            $order->products()->attach($item->product["id"], [
                "quantity" => $item->quantity,
                "amount" => $item->amount,
            ]);
        }

        $this->emit("tg:orderPlaced", __("admin.order_placed_successfully"));

        session()->flush();
    }

    public function render()
    {
        return view("livewire.order-placed", [
            "cart" => CartManager::items(),
            "subtotal" => CartManager::subtotal(),
        ]);
    }
}
