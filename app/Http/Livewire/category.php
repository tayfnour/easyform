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
      
      class category extends Component
      {
        protected $listeners = ["setStyle" => "setStyle" ];
        public $autoStyle="display:none";
        public $autoComplete ;
        public $sword;
        public $swordPar;
        public $ids;
        public $sqlFromConfig;

       public function setEditedInputBox($id , $key , $value){  
            $this->{$id}=$key;
            $this->{$id."Par"}=$value;
            $this->closeFloat();
       }

        public function setStyle($style , $ids , $sqlFromConfig){
            $this->ids = $ids;     
            $this->autoStyle = $style;
            $this->sqlFromConfig =  $sqlFromConfig;           
        }

        public function closeFloat(){
            $this->autoStyle="display:none";
         }
             
          public function render()
          {
              
            if($this->ids)
              $this->autoComplete=Helpery::getCat( $this->ids , $this->sqlFromConfig ,"%".$this->sword."%");

              return view('livewire.category');
          }
      }