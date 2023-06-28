<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
use Livewire\Component;

class ShowCarts extends Component
{
    public $carts;

    public function mount()
    {
        $this->carts = CartManager::items();
    }

    public function render()
    {
        return view('livewire.show-carts', ['carts' => $this->carts]);
    }
}
