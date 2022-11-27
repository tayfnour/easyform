<?php

namespace App\Http\Livewire;
use App\MyClass\Tree;
use App\Models\catagory;
use Livewire\Component;


class Categ extends Component
{
    public $main_cat = 0;
    public $second_cat =0;
    public $three_cat = 0;

    public $main_cat_input;
    public $sec_cat_input;
    public $thr_cat_input;

    //protected $listeners = ['CatTree' => '$refresh'];

    //protected $listeners = ['render' => 'render'];

    public function add_main_cat(){

      $arr= [
             "code" =>  catagory::max("code") + 1 ,
             "name" => $this->main_cat_input,
             "parent_id" => $this->second_cat
          ];

         catagory::create($arr);
       //  $this->emit('refreshComp');
         $this->sec_cat_input= "";
    }

    public function add_second_cat(){

        $arr= [
               "code" =>  catagory::max("code") + 1 ,
               "name" => $this->sec_cat_input,
               "parent_id" => $this->second_cat
  
           ];
  
           catagory::create($arr);
         //  $this->emit('refreshComp');
           $this->sec_cat_input="";
      }
  
      public function add_three_cat(){

        $arr= [
               "code" =>  catagory::max("code") + 1 ,
               "name" => $this->thr_cat_input,
               "parent_id" => $this->three_cat
  
           ];
  
           catagory::create($arr);
        //   $this->emit('refreshComp');
           $this->thr_cat_input="";
      }


    public function getChildofmain($num){
       
        $this->second_cat=$num;
        $this->three_cat =0;
        

    }

    public function getChildofsecond($num){
       
        $this->three_cat=$num;

    }

    public function render()
    {
       
         return view('livewire.categ' , 
         [
             "cats1" =>  catagory::where("parent_id", 0)->get() ,
             "cats2" =>  catagory::where("parent_id", $this->second_cat)->get(),
             "cats3" =>  catagory::where("parent_id", $this->three_cat)->get()
         ]
        );
    }
}
