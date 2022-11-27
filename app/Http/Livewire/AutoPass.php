<?php
namespace App\Http\Livewire;
use App\MyClass\Helpery;
use Livewire\Component;

class AutoPass extends Component
{
   protected $listeners = ['refreshVars' => 'refreshVars',"closeFloat" => "closeFloat" ]; 

 // public $post;
   public $idd;
   public $style;
   public $sqlConf;
   public $swo;
   public $autoComplete;  

    public function setEditedInputBox($id , $key , $value){    
    
        $this->emit("resultFromChild" , $id , $key , $value);      
        $this->closeFloat();
   }

     public function refreshVars($post){
        //   dd($post);
        $this->idd = $post["idd"];
        $this->style = $post["style"];
        $this->sqlConf = $post["sqlConf"];
        $this->swo = $post["sw"];
     }

    public function closeFloat(){
        $this->style="display:none";
     }

    public function render()
    {
        if($this->sqlConf)
        $this->autoComplete= Helpery::getCat( $this->idd , $this->sqlConf ,"%".$this->swo."%");
        return view('livewire.auto-pass');
    }
}
