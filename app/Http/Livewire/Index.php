<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = [
        'cart-updated' => '$refresh',
        'tg:initData' => 'telegramInitDataHandler',
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
            $this->emit('cart-updated', CartManager::count());
        }
    }

    public function decrement(Product $product)
    {
        CartManager::update($product->id, -1);

        if (CartManager::count() == 0) {
            $this->emit('cart-updated', CartManager::count());
        }
    }

    public function telegramInitDataHandler($raw_data)
    {
        parse_str($raw_data, $data);
        asort($data);
        $dataCheckString = [];
        foreach ($data as $key => $value) {
            if ($key === 'hash') {
                continue;
            }

            if (is_array($value)) {
                $dataCheckString[] = $key . '=' . json_encode($value);
            } else {
                $dataCheckString[] = $key . '=' . $value;
            }
        }

        $dataCheckString = implode("\n", $dataCheckString);
        $secretKey = hash_hmac('sha256', 'YOUR-TOKEN-CODE-123', 'WebAppData', true);
        $sig = hash_hmac('sha256', $dataCheckString, $secretKey);

        return $sig === $data['hash'];
    }

    public function getQuantity(Product $product)
    {
        $item = CartManager::item($product);

        return $item === null ? 0 : $item->quantity;
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
