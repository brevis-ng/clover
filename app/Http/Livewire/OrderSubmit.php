<?php

namespace App\Http\Livewire;

use App\Events\OrderPlaced;
use App\Helpers\CartManager;
use Livewire\Component;

class OrderSubmit extends Component
{
    public $name;
    public $phone;
    public $address;
    public $payment = "cod";
    public $notes;
    protected $rules = [
        "name" => "required|max:255",
        "phone" => "required|numeric|min_digits:10",
        "address" => "required|max:255",
        "payment" => "required",
        "notes" => "max:255",
    ];

    public function mount()
    {
        $this->name = CartManager::customer()?->name;
        $this->phone = CartManager::customer()?->phone;
    }

    public function submit()
    {
        $this->validate();

        $customer = CartManager::customer();
        $customer->phone = $this->phone;
        $customer->save();

        $order = CartManager::order($customer->id);
        $order->address = $this->address;
        $order->payment_method = $this->payment;
        $order->notes = $this->notes;
        $order->save();

        foreach (CartManager::items() as $item) {
            $order->products()->attach($item->product["id"], [
                "quantity" => $item->quantity,
                "amount" => $item->amount,
            ]);
        }

        $this->emit("tg:orderPlaced", __("frontend.order_placed_successfully"));

        OrderPlaced::dispatch($order);

        CartManager::clear();
    }

    public function render()
    {
        return view("livewire.order-submit", [
            "cart" => CartManager::items(),
            "subtotal" => CartManager::subtotal(),
        ]);
    }
}
