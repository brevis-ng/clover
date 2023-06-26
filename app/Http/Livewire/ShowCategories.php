<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

class ShowCategories extends Component
{
    public $categoryId;
    protected $listeners = ['categoryActivated' => 'categoryActivatedHandle'];

    public function categoryActivatedHandle($id)
    {
        $this->categoryId = $id;
    }

    public function render()
    {
        $categories = Category::has('products')->get();
        return view('livewire.show-categories', ['categories' => $categories]);
    }
}
