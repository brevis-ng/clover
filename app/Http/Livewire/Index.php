<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = [
        'cart-updated' => '$refresh'
    ];
    public $products;

    public function mount()
    {
        $this->products = Product::orderBy('updated_at')->get();
    }

    public function filter_products($id = null)
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

        if (CartManager::count() > 0) {
            $this->emit('cart-updated', $this->getItemCount());
        }
    }

    public function decrement(Product $product)
    {
        CartManager::update($product->id, -1);

        if (CartManager::count() == 0) {
            $this->emit('cart-updated', $this->getItemCount());
        }
    }

    public function getQuantity(Product $product)
    {
        $item = CartManager::item($product);

        return $item === null ? 0 : $item->quantity;
    }

    public function getItemCount()
    {
        return CartManager::count();
    }

    public function render()
    {
        $categories = Category::has('products')->get();

        return view('livewire.index', [
            'products' => $this->products,
            'categories' => $categories,
        ]);
    }
}
