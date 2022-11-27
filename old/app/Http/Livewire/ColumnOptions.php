<?php

      namespace App\Http\Livewire;
      use DB;
      use Schema;      
      use Livewire\Component;
      use Faker\Factory;
      
      class ColumnOptions extends Component
      {
        
        // public $table ="bill_headers";
        // public $formName ="bill_headers_in";Default

        public $table ="bill_headers";
        public $formName ="Default";
        public $dbName ="easyPanel";
        public $formNames;
        public $tableNames;
        public $colName;
        public $columns;
        public $primaryKey;
        
        public $newtable;
        public $formType;
        public $newformName;
        public $newFormName1;

        public $options=[];

        
        public $toFormName;

        protected $listeners = ['saveOption' => 'saveOption'];
     
         public function mount (){

            $this->table =  session("table") ? session("table") : $this->table;
            $this->formName =  session("formName") ? session("formName") : $this->formName;
            $result = DB::select(DB::raw("SHOW KEYS FROM {$this->table} WHERE Key_name = 'PRIMARY'"));
            $this->columns = Schema::getColumnListing($this->table);    
           // dd( $result[0]->Column_name);      
            $this->colName = session("colName") ? session("colName") : $result[0]->Column_name;    
            $this->tableNames = $this->getTableNames();   
            $this->getColOptions();

            // dd($this->formNames , $this->table , $this->formName);
         }

         public function copyColOptionOtherForm (){

          $res =  DB::table("coloptions")
                  ->Where("formName" , $this->formName )
                  ->where("tableName",$this->table)
                  ->where("colName" , $this->colName)
                  ->first();

         $resSourse =  DB::table("coloptions")
         ->Where("formName" , $this->toFormName )
         ->where("tableName",$this->table)
         ->where("colName" , $this->colName)
         ->first();



        $arr = (array)$res;
         unset($arr["colOptions_id"]);
         unset($arr["formName"]);
         unset($arr["formType"]);

        // dd($arr);
        dd($arr , $resSourse , $resSourse->colOptions_id) ;

          DB::table("coloptions")
          ->where("colOptions_id" , $resSourse->colOptions_id)
          ->update($arr);

          dd($arr);


          session()->flash('message' ,  "تم تحديث العمود بنجاح");
        //    dd((array)$res );


         }

         public function updateFormName(){
          
            DB::table("coloptions")
              ->where("tableName" ,$this->table)
              ->where("formName" , $this->formName)
              ->update(["formName"=>$this->newFormName1]);
              $this->formName = $this->newFormName1;
              $this->newFormName1=null;
         }

        public  function getTableNames(){
          $blockTables = ["failed_jobs" , "migrations"  , "password_resets" , "personal_access_tokens" , "sessions"];
          $Tb_names =  DB::select('SHOW TABLES');
          $Arr = [];
          
          foreach ($Tb_names as $tb_name){

            if( !in_array($tb_name->Tables_in_easypanel , $blockTables) )
                $Arr[] =  $tb_name;
          }
            return $Arr;
        } 

        public function createNewFormOptions (){
         
         $val["newtable"] ="required";
         $val["formType"] = "required";
         $val["newformName"] ="required";
         $this->validate($val);

         $this->formType =(int)filter_var($this->formType, FILTER_SANITIZE_NUMBER_INT);
         $autoIncName = $this->getAutoIncreamentName($this->newtable);
         $res = $this->regTableInColOptions ($this->newtable , $autoIncName ,   $this->newformName  , $this->formType );
       
      }

       public function getFormNames (){

         return  DB::table("coloptions")   
                     ->select('formName')                    
                     ->where("tableName" , $this->table)   
                     ->groupBy("formName")                                                  
                     ->get();

       }

        public function changeTable(){

            $this->columns = Schema::getColumnListing($this->table);
            $this->colName = $this->getAutoIncreamentName($this->table);
            $this->formNames = $this->getFormNames();
            $this->formName = "Default";

         }
    

        public  function getColOptions(){

       //   dd($this->table , $this->colName , $this->formName);

               $this->tableNames = $this->getTableNames();
               $this->formNames = $this->getFormNames();


            //   dd( $this->tableNames , $this->formNames);

               $res = $this->regTableInColOptions($this->table  , $this->colName , $this->formName);  

               
              
               if(count($res)>0 ){
                  $this->options = (array) $res[0];  
               } 
               else{              
                $result = DB::select(DB::raw("SHOW KEYS FROM {$this->table} WHERE Key_name = 'PRIMARY'"));
                $this->primaryKey = $result[0]->Column_name;
                $res = $this->regTableInColOptions($this->table  , $this->primaryKey , "Default"); 
                $this->options = (array) $res[0];  
               }
              //dd($this->formNames);
             //  dd($this->colName ,  $this->formName  , $res  , $this->table);
                            
         }

          
            public function  saveOption ($obj , $id) {
           

             // dd($id , $obj);

                DB::table("coloptions")->where( "colOptions_id", $id )->update($obj);
            
                session()->flash('message' ,  "تم تحديث الصفات بنجاح");
            }


            function getAutoIncreamentName ($tb_name){
                $result = DB::select(DB::raw("SHOW KEYS FROM {$tb_name} WHERE Key_name = 'PRIMARY'"));
                return $result[0]->Column_name;        
             }

            function regTableInColOptions ($tb_name="program_notes" , $par_colName ,  $formName ="Default" , $formType = 2	){
                                
               // knowin table not  found in table 
                  $columns = Schema::getColumnListing($tb_name);
                  $autoIncName = $this->getAutoIncreamentName($tb_name);
                  $coloptionsoftable = DB::table("coloptions")->where("formName" , $formName)->where("tableName" , $tb_name )->get();        
                  //dd( $coloptionsoftable);           
                   foreach ($columns as $column) {
                     $result = DB::table("coloptions")->where("formName" , $formName)->where("tableName", $tb_name)->where("colName", $column)->get();
                     if ($result->count() == 0) {
                         DB::table("coloptions")->insert(["formName" => $formName ,"tableName" =>$tb_name, "colName" => $column,"eng_name"=> $column ,"arabic_name"=>"" ,"autoIncreament"=> $autoIncName , "inputType" => "text" , "defaultVal"=>"" ,"widget"=>"" ,"bootstrap" => "col-md-6" ,"imgSrc" =>"" ,"lookup"=>"" ,"formType"=>$formType]);
                     }
                   }     
                   
                   foreach($coloptionsoftable as $colopt){
                       if (!in_array($colopt->colName, $columns)){
                          DB::table("coloptions")->where("formName" , $formName)->where("tableName" , $tb_name )->where("colName",$colopt->colName)->delete();
                      }
                   }
             
                   return DB::table("coloptions")->where("formName" , $formName)->where("tableName" , $tb_name )->where("colName" ,$par_colName)->get();
                    
                 }
          

          public function render()
          {


            session()->put('table', $this->table);
            session()->put('formName', $this->formName);
            session()->put('colName', $this->colName);

            
           // $faker = Factory::create();
           // dd($faker->name , $faker->unique()->safeEmail);
           
           // dd(null==false); 

           //   dd( DB::table("coloptions")->orderBy("formName")->get()->groupBy(['tableName' ,'formName']));


           

          // dd($this->options);

              return view('livewire.column-options',[
              
                   "formsforTable" =>  DB::table("coloptions")->orderBy("formName")->get()->groupBy(['tableName' ,'formName'])
              ]);
          }
      }