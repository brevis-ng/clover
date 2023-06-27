<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class Carts extends Component
{
    public $count;

    public function mount()
    {
        $this->count = 0;
    }

    public function add(Product $product)
    {

    }

    public function remove(Product $product)
    {

    }

    public function render()
    {
        return view('livewire.carts');
    }
}
