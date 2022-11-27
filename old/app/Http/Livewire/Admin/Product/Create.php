<?php

namespace App\Http\Livewire\Admin\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

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

    public function updated($input)
    {
        $this->validateOnly($input);
    }

    public function create()
    {
        $this->validate();

        $this->dispatchBrowserEvent('show-message', ['type' => 'success', 'message' => __('CreatedMessage', ['name' => __('Product') ])]);
        
        if($this->getPropertyValue('image') and is_object($this->image)) {
            $this->image = $this->getPropertyValue('image')->store('images/products');
        }

        Product::create([
           // 'id' => $this->id,
            'pro_name' => $this->pro_name,
            'price' => $this->price,
            'catgory' => $this->catgory,
            'sku' => $this->sku,
            'image' => $this->image,
           // 'user_id' => auth()->id(),
        ]);

        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.product.create')
            ->layout('admin::layouts.app', ['title' => __('CreateTitle', ['name' => __('Product') ])]);
    }
}
