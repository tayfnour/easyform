<?php

// layout('layouts.HtmlEditorLayout')

namespace App\Http\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Blade;

class HtmlEditorComponent extends Component
{
    // core Lib -----//

    public $fileName ;
    public $pageMode ;
    public $history;
    public $his_Pointer;
    public $css;
    public $js;

    protected $listeners = [
        'savePage' => 'savePage' ,
        'CreateComponent'=> 'CreateComponent' ,
        'viewLive'=>'viewLive' , 
        'viewHtml' =>'viewHtml',
        'saveChangeHtml' => 'saveChangeHtml',
        'saveComponent' =>'saveComponent',
        'setCss'  => 'setCss',
        'setJs'  => 'setJs',
        
      //  'saveChangeHtmlAndView'=>'saveChangeHtmlAndView'
    ];   

    public $comp_dir ;
    public $compFile;
    public $bladeFile;
    public $blade_dir;
    public $message=[];
  //  public $comp_name;

    // study to display component him self
    //  custom  component public variable;
    //end  component public variable;

    // public function  update() {        
    //     $this->dispatchBrowserEvent('saveState', []);
    // } 

    public function setCss($css){

        $this->setConfig($this->fileName."_css" , $css);
        $this->css = $css ;
    }

    public  function setJs($js){ 
        $this->setConfig($this->fileName."_js" , $js) ;
        $this->js = $js;    
    }

    public  function dd_his(){
        dd($this->history);
    }

     public function undo (){    
          $this->saveChangeHtml("" , "undo");
         
     }

     public function redo (){         
         $this->saveChangeHtml( ""  , "redo");        
    }

    public function historyAdd($html){
         
        //$cont = count($this->history);
        $this->history[]= $html ;       
    }

    public function  hydrate() {      
        $this->dispatchBrowserEvent('showAlert', []);
    }  

    public function saveComponent($php){

        File::put($this->comp_dir , $php);
    }

    function mount($comp_name){          
        //dd($comp_name);
        //$comp_name// this from route url
        // nameOfComponent  between (-)

        $this->pageMode=$this->getConfig("mode");
        $this->fileName = $comp_name  ; // this from route url  // $this->getConfig("fName");       
        $this->blade_dir = base_path()."\\resources\\views\\livewire\\{$this->fileName}.blade.php";
        $comp_fileName =  str_replace("-","", $this->fileName);
        $this->comp_dir = app_path()."\\Http\\Livewire\\{$comp_fileName}.php"; 

        $res=$this->getConfig($this->fileName ."_his");
      
        if($res){           
            $this->history = json_decode($res);
            $this->his_Pointer=count($this->history)-1; 
        }else{
            $this->history = [];
            $this->his_Pointer=count($this->history)-1; 
        }

      
        
    }

    // public function resetc(){
    //    // dd("reset");
    //     $this->blade_dir = base_path()."\\resources\\views\\livewire\\{$this->fileName}.blade.php";
    //     $comp_fileName =  str_replace("-","", $this->fileName);
    //     $this->comp_dir = app_path()."\\Http\\Livewire\\{$comp_fileName}.php"; 

    //     $this->dispatchBrowserEvent('refresh', []);
    // }

    

    function viewLive(){
        $this->pageMode = 2;
        $this->setConfig("mode" , 2);      
    }

    function viewHtml(){
         $this->pageMode = 1;
         $this->setConfig("mode" , 1);
    }

    function setConfig ($k , $val){

       $res = DB::table('config')->where("mkey" , $k)->get();

        if($res->count() == 1 ){
         
            DB::table('config')->where("mkey" , $k)->update(["mval" => $val]);

        }else if ($res->count() == 0){

            DB::table('config')->insert([  "mkey" =>  $k ,"mval" => $val]);

        }
  

    }

    function getConfig ($k){       

        $res = DB::table('config')->where("mkey" ,$k)->first();

        if($res){
             return $res->mval;
        }else{
             return false;
        }
       
    }    

