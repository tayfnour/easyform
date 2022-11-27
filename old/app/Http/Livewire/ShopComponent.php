<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\catagory;
use Livewire\WithPagination;
use Gloudemans\Shoppingcart\Facades\Cart;
//use 

class ShopComponent extends Component
{

     use WithPagination;

     public $uurl="192.168.100.37"; 

     protected $paginationTheme = 'bootstrap';
     
    public  function store ( $id , $pro_name ,  $pro_price){

       // Cart::destroy();

        Cart::add($id  , $pro_name , 1 , $pro_price)->associate("App\Models\Product");

        session()->flash("message" , "تمت الاضافة للسلة");

        return redirect()->route('product.cart');

      // dd(Cart::content()->count());
    }


    

    public function render()
    {
        return view('livewire.shop-component',
        [
            "products" =>  Product::paginate(9),
            "cats" => catagory::where("parent_id" , 0)->get(),
            "cats1" => catagory::where("parent_id" ,">", 0)->get(),
        ]
        
        )->layout("layouts.base");
    }
}
