<?php

      namespace App\Http\Livewire;
      
      use Livewire\Component;
      
      class testauto extends Component
      {   
       
        protected $listeners = ["setStyle" => "setStyle" , "resultFromChild" => "resultFromChild"]; 

       public  $pass_idd = "";
       public  $pass_style = "";
       public  $pass_sqlConf = "";
       public  $counter=0;
       public  $post;    

       //input feild
       public  $sword ="";
       public  $swordPar;

       public function  mount(){
        $this->post = ["idd" => $this->pass_idd ,"style" => $this->pass_style , "sqlConf" => $this->pass_sqlConf ,"sw" => $this->sword];
       }

    
       public function setStyle( $style , $idd  , $sqlConf , $sw ){

        $this->counter+=1;
        $this->pass_idd = $idd;     
        $this->pass_style = $style;
        $this->pass_sqlConf = $sqlConf;
        $this->sword = $sw;
        $this->post = ["idd" => $this->pass_idd ,"style" => $this->pass_style , "sqlConf" => $this->pass_sqlConf ,"sw" => $this->sword];
        $this->emit("refreshVars" , $this->post ); 
             
    }    

    public function resultFromChild ($id , $key , $value){    
       $this->{$id}=$key;
       $this->{$id."Par"} =$value?$value:"";
    }


          public function render()          {               
              return view('livewire.test-auto');
          }
      }