<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Cart;

class DetailComponent extends Component
{
    public $slug;

    public  function mount($slug){
       
        $this->slug = $slug ;
    }

    public  function store( $id , $pro_name ,  $pro_price){

        // Cart::destroy();
 
         Cart::add($id  , $pro_name , 1 , $pro_price)->associate("App\Models\Product");
 
         session()->flash("message" , "تمت الاضافة للسلة");
 
         return redirect()->route('product.cart');
 
       // dd(Cart::content()->count());
     }

    public function render()
    {
            return view('livewire.detail-component',
            
            [
                'product'  => Product::where( 'sku' ,  $this->slug )->first()

            ]          
    
         )->layout('layouts.base');
    }
}
