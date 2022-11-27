<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Http\Livewire\HomeComponent;

class HomeComponent extends Component
{
    public function render()
    {
        return view('livewire.home-component' )->layout('layouts.base');
    }
}
