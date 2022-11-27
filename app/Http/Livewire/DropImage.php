<?php

      namespace App\Http\Livewire;
      use Illuminate\Support\Str;
      use Livewire\Component;
      use Livewire\WithFileUploads;
      use DB;
      
      class DropImage extends Component
      {

        use WithFileUploads;

          
         // MHT : you Must add tow  function when use auto complete "setStyle" & "resultFromChild"
          protected $listeners =["removePhotoFromArr"=> "removePhotoFromArr" , "setStyle" => "setStyle" ,  "resultFromChild" => "resultFromChild"];
          public $fields;
          public $photos=[] ;
          public $folderName = "events";

          public $edit_images;

          public $event_id;
          public $event_idPar;

          public function mount(){
            $this->fields = 1;
          }

          public function addField(){            
            $this->fields++;
           // $this->photos[$this->fields]=null;       
           // dd($this->fields)  ;         
          } 

          public function removePhotoFromArr($i) {
            array_splice($this->photos, $i , 1); 
            $this->fields--;
          }  
          

          public function pdd(){
            dd($this->photos);    
          } 

          public function delImage($id){
            DB::table("up_images")->where("id",$id)->delete();
            $this->getImages($this->event_id);
          }  

          public function storeImages(){
            
           $str= Str::random(15);

           if(count($this->photos)>0) {

            $ev_id = intval($this->event_id);
            // dd($ev_id );
             foreach ($this->photos as $photo) {
               
                $filename = $photo->store( $this->folderName , "global_images");

                DB::table("up_images")->insert([              
                "event_id" => $ev_id , 
                "path" => $this->folderName,
                "file_name"=> $filename,
                "timestamps" =>  date("Y-m-d H:i:s")
               ]);    
               $this->getImages($this->event_id);        
                $this->photos = [];
        

                # code...
            }
           }     

          }

          public function getImages($ind){
            $str ="";
            $res = DB::table("up_images")->where("event_id" , $ind)->get();
           
            foreach($res as $ee ){        

               $str .="<img src=\"http://localhost/global_images/".$ee->file_name."\" width=\"200\" ><input class=\"btn btn-danger\" type=\"button\" wire:click=\"delImage($ee->id)\" value=\"Del\"    >";
            }

            $this->edit_images=$str;
          }


          public function setStyle( $style , $idd  , $sqlConf , $sw ){
            $this->pass_idd = $idd;     
            $this->pass_style = $style;
            $this->pass_sqlConf = $sqlConf;
            $this->sword = $sw;
            $this->post = ["idd" => $this->pass_idd ,"style" => $this->pass_style , "sqlConf" => $this->pass_sqlConf ,"sw" => $this->sword];
            $this->emit("refreshVars" , $this->post );

            $this->getImages($sw);      
                 
        }    
    
        public function resultFromChild ($id , $key , $value){    
           $this->{$id}=$key;
           $this->{$id."Par"} =$value?$value:"";
           $this->getImages($key);
        }
    
             
          public function render()
          {

            //logger($this->photos);
          //  dd($this->photos);
             
              return view('livewire.drop-image');
          }


      }