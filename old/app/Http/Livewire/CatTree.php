<?php

namespace App\Http\Livewire;
use App\MyClass\Tree;
use App\Models\catagory;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class CatTree extends Component
{
    use WithFileUploads;
    public $forcefresh ;
    public $endChild="empty";
    public $UpdatedOk ="";   
    
    public $tempname ;
    public $crudVal;
    public $msg ="";
    public $price;
    public $sku;
    public $image;
    public $gcode;
    public $gparent;
    public $isParent;
    public $isgategory=0;

    public $isProduct;   

   // public $listeners = ['refreshComp' => '$refresh'];


   function resetAll(){
    $this->crudVal =  $this->tempname;   
   }

   public function hydrate()
   {
       $this->resetErrorBag();
       $this->resetValidation();
       $this->dispatchBrowserEvent('loadClasses', []);
   }



   function setGlobalVar($code , $parent){   
       
    
    $this->isParent =  catagory::where("parent_id",$code )->first();
    

    $this->isProduct  = $row = Product::where("catgory",$code )->first();

    $name =catagory::where("code",$code )->first();

    $this->gcode = $code;
    $this->gparent = $parent;
  

    // if  exist into database  its a product  else category 
    if($this->isProduct){            

                $this->crudVal= $row->pro_name;   
                $this->price = $row->price;
                $this->sku = $row->sku;
                $this->image = $row->image;
             
                session()->flash('message','لقد اخترت منتجا لانه موجود في جدول المنتوجات');
        }else{

            $this->tempname= $name->name; 

            $this->crudVal=""; 
            $this->price ="";
            $this->sku = "";
            $this->image =""; 

              

            session()->flash('message', 'لقد اخترت تصنيفا لانه ليس موجودا في جدول المنتوجات');
        }

    // $this->dispatchBrowserEvent('loadClasses', []);

   }

    function updatedimage(){
       // $this->dispatchBrowserEvent('loadClasses', []);
    }


function update_node($gcode){     
   
    // if not parent and  it is product
   // if(!$this->isParent ){   
       $str="";
        catagory::where("code", $gcode )->update( ["name" => $this->crudVal ] ) ;    
        
        $str="لقد تم تعديل التصنيف";
        
        if($this->isProduct){

            $this->validate([
                'crudVal'=> 'required',
                'sku' => 'required|unique:Products,sku',
                'price' => 'required' ,
              
           ]); 


            if(is_object($this->image)){
                $filename = $this->image->store('images','public');
               }else{
                $filename=$this->image; 
               }          
 
            Product:: where("catgory", $gcode)->update([
               
             "pro_name" => $this->crudVal,
             "sku" =>  $this->sku,
             "price" => $this->price,
             "image" =>  $filename,
              ]);
        
          }  

          $str.="تم تعديل المنتج".",";
         session()->flash('message' ,  $str);
     
   // }
    
    // else if($this->isParent){

    //     catagory::where("code", $gcode )->update( ["name" => $this->crudVal ] ) ;   
       
    //     session()->flash('message', 'تم تعديل  التصنيف فقط  لأنه ليس منتج');

    //  }

    //  $this->dispatchBrowserEvent('loadClasses', []);
       
  
  
       
    }



    function insert_node($parent_id , $code){   

     $new_code = catagory::max("code") + 1;

     $ِcountName = catagory::where("name" , $this->crudVal)->count();

   // dd(ِcountName);

     $str ="";

   
    if ($ِcountName == 0 ){

            catagory::create( 
                                [
                                "name" => $this->crudVal   , 
                                "parent_id" => $parent_id  ,
                                "code" => $new_code ,
                                    
                                ]) ;
                                

                                $str ="تمت اضافة  التصنيف";
              }else{
                
                $str ="اسم التصنيف موجود";
              }
    
    $ِcountName1 = Product::where("pro_name" , $this->crudVal)->count();    
    
  
    if ($ِcountName1  == 0 ){

        if($ِcountName==0){
            $insertcode =  $new_code;
        }else{

            $insertcode = $code;
        }

            $this->validate([
                'image' => 'image|max:1024', // 1MB Max
                'crudVal'=> 'required',
                'sku' => 'required|unique:Products,sku',
                'price' => 'required' ,
            ]);
        
            if( is_object($this->image)){
                $filename = $this->image->store('images','public');
               }else{
                $filename=$this->image; 
               }    

             Product::create ( 
               [
                 "pro_name" => $this->crudVal,
                 "sku" =>  $this->sku,
                 "price" => $this->price,
                 "image" =>  $filename,
                 "catgory" => $insertcode  
               ]
               ); 

               $str.=", وتم اضافة معلومات المنتج" ;
                      

          
        }else{

            $str.=",المنتج موجود ";
        }

        session()->flash('message',  $str);
        
     }

     function insert_child($code){

    // if not product
      if (!$this->isProduct)  {

        $this->validate([
            'crudVal' => 'unique:catagories,name', // 1MB Max       
        ]);
          
         catagory::create( 
            [
            "name" => $this->crudVal   , 
            "parent_id" => $code ,
            "code" => catagory::max("code") + 1              
            ]) ;            

            session()->flash('message',  "لقد تم اضافة فرع");  

         
      } else{

        session()->flash('message',  "لا تستطيع اضافة فرع لانه منتج");  
      }  
       
     
       
    }



     function del_node ($code){
           
         $res = catagory::where("parent_id", $code)->get();

         if($res->count()== 0 ){    

            
            catagory::where(["code" => $code ])->delete();
                  $this->msg = "He has not  a childrens Deleted;";

         }else{
                  $this->msg = "He has a childrens You Can not Delete it";
           
         }
        //  $this->dispatchBrowserEvent('loadClasses', []);
     }

   
    function mount(){
        // function mount($forcefresh){
        //$this->forcefresh =  $forcefresh;   
        //$this->dispatchBrowserEvent('loadClasses', []);
    }

   // function drawTree(){    }

    public function render()
    {
        $tree = new Tree();        
        return view('livewire.cat-tree' ,["Dtree" =>  $tree->createDynamicTree()])
        ->layout("layouts.app");

    }
}
