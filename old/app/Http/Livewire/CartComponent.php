<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Cart;

class CartComponent extends Component
{

    public $uurl="192.168.100.37"; 
    public function increaseCart($rowId)
    {
       $product = Cart::get($rowId);       
       $qty =  $product->qty +1 ;
       Cart::update($rowId , $qty);
    }  

    public function decreaseCart($rowId)
    {

       $product = Cart::get($rowId);

       if ($product->qty>0){
           $qty =  $product->qty -1 ;
           Cart::update($rowId , $qty);
       }
       
       
    } 

    public function deleteItem ($rowId){

          Cart::remove($rowId);
          session()->flash("message" , "نم حذف المنتج من السلة");
    }

    public function render()
    {
        return view('livewire.cart-component')->layout("layouts.base");
    }
}
