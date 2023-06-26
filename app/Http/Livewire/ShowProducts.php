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

    public function mount()
    {
        $this->products = Product::orderBy('updated_at')->get();
    }

    public function showProductsByCategory($id = null)
    {
        if ($id !== null) {
            $category = Category::find($id);

            $this->products = $category->products()->get();
        } else {
            $this->products = Product::orderBy('updated_at')->get();
        }
    }

    public function render()
    {
        return view('livewire.show-products', ['products' => $this->products]);
    }
}
