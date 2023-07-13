<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $listeners = ["cart-updated" => '$refresh'];
    public $categories;
    public $category_id;

    public function mount()
    {
        $this->category_id = 0;

        $this->categories = Cache::rememberForever("categories", function () {
            return Category::visibility()
                ->latest("updated_at")
                ->get();
        });
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function increment($product)
    {
        CartManager::add(json_decode($product, true));

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
        if ($this->category_id != 0) {
            $products = Product::visibility()
                ->where("category_id", $this->category_id)
                ->latest("updated_at")
                ->paginate(10);
        } else {
            $products = Product::visibility()
                ->latest("updated_at")
                ->paginate(10);
        }

        return view("livewire.index", [
            "products" => $products,
        ]);
    }
}
