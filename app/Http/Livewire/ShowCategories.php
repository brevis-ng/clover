<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

class ShowCategories extends Component
{
    protected $listeners = ['categoryActivated' => 'categoryActivatedHandle'];

    public function render()
    {
        $categories = Category::has('products')->get();
        return view('livewire.show-categories', ['categories' => $categories]);
    }
}
