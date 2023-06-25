<?php

namespace App\Http\Livewire;

use App\Enums\ProductStatus;
use App\Models\Product;
use Livewire\Component;

class ShowProducts extends Component
{
    public function render()
    {
        $products = Product::where('status', ProductStatus::INSTOCK)->orderBy('updated_at')->paginate();
        return view('livewire.show-products', ['products' => $products]);
    }
}
