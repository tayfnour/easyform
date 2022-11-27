<?php
//12354  6789  
      namespace App\Http\Livewire;      
      use Livewire\Component;
      use Illuminate\Support\Facades\DB;
      use App\MyClass\Helpery;

      class newProduct extends Component
      {
         

        protected $listeners = ["setStyle" => "setStyle" ];
       
          public $product_id ;  
          public $productName ="any";  
          public $price =35;  

          public $catagory ;  
          public $catagoryPar  ;

          public $sku ;    
          

          public $autoStyle = "display:none";
        

          public $ids;
          public $sqlFromConfig;
          public $autoComplete;   
          public $wordSearch ;


          public $part=1;

           //set selected valye with key
          public function setEditedInputBox($id , $key , $value){ 
       //   dd($value);
            $this->{$id}=$key;
            $this->{$id."Par"}= $value?$value:"";
            $this->closeFloat();
          }


          // display auto complete div
          public function setStyle($style , $ids , $sqlFromConfig){
        //       dd($ids);
              $this->ids = $ids;     
              $this->autoStyle = $style;
              $this->sqlFromConfig =  $sqlFromConfig; 
              $this->wordSearch = $ids;          
          }

          public function closeFloat(){
              $this->autoStyle="display:none";
          }


          public function declvl(){
            
            if ($this->part-1>=1){
             $this->part--;
            }

           }

          public function increaselvl(){            
               if ($this->part+1<=3){
                $this->part++;
               }

           }

        //    public function setStyle($style){
        //     dd($style);
        //     $this->autoStyle = $style;
        //     $this->autoComplete="";
        // }

        



          public function newProductf(){

            DB::table('products')->insert(
                
                [   
                    'pro_name' => $this->productName ,
                    'price' =>  $this->price
                    
                ]
            );
    
         }

          public function render()
          {
            if($this->sqlFromConfig)
            $this->autoComplete= Helpery::getCat( $this->ids , $this->sqlFromConfig ,"%".$this->{$this->wordSearch}."%");

            return view('livewire.new-product'
            //, [ 'autoComplete' => Helpery::getCat("option" , "getCatagories" , "ل")]
            );
          }
      }