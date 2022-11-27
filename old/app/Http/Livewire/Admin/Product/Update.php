<?php

namespace App\Http\Livewire\Admin\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use WithFileUploads;

    public $product;

    //public $id;
    public $pro_name;
    public $price;
    public $catgory;
    public $sku;
    public $image;
    
    protected $rules = [
        'sku' => 'required',
        'pro_name' => 'required|min:5',        
    ];

    public function mount(Product $product){
        $this->product = $product;
        $this->id = $this->product->id;
        $this->pro_name = $this->product->pro_name;
        $this->price = $this->product->price;
        $this->catgory = $this->product->catgory;
        $this->sku = $this->product->sku;
        $this->image = $this->product->image;        
    }

    public function updated($input)
    {
        $this->validateOnly($input);
    }

    public function update()
    {
        $this->validate();

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => __('UpdatedMessage', ['name' => __('Product') ]) ]);
        
        if($this->getPropertyValue('image') and is_object($this->image)) {
            $this->image = $this->getPropertyValue('image')->store('images/products');
        }

        $this->product->update([
         //   'id' => $this->id,
            'pro_name' => $this->pro_name,
            'price' => $this->price,
            'catgory' => $this->catgory,
            'sku' => $this->sku,
            'image' => $this->image,
           // 'user_id' => auth()->id(),
        ]);
    }

    public function render()
    {
        return view('livewire.admin.product.update', [
            'product' => $this->product
        ])->layout('admin::layouts.app', ['title' => __('UpdateTitle', ['name' => __('Product') ])]);
    }
}
