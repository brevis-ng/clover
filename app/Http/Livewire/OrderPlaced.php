<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
use Livewire\Component;

class OrderPlaced extends Component
{
    public $cart;

    public function mount()
    {
        $this->cart = CartManager::items();
    }

    public function getSubtotal()
    {
        return CartManager::subtotal();
    }

    public function render()
    {
        return view('livewire.order-placed');
    }
}
