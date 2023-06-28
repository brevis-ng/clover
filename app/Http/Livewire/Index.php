<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cartUpdated' => 'update_state'];

    public function update_state()
    {

    }

    public function render()
    {
        return view('livewire.index');
    }
}