    function CreateComponent($fName){
       // create component blade ------;  

       $fName = strtolower ($fName);
       $comp_fileName="";
       $this->blade_dir = base_path()."\\resources\\views\\livewire\\{$fName}.blade.php";
       $nameArr = explode("-",$fName);
       if(count($nameArr)>1){
       $comp_fileName=ucfirst($nameArr[0]).ucfirst($nameArr[1]);
    
       }else{
        $comp_fileName=ucfirst($nameArr[0])  ;
       }

      // $comp_fileName =  str_replace("-","", $fName);
       $this->comp_dir = app_path()."\\Http\\Livewire\\{$comp_fileName}.php"; 
        
        // DB::table('designer')->insert(
            
        //     [   
        //         'name' => $fName ,
        //         'html' => "" ,
        //         'js' =>   ""  ,
        //         'css' =>  ""  ,
        //         'notes' => ""
        //     ]
        // );
       
      $comp ="<?php

      namespace App\Http\Livewire;
      
      use Livewire\Component;
      
      class {$comp_fileName} extends Component
      {
             
          public function render()
          {
              return view('livewire.{$fName}');
          }
      }";


      $blade = "<div  class='el' >New_Component_{$fName}</div>";

     // dd($fName ."___".$comp_fileName);

        File::put($this->comp_dir,$comp);

        File::put($this->blade_dir,$blade);

        $this->fileName = $fName;

        session()->flash( 'message' , 'تم انشاء مكون  بنجاح' );
    }

    

    function savePage ($html , $js , $css , $fName){    
        
        
     if($this->pageMode == 1) {
      // dd($html);
      //save as blade
       $html =  str_replace( "<!--" ,"", $html );
       $html =  str_replace( "-->" ,"", $html );
      
       

        //    DB::table('designer')->where("name" , $fName)->update(
        //     [
        //         'html' =>  $this->bladeFile   ,
        //         'js' =>   $js  ,
        //         'css' =>  $css  
        //     ]
        //  );

      session()->flash( 'message' , 'تم حفظ الملف بنجاح' );
     }  
    }

     // save blade from browser to blade file when add  html tags or attributes
    public function saveChangeHtml($html , $p = null){


        //dd($html);
        $re = '/wire:id="[\w]+"/m';
        $html =  str_replace( "<!--" ,"", $html );
        $html =  str_replace( "-->" ,"", $html );

        $html =  str_replace( "&lt;!--" ,"", $html );
        $html =  str_replace( "--&gt;" ,"", $html );

      
        $html =  str_replace( "HE_border_click" ,"", $html );
        $html =  str_replace( "HE_borde" ,"", $html );

        $this->history=  isset($this->history)?$this->history:[];
         // $html =  str_replace( $re ,"", $html );
        if($p==null){
           //   dd("add");
           $his_count= count($this->history);
           if($his_count<20){
            $this->his_Pointer++;            
            $this->history[$this->his_Pointer]= $html;   
           }else{
            array_unshift($this->history);
            $this->history[$this->his_Pointer]= $html;
           }        
       }

       if($p=="undo" && $this->his_Pointer >0){
        $this->his_Pointer--;
        $html = $this->history[$this->his_Pointer];

       }

       if($p=="redo" && $this->his_Pointer < count($this->history)-1){
        $this->his_Pointer++;
        $html = $this->history[$this->his_Pointer];
       }
       
    

       if($html !== ""){
        
        $this->bladeFile = $html;
        File::put($this->blade_dir , $html);
        // hid = histoty
        $this->setConfig($this->fileName."_his" , json_encode($this->history));

       }
        
       // dd($html);
    }

    public function  getAllFiles(){   

        $files=json_decode($this->getConfig("feature_components_sys"));
        return explode(",",$files->comp_names);
    }

    public function render()
    {
        
       // dd($this->fileName);

       // $this->setConfig("fName" ,  $this->fileName);     

        $this->compFile = File::get($this->comp_dir);       
        $this->bladeFile = File::get($this->blade_dir);
        $this->css =  $this->getConfig($this->fileName."_css");
        $this->js  =  $this->getConfig($this->fileName."_js");

       //dd($this->css); 
       //dd($this->bladeFile);
       //dd($this->fileName);
        

      //  if( DB::table('designer')->where("name" , $this->fileName)->first()){

            return view('livewire.html-editor-component' ,
            [                  
              //  'page'=> DB::table('designer')->where("name" , $this->fileName)->first(),
                'fileNames'=>$this->getAllFiles()
            ]);
            
        
       
    }
}
