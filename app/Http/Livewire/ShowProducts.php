<?php

namespace App\Http\Livewire;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ShowProducts extends Component
{
    public $products;
    protected $listeners = ['categoryClicked' => 'showProductsByCategory'];

    public function showProductsByCategory($id = null)
    {
        $this->emit('categoryActivated', $id);

        if ($id !== null) {
            $category = Category::find($id);

            $this->products = $category->products()->get();
        } else {
            $this->products = Product::orderBy('updated_at')->get();
        }
    }

    public function render()
    {
        $this->products = $this->products ?? Product::orderBy('updated_at')->get();
        return view('livewire.show-products', ['products' => $this->products]);
    }
}
