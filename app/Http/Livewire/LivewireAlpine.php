<?php

namespace App\Http\Livewire;
use Db;

use Livewire\Component;

class LivewireAlpine extends Component
{
    public $products;
    public function mount()
    {
        $this->products= DB::table('simpleproducts')->get();
        
      //  $this->products=json_encode($products);

       // dd($this->products);
    }

    public function render()
    {
        return view('livewire.livewire-alpine');
    }
}
