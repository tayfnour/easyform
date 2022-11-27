<?php
    //Hassan Tayfour  15-8-2021
    /* 
       AutoComplete Componenet
      for using  1- include  [js . css ];
                 2- include  autoComplete Component;
    */
      namespace App\Http\Livewire;
      use Livewire\Component;
      use Illuminate\Support\Facades\DB;
      use App\MyClass\Helpery;
      
      class AutoComplete extends Component
      {
        protected $listeners = ["setStyle" => "setStyle" , "closeFloat"=>"closeFloat"];
        public $autoStyle="display:none";
        public $autoComplete ;

        public $wordSearch ;

        public $sword;
        public $swordPar;

        public $swordd;
        public $sworddPar;

        public $ids;
        public $sqlFromConfig;
       

      //set selected valye with key
       public function setEditedInputBox($id , $key , $value){         
            $this->{$id}=$key;
            $this->{$id."Par"}= $value?$value:"";
            $this->closeFloat();
       }


        // display auto complete div
        public function setStyle($style , $ids , $sqlFromConfig){
            $this->ids = $ids;     
            $this->autoStyle = $style;
            $this->sqlFromConfig =  $sqlFromConfig; 
            $this->wordSearch = $ids;          
        }

        public function closeFloat(){
            $this->autoStyle="display:none";
         }
             
          public function render()
          {            
            // $this->sqlFromConfig : to get table and cols lookup table;
            //  id of lookup  input      
            //  $this->wordSearch :  actual name of  Id
            // $this->{$this->wordSearch}  value of acual id

              if($this->sqlFromConfig)
              $this->autoComplete= Helpery::getCat( $this->ids , $this->sqlFromConfig ,"%".$this->{$this->wordSearch}."%");
            
              return view('livewire.auto-complete');
          }
      }