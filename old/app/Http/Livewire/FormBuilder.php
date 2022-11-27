<?php   
      namespace App\Http\Livewire;    
      use Illuminate\Support\Str;
      use Livewire\WithFileUploads;  
      use Livewire\Component;
      use Carbon\Carbon;
      use DB;
      use Schema;
      class FormBuilder extends Component
      {

         use WithFileUploads; 

         // entries ..
         public $table = "products";
         public $title = "Products Manager";
         public $button_new = "New";
         public $button_update = "update";        
         public $formName = "Default";
         public $Default_forms="coloptions/default";
        // public $formName = "todoForm";
       //  public $rowid = 1;
       
        public  $datas;
        public  $dataOption;       
        public  $primaryKey;
        public  $columns;
        public  $formType;

        public  $treeleaf;
        public  $bgleafInput=[];

        public  $autoComplete=[];
        public  $style="Style";

        public $photos_Str;

        public $photos=[];

        // description who  insert with upload photo
        public $photos_desc = [];
        public $photos_desc_edit = [];


        //autocomplet description
        public $ph_Descp; 

        public $colOptions ;
      
        public  $rows_insert =[];
        protected $rules=[];

        public $messeges=["start.."];

        public $ci;
      
        public $path ;
        public $ref;
        public $submit_visible;

       protected $listeners = ['resetMount' => 'resetMount' , 'sendLeafValue'=>'getLeafValue' , 'saveForm' => 'insertRow']; 


       function retRefValue(){
         return $this->rows_insert["ref"];
       }


       

      public function setfocustree($colName , $lookup){

         $this->treeleaf =  $colName;
         $this->bgleafInput=[];
         $this->bgleafInput[$colName] = true;    
         $this->emit("setTableofTree" , $lookup ,  $this->rows_insert[$colName] );

       }

       public function getLeafValue($val){
         $this->rows_insert[$this->treeleaf]=$val;
        // unset($this->bgleafInput[$this->treeleaf]);        
       }

     

      //  public function setblurtree($colName){
      //  }

        public function setFloat($coloptions_id , $colName){
            $res = DB::table("coloptions")->where("colOptions_id" , $coloptions_id )->first();
            $this->autoComplete[$colName] =$this->getCat($res->lookup , $this->rows_insert[$colName] , $colName);
        }

        public function setEditedInputBox($colName , $code , $dataInput){
         // dd($dataInput , $colName) ;
            $this->rows_insert[$colName] = $code;
            $this->rows_insert[$colName."_par"] =$dataInput;

          // dd($colName , $this->primaryKey);
            // if feilde autoincreament and  autocomplet
           if ($colName == $this->primaryKey) {
            $result = DB::table($this->table)->where($this->primaryKey,$code)->first();   

            // dd($result , $this->rows_insert);
             
               foreach($result as $k => $v){                   
                  if(isset($this->rows_insert[$k]))
                    $this->rows_insert[$k] = $v;
               }
           }

            $this->resetAuto($colName);
        }

        public function pddd(){
         dd($this);    
       }

       public function setInputValue ($desc , $ev_id , $tb_name , $colName){

         $this->rows_insert[$colName] =  $ev_id;
         $this->rows_insert[$colName."_par"] =$desc;

         $this->getPhotos ($tb_name , $colName);       

       // dd ($desc , $ev_id , $tb_name , $colName);
       }

       public function getDescrp($tb_name , $colName){
        // dd($tb_name , $colName);
           
            $str="";
            

         if($this->rows_insert[$colName]){        
            $result = DB::table($tb_name)                                         
            ->where( "event_id","LIKE",  $this->rows_insert[$colName] )
            ->orWhere( "description","LIKE",  "%". $this->rows_insert[$colName]  ."%" )
            ->get();

            $this->resetPhoto($colName);
         }else{

            $this->photos_Str = "";
            $this->rows_insert[$colName."_par"]="-";

         }

         if(isset($result) && count($result)>0){
               foreach($result as $desc){
               $str.="<li class= 'autoli' wire:click=\"setInputValue('{$desc->description}','{$desc->event_id}','{$tb_name}','{$colName}')\">{$desc->description}-({$desc->event_id})</li>";

               }
         } 
          $this->ph_Descp=$str;
       }

       public function EditDesc($id , $tbName , $key){

         DB::table($tbName)->where("id",$id)->update(["description"=> $this->photos_desc_edit[$key]]);
       }

       public function delImage($id , $colName , $tbName){
         DB::table("up_images")->where("id",$id)->delete();
         $this->getPhotos($tbName ,$colName);
       } 

        public function getPhotos($tb_name , $colName){
           //  dd( $colName);

         $str="";

         if($this->rows_insert[$colName]){         

         $ListOfPhotos = DB::table($tb_name)                                         
                        ->where( "event_id","LIKE",  $this->rows_insert[$colName] )
                      //  ->orWhere( "description","LIKE",  "%". $this->rows_insert[$colName]  ."%" )
                        ->get();
          
         if ($ListOfPhotos->count()>0){ 
      
            foreach($ListOfPhotos as $key =>  $item){  

              // dd(config('livewire.storge_img'));

                 $url = config('livewire.storge_img').$item->file_name;
                 $this->photos_desc_edit[$key]=$item->description;
                // dd($url);             
                  $str.="<div class='card' style='min-width:230px;max-width: 250px;flex:1;margin:0px 5px'>
                  <div style='height: 190px;'>
                  <img Style='max-height:200px' class='card-img-top' src='{$url}' alt='Card image cap'>
                  </div>
                  <div class='card-body'>
                  <h5 class='card-title text-center bg-light'>وصف الصورة</h5><hr>
                  <textarea wire:model.defer=\"photos_desc_edit.{$key}\" class='form-control mb-1 editDesc'></textarea>
                  <input wire:click=\"delImage( {$item->id} , '{$colName}' , '{$tb_name}')\" class='btn btn-primary' value='delete' style='max-width:45%;'>
                  <input wire:click=\"EditDesc({$item->id} , '{$tb_name}' , {$key})\" class='btn btn-warning' value='edit'  style='max-width:45%;'>
                  </div>
                  </div>";   
            }  

            $this->photos_Str = $str;
          
         }else{

            $this->photos_Str = "";
         }

         }
        }

        public function getCat($sql , $serchWord , $colName){
         $sqlArr= explode( "|" , $sql);
        // dd($sqlArr);
         $str ="";
         //$id = $id."Par";
         
         $ListOfTree = DB::table($sqlArr[0])
                                  ->select([$sqlArr[1],$sqlArr[2]])                         
                                  ->where( $sqlArr[3],"LIKE",  "%". $serchWord ."%" )
                                  ->orWhere( $sqlArr[1],"LIKE",  "%". $serchWord ."%" )
                                  ->get();
      
         if ($ListOfTree->count()>0){
            foreach($ListOfTree as  $item){       
               //$str.="<li wire:click='setEditedInputBox(\"{$id}\", {$item->code} , \"{$item->name}\")' class='autoComplete' id='{$item->code}' >{$item->name} ({$item->code})</li>";
               $code = intval($item->{$sqlArr[1]});           
               $name = $item->{$sqlArr[3]};


              // dd($code , $name);
               $str.="<li class= 'autoli' wire:click='setEditedInputBox(\"{$colName}\", {$code} , \"{$name}\")' >{$name} ({$code})</li>";
            }        
            }else{
               $str = '<li>No Elements</li>';
            } 
            
            return $str;
         }

         //   public function photoChanged($colName){

         //    $this->photoIndex++;
      
         //     // dd( $this->rows_insert[$colName]);
         
         //   }

         //delete to upload

         public function deleteImage($index){

            array_splice($this->photos, $index , 1); 
         }

         public function storeImages($colName , $lookup){

           // $this->valid
           
            $valArr["rows_insert.".$colName] = "required";

            $this->validate($valArr);
            
           $str= Str::random(15);

           if(count($this->photos)>0) {


            $ev_id = intval($this->rows_insert[$colName]);

             if(intval( $ev_id)){
                  foreach ($this->photos as $key =>  $photo) {

                     
                     $valArr1["photos_desc.".$key] = "required";
                     $this->validate($valArr1);
                     
                     $filename = $photo->store( $this->table , "global_images");

                     DB::table($lookup)->insert([              
                     "event_id" => $ev_id , 
                     "path" => $this->table ,
                     "file_name"=> $filename,
                     "description" => $this->photos_desc[$key],
                     "timestamps" =>  date("Y-m-d H:i:s")
                     ]); 

                           
                     $this->getPhotos($lookup , $colName);
                     $this->photos=[];

                     # code...
                  }
               }     
             }
          }

         public function resetMount ($forms){
           // dd($forms);
           $this->mount($forms);
         } 

            
        public function mount($forms=null , $ref = null  , $submit_visible = null){
       
        if(is_null($forms) || trim($forms) === '') $forms = $this->Default_forms;
        $this->$ref=$ref;
        $this->submit_visible = $submit_visible;

         $spArr = \explode("/", $forms );
       
        // $colOptions= DB::table("coloptions")->where("formName" , $this->formName)->where("tableName" ,$this->table)->get();
            $this->table =  $spArr [0];
            $this->formName =  $spArr [1];
            $this->button_new = "New ".$this->table;
            $this->button_update = "Update ".$this->table;

            file_put_contents("rows.json" , json_encode([$this->table , $this->formName] ) , FILE_APPEND);

            $colOptions= DB::table("coloptions")->where("formName" , $this->formName)->where("tableName" ,$this->table)->get();

           
            $ci=0; // mht : watch request

            $result = DB::select(DB::raw("SHOW KEYS FROM {$this->table} WHERE Key_name = 'PRIMARY'"));

            $this->primaryKey = $result[0]->Column_name;
           
            $this->columns = Schema::getColumnListing($this->table); 
            
            //$colOptions= DB::table("coloptions")->where("formName" , $this->formName)->where("tableName" ,$this->table)->get();
           

            $this->formType = $colOptions[0]->formType;

           // dd($this->formType);
            // mht : get row for update
            $up_data = DB::table($this->table)->limit(1)->first();

          //  dd($up_data);
        //   dd($this->photoIndex);

            $this->rows_insert = null;
        
            foreach ($this->columns as $col){
               // mht  if formtype =0 then for insert else for update  
               if($this->formType==0)
               {  
                  if($col=="ref"){
                     $this->rows_insert[$col]= $this->ref;
                   //  session()->put("ref" , $this->rows_insert[$col]);
                  }else{
                      $this->rows_insert[$col]= null;
                     
                  }
                  
               }
               else
               {
                  $this->rows_insert[$col]= $up_data->{$col};
               }
               
            }


            file_put_contents("rows.json" ,"\n". json_encode($this->rows_insert) , FILE_APPEND);

            $this->datas = DB::table("coloptions")->where("formName" , $this->formName)->where("tableName" ,$this->table)->get();
        
         } 

         public function hydrate()
         {

           // $time_start = microtime(true); 
            
             $this->datas =  json_decode(json_encode($this->datas), FALSE);
             $this->resetErrorBag();
             $this->resetValidation();

             //  $time_end = microtime(true);

           //  dd( ($time_end - $time_start)/60);

         }       


         public function resetAuto($colName){

               $this->autoComplete[$colName]="";
           }

           public function resetPhoto($colName){

            $this->photos_Str="";
           }

           public function resetDescrption($colName){
              
            $this->ph_Descp="";
           }



         
         public function insertRow(){

           // dd($this->rows_insert);
         
            $valArr=[];          

            $colOptions = DB::table("coloptions")
            ->where("formName" , $this->formName)
            ->where("tableName" ,$this->table)->get();

            foreach($colOptions as $col){

               if($col->validation)            
               $valArr["rows_insert.".$col->colName] = $col->validation;

              
               if($col->inputType == "image"){
                  //mht : check if  model has image object
                 if(is_object($this->rows_insert[$col->colName])){

                   $filename = $this->rows_insert[$col->colName]->store( $this->table , "global_images");
                   $this->rows_insert[$col->colName] = $filename;

               }else{
                        $this->rows_insert[$col->colName]="";
                }                


               } 

               if($col->colName=="timestamps" || $col->colName=="updated_at" || $col->colName=="created_at")
               $this->rows_insert[$col->colName] = date('Y-m-d H:i:s');

               if($col->colName==$this->primaryKey) $this->rows_insert[$col->colName]=null;

               

           }

           $update_Arr =  $this->rows_insert;

           // remove _par
            foreach($update_Arr as $k => $v){

               if(strpos($k, '_par')!== false){
                  unset($update_Arr[$k]);
               }
            }



           //dd(strpos($col->colName,'_par'));
         // dd($this->rows_insert);

           if(count($valArr) >0)  $this->validate($valArr);  
           
           $insert_Arr =  $this->rows_insert;

           // remove _par
            foreach($insert_Arr as $k => $v){

               if(strpos($k, '_par')!== false){
                  unset($insert_Arr[$k]);
               }
            } 
            
                   
            $idInserted = DB::table($this->table)->insertGetId($insert_Arr);

            DB::table('history')->insert(
               ["id"=>null ,
                 "user_id" => 1,
                 "changeId" =>  $idInserted,
                 "tableName" =>  $this->table ,
                 "operation" => json_encode($insert_Arr),
                 "type" => "insert",
                 "created_at" => date('Y-m-d H:i:s')
               ]
            );

            $this->messeges[]= "inserted..".json_encode($insert_Arr)."<hr>";
             
         }

      public function updateRow(){

       //  dd($this->rows_insert);

         $valArr=[];            
          
            $colOptions = DB::table("coloptions")
            ->where("formName" , $this->formName)
            ->where("tableName" ,$this->table)->get();

            //dd($colOptions);

            foreach($colOptions as $col){

               if($col->colName=="timestamps" || $col->colName=="updated_at")
               $this->rows_insert[$col->colName] = date('Y-m-d H:i:s');

            if($col->validation)            
              $valArr["rows_insert.".$col->colName] = $col->validation;

            if($col->inputType == "image"){
               //check if  model has image object
              if(is_object($this->rows_insert[$col->colName])){
                     $filename = $this->rows_insert[$col->colName]->store( $this->table , "global_images");
                     $this->rows_insert[$col->colName] = $filename;
              }else{
                     $this->rows_insert[$col->colName]="";
              }                

              }

            
            } 

         //dd($valArr);
            if(count($valArr) >0)  $this->validate($valArr);

            $update_Arr =  $this->rows_insert;

           // remove _par
            foreach($update_Arr as $k => $v){

               if(strpos($k, '_par')!== false){
                  unset($update_Arr[$k]);
               }
            }      
        
            DB::table($this->table)->where( $this->primaryKey ,$this->rows_insert[$this->primaryKey])->update($update_Arr);

          
            DB::table('history')->insert(
               ["id"=>null ,
                 "user_id" => 1,
                 "changeId" =>   $this->rows_insert[$this->primaryKey],
                 "tableName" =>  $this->table ,
                 "operation" => json_encode($update_Arr),
                 "type" => "update",
                 "created_at" => date('Y-m-d H:i:s')
               ]
            );
            
            $this->messeges[]= "updated..".json_encode($this->rows_insert)."<hr>";

           // dd($this->rows_insert);
         }

          public function render()
          {

           // dd((object) ["hassa"=>"tyfour"]);

            $this->ci++;

          //  dd(empty(" "));
           // dd(config('my.img_url')); 

         //   $this->datas =(object) $this->datas;
         // $this->path  = asset('storage/app/images');
   

              return view('livewire.form-builder' , 
                     [
                        //    "datas" =>DB::table("coloptions")->where("formName" , $this->formName)->where("tableName" ,$this->table)->get(),
                        //     "datas" =>(object)$this->colOptions,
                        //     "update_datas"=>   DB::table($this->table)->where("id",10)->first()
                    ]);
          }

      }