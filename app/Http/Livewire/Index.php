<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ["cart-updated" => '$refresh'];
    public $products;

    public function mount()
    {
        $this->products = Cache::rememberForever("products", function () {
            return Product::orderBy("updated_at")->get();
        });
    }

    public function filter_products($id = null)
    {
        $this->products =
            $id == null
                ? Cache::get("products")
                : Product::where("category_id", $id)->get();
    }

    public function increment($product)
    {
        CartManager::add($product);

        $this->emit("cart-updated", CartManager::count());
    }

    public function decrement($item_id)
    {
        CartManager::update($item_id, -1);

        if (CartManager::count() == 0) {
            $this->emit("cart-updated", CartManager::count());
        }
    }

    public function getQuantity($id)
    {
        $item = CartManager::item($id);

        return $item === null ? 0 : $item->quantity;
    }

    public function render()
    {
        $categories = Category::has("products")->get();

        return view("livewire.index", [
            "products" => $this->products,
            "categories" => $categories,
        ]);
    }
}
