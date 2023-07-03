<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
use Livewire\Component;

class ShowCarts extends Component
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
        return view('livewire.show-carts');
    }
}