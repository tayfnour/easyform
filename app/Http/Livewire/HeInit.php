<?php

namespace App\Http\Livewire;

use Livewire\Component;
use DB;

class HeInit extends Component
{
    
    public $multiple_features=[];
    public $features;
    //=["name"=>"hassan" , "age" => "47" , "mobile"=>"056396111"];
    public $editKey;
    public $editVal;
    public $massege=[];
    public $fe_gr_name;
    public $col_width = 4;


    protected $listeners = [
        "addGroup" => "addGroup"
    ];


    public function mount(){

        $this->fe_gr_name = "feature_default";

        $this->features = json_decode($this->getConfig($this->fe_gr_name) ,true);

       // dd($this->features);
      //  dd($this->features);

       if($this->features == "" ||  !isset($this->features) ){
            $this->features =["first-key" => "first_value" ];
            $this->setConfig( $this->fe_gr_name , json_encode($this->features));
        }

       $res = DB::table("config")->select("mkey")->where('mkey','like','feature_%')->get() ;
       
      // dd ($res);

      foreach($res as $k => $v){

        $this->multiple_features[]= $v->mkey;

      } 
      
     // dd($this->multiple_features);
     
    }    


    public function setGroup(){
      
        $this->features = json_decode($this->getConfig($this->fe_gr_name) ,true);
        $this->massege[] =  "تم تغيير اسم مجموعة الميزات   ";
    }

    public function addGroup($name){
      
        $this->setConfig($name , json_encode(["first-key" => "first_value" ]));

        $this->massege[] =  "تم إضافة اسم مجموعة ميزات جديدة  ";
    }

    function setConfig ($k , $val){

        $res = DB::table('config')->where("mkey" , $k)->get();
 
         if($res->count() >0 ){
          
             DB::table('config')->where("mkey" , $k)->update(["mval" => $val]);

 
         }else{
 
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


    public function updFeature($k , $v  , $old){

        //dd($v == $old);

        if ($v == $old) {
             
            $this->massege[]=  " لم يتم تحديث او تغير للقيمة ";

        }
        else if (isset($this->features[$k])) {

            $this->features[$k] = trim($v);            
            $this->setConfig( $this->fe_gr_name , json_encode($this->features));
            $this->massege[]=  "تم تعديل قيمة المفتاح ";

        }else{
           
            $this->massege[]=  "تم حذف المفتاح ";

        }  
    }

    public function DelFeature( $delkey ){
 
        if (isset($this->features[$delkey])) {

            unset($this->features[$delkey]);
            $this->setConfig( $this->fe_gr_name , json_encode($this->features));
            $this->massege[]=  "تم حذف المفتاح ";

        }else{

            $this->massege[]=  "تم حذف المفتاح ";
        }

    }    

    public function addFeature(){
       
        
        
            if (array_key_exists($this->editKey, $this->features) || $this->editKey=="" )
            {
                $this->massege[]=  " المفتاح موجودأو فارغ لا تستطيع إضافة ميزة";
            }
            else
            {
                $this->features[$this->editKey] = trim($this->editVal) ;
                $this->setConfig( $this->fe_gr_name , json_encode($this->features));

                $this->massege[]= "تم إضافة الميزة";
            }

            $this->editKey="" ;
            $this->editVal="" ;

    }

    public function render()
    {
        $this->massege = count($this->massege)>3?array_slice($this->massege, -3, 3):$this->massege;
        return view('livewire.he-init');
    }
}
