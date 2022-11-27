<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProductComponent extends Component
{
   public $pro_id;
   public $pro_name;
   public $sku;
   public $cat;

    public function render()
    {
        return view('livewire.product');
    }
}
