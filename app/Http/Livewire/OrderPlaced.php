<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
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

    }

    public function render()
    {
        return view('livewire.order-placed', [
            'cart' => CartManager::items(),
            'subtotal' => CartManager::subtotal(),
        ]);
    }
}
