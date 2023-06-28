<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
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

    public function increment(Product $product)
    {
        CartManager::add($product);
        $this->emit('showMainMenu', __('view_carts'));
    }

    public function decrement(Product $product)
    {
        CartManager::update($product->id, -1);

        if (CartManager::count() == 0) {
            $this->emit('hideMainMenu');
        }
    }

    public function getQuantity(Product $product)
    {
        $item = CartManager::item($product);

        return $item === null ? 0 : $item->quantity;
    }

    public function render()
    {
        return view('livewire.show-products', ['products' => $this->products]);
    }
}
