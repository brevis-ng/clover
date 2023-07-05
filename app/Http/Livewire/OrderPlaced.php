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
        'name' => 'required|min:6',
        'phone' => 'required|numeric|min_digits:10',
        'address' => 'required|max:255',
        'payment' => 'required',
    ];

    public function submit()
    {
        $this->validate();

        $customer = CartManager::customer();
        if (!$customer) {
            $customer = new Customer();
            $customer->name = $this->name;
            $customer->phone = $this->phone;
            $customer->save();
        } else {
            $customer->name = $this->name;
            $customer->phone = $this->phone;
            $customer->save();
        }

        $order = CartManager::order($customer->id);
        $order->address = $this->address;
        $order->payment_method = $this->payment;
        $order->save();

        $message = __('admin.order_placed_successfully', ['id' => $order->id]);
        $this->emit('tg:orderPlaced', $message);

        CartManager::clearCustomer();
        CartManager::clear();
    }

    public function render()
    {
        return view('livewire.order-placed', [
            'cart' => CartManager::items(),
            'subtotal' => CartManager::subtotal(),
        ]);
    }
}
