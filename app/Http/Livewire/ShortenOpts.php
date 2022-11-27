<?php
namespace App\Http\Livewire;

use DB;
use Livewire\Component;
use Schema;
//use Session;

class ShortenOpts extends Component
{
    public $table = "coloptions";
    public $formName ="Default";
    //public $formName = "products_update";
    public $dbName = "easypanel";
    public $formNames = [];
    public $tableNames;
    public $colName;
    public $columns;
    public $columns2;
    public $primaryKey;
    public $ic = 0;
    public $options = [];
    public $msgs=[];

    public $formType;
    public $selTable = "coloptions";
    public $newFormName;

    public $copiedColName;
    public $copyFromForm;
    public $copyToForm;

    public $lookTable;
    public $lookCol;
    public $lookcolumns=[];

    public $formAttrs ;
    public $formAttrKey=[];
    public $formAttrVal=[];
    public $formData=[];

    public $optColOption = [];

    public $cloningName;

    public $fieldsBootstrap;

    public $AppNames;
    public $appname;

    public $AppPages;
    public $currentPage;

    public $AppForms;
    public $appform;

    public $lookupIdName;
    public $formMsg;
    public $projectOrPageName;
    public $selectTableToAddCol;
    public $colLength;
    public $colType;
    public $colToAdd;
    public $oldColToAlter;
    public $formJsonKey;
    public $formJsonVal;
    public $createTableQuery="CREATE TABLE IF NOT EXISTS tasks (
 task_id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(255),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;";

    protected $listeners = ["setFormAndTableName" => "setFormAndTableName"];
    
    public function mount()
    {
       // Session::flush();
        $this->init();
    }

    public function init($tb_in=null , $fn_in=null){
        

        if(isset($tb_in) && isset($fn_in)){
            $this->table = $tb_in;
            $this->formName = $fn_in;
        }
          else{
           
        // current form name and table name
        $this->table = session("table_Shorten") ? session("table_Shorten") : $this->table;
        $this->formName = session("formName_Shorten") ? session("formName_Shorten") : $this->formName;
        }
        //get Primary Key
        $result = DB::select(DB::raw("SHOW KEYS FROM {$this->table} WHERE Key_name = 'PRIMARY'"));
        $this->primaryKey = $pk = $result[0]->Column_name;

        //get all Columns names
        $this->columns = Schema::getColumnListing($this->table);
        
        //get columns name from session or default primary key
       // $this->colName = session("colName_Shorten") ? session("colName_Shorten") : $this->primaryKey;
        $this->colName =  $this->primaryKey;

        //get meta data to coloptions table (coloptions is meta data table)
        $this->optColOption = $this->colOptionByColName("Default");

        //  dd(  $this->optColOption);

        $this->getTableNames();
        $this->getFormNames();
        $this->getColOptions();

        $this->AppNames = DB::table("myprojects")->groupby("proName")->pluck("proName")->toArray();
        $this->appname = session("proName_Shorten") ? session("proName_Shorten") : $this->AppNames[0]; //set $this->appname = 0

       // dd($this->appname);  

        $this->AppPages=DB::table("myprojects")->where("proName",$this->appname)->groupby("proPage")->pluck("proPage")->toArray();
   
       // dd($this->AppPages);  
      
        $this->currentPage= session("proPage_Shorten") ? session("proPage_Shorten") : $this->AppPages[0];

        $this->AppForms=DB::table("myprojects")->where("proName",$this->appname)->where("proPage",$this->currentPage)->pluck("proForm")->toArray();
        $this->appform = session("proForm_Shorten") ? session("proForm_Shorten") : $this->AppForms[0];

        $this->fillWithAppsData();
       
    }



    public function AddProject(){

      if(!empty($this->projectOrPageName))  {

        $res = DB::table("myprojects")->where("proName",$this->projectOrPageName)->get();

        if(count($res)>0){
            $this->msgs[] = "Project already exists";
        }
        else{
            DB::table("myprojects")->insert(["proName"=>$this->projectOrPageName ,"proPage" => "defaultPage" ,"proForm"=>"defaultForm"]);
            $this->projectOrPageName = null;
            $this->msgs[] = "Project added";
        }
        $this->init();

      }else{
        $this->msgs[]="Fill name of project";
      }

        
    }

    public function addPageToProject(){
            
            if(!empty($this->projectOrPageName))  {
    
                $res = DB::table("myprojects")->where("proName",$this->appname)->where("proPage",$this->projectOrPageName)->get();
    
                if(count($res)>0){
                    $this->msgs[] = "Page already exists";
                }
                else{
                    DB::table("myprojects")->insert(["proName"=> $this->appname,"proPage"=>$this->projectOrPageName,"proForm"=>$this->formName]);
                    $this->projectOrPageName = null;
                    $this->msgs[] = "Page added";
                }
                $this->init();
    
            }
    }


    public function updateWithCurrentForm(){

        DB::table("myprojects")
        ->where("proName", $this->appname)
        ->where("proPage", $this->currentPage)
        ->where("proForm", $this->appform)
        ->update(["proForm"=>$this->formName]);
        $this->init();
    }

    public function updateFormData(){

        $this->formData["proName"] = $this->appname;
        $this->formData["proPage"] = $this->currentPage;
        $this->formData["proForm"] = $this->appform;
        DB::table("myprojects")
        ->where("myproj_id", $this->formData["myproj_id"])
        ->update($this->formData);
        $this->msgs[] = "Form data updated";
       // $this->emit("refreshPage")
        $this->emit("refreshcomp");
    }

    public function addCurrentForm(){       

        if(!empty($this->formName))  {
                DB::table("myprojects")->insert(["proName"=> $this->appname,"proPage"=>$this->currentPage,"proForm"=>$this->formName]);
                $this->projectOrPageName = null;
                $this->formMsg = "Form added";
            }
            $this->init();
     }
    

    public function updatedAppname (){
        $this->AppPages=DB::table("myprojects")->where("proName",$this->appname)->groupby("proPage")->pluck("proPage")->toArray();
        $this->currentPage=$this->AppPages[0];
        $this->AppForms=DB::table("myprojects")->where("proName",$this->appname)->where("proPage",$this->currentPage)->pluck("proForm")->toArray();
        $this->appform = $this->AppForms[0];
        $this->fillWithAppsData();
    }


    public function updatedcurrentPage(){
        $this->AppForms=DB::table("myprojects")->where("proName",$this->appname)->where("proPage",$this->currentPage)->pluck("proForm")->toArray();
        $this->appform = $this->AppForms[0];

        $this->fillWithAppsData();
    }

    public function updatedAppform($fn){
        $this->fillWithAppsData();
        $res = DB::table("coloptions")->where("formName", $this->appform)->first();       
        $this->updatedTable($res->tableName , $fn);
    }
    
    public function updatedTable($tb , $fn=null)
    {
        $this->getTableNames();
        $this->getFormNames();
        $this->columns = Schema::getColumnListing($this->table);
        //  dd($this->formNames);
        if (count($this->formNames) == 0) {
            $this->formNames[0] = $this->table;
        }
        if($fn==null)
        $this->formName = $this->formNames[0];
        else
        $this->formName =$fn ;

        $this->colName = $this->columns[0];
        $this->getColOptions();
    }

     public function fillWithAppsData(){

      $data= DB::table("myprojects")
            ->where("proName", $this->appname)
            ->where("proPage", $this->currentPage)
            ->where("proForm",$this->appform)->first();

      $this->formData =(array)$data;
      $this->emit("refreshcomp");
     }

     public function addAttrToJson(){

        $jeson = json_decode($this->formData["formAttrs"] , true) ;
    
        $jeson[$this->formJsonKey]= $this->formJsonVal;

        $this->formJsonKey=null;
        $this->formJsonVal=null;

        $this->formData["formAttrs"] = json_encode($jeson);

        $data= DB::table("myprojects")
        ->where("proName", $this->appname)
        ->where("proPage", $this->currentPage)
        ->where("proForm",$this->appform)
        ->update(["formAttrs"=>$this->formData["formAttrs"]]);

     //  $this->formData =(array)$data;
               
          
        $this->fillWithAppsData();
          
     }


     public function deleteJsonByKey($delKey){

        $jeson = json_decode($this->formData["formAttrs"] , true) ;
    
       unset($jeson[$delKey]);

       $this->formData["formAttrs"] = json_encode($jeson);

        $data= DB::table("myprojects")
        ->where("proName", $this->appname)
        ->where("proPage", $this->currentPage)
        ->where("proForm",$this->appform)
        ->update(["formAttrs"=>$this->formData["formAttrs"]]);

        $this->fillWithAppsData();
       
     }


    public function setFormAndTableName($tb , $fn){
      
        $this->init($tb , $fn);
       
    }

    public function getTypeofColumnAndLangth($colName){
        $type = DB::select("SELECT DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$this->table' AND COLUMN_NAME = '$colName'");
        if(count($type)>0){
            return $type[0]->DATA_TYPE . ($type[0]->CHARACTER_MAXIMUM_LENGTH?"(".$type[0]->CHARACTER_MAXIMUM_LENGTH.")":"");
        }else{
            return null;
        }
    }  

    public function renameColumnName($oldName,$newName){
        DB::statement("ALTER TABLE $this->table RENAME COLUMN $oldName TO $newName");
    }
    
    public function alterColumnLengthAndType(){

        $table = $this->selectTableToAddCol;
        $newColName = $this->colToAdd;
        $oldColName =$this->oldColToAlter;
        $type = $this->colType;
        $length = $this->colLength;

        if($table && $newColName && $oldColName && $type){     
            
            if($length){
                $type = $type."(".$length.")";
            }
            DB::statement("ALTER TABLE $table CHANGE  $oldColName $newColName $type");       
            $this->msgs[] = "Column $newColName has been altered";    
            $this->init();

        }else{   

            $this->msgs[] = "Please fill all the fields";
        }
    }

    public function updatedselectTableToAddCol(){
        $this->columns2 = Schema::getColumnListing($this->selectTableToAddCol);
      }




    public function addColumnToTable(){

        //add new column to table
        if($this->selectTableToAddCol && $this->colToAdd &&  $this->colToAdd  && $this->colType){
            
        
        if($this->colLength){

            $query = "ALTER TABLE ".$this->selectTableToAddCol." ADD ".$this->colToAdd." ".$this->colType."(".$this->colLength.")";
        
        }else{
            $query = "ALTER TABLE ".$this->selectTableToAddCol." ADD ".$this->colToAdd." ".$this->colType;
        }

        try{

            DB::statement($query);
            $this->init();
            $this->msgs[] = "Column Added";

        }catch(\Exception $e){

            $this->msgs[] = $e->getMessage();
        }

    }else{
        $this->msgs[] = "Please fill all the fields";
    }

    

    }

   // get options from coloptions table to every column
    public function getColOptions()
    {

       // dd($this->table, $this->colName, $this->formName);
       
       // selected column or default primary key
        $res = $this->regTableInColOptions($this->table, $this->colName, $this->formName);

        // if $res == null that means no form name for this table
        // if there are options for this form then :
        if (count($res) > 0) {
            $this->options =(array) $res[0];
        } else {
            // if there are not any options Create Default Options
            // and if form not exist then create new form by default same as table name
            $this->primaryKey = $this->getAutoIncreamentName($this->table);
            $res = $this->regTableInColOptions($this->table, $this->primaryKey, $this->table);
            $this->options = (array) $res[0];
        }

    }
    
    // register table in coloptions table
    public function regTableInColOptions($tb_name,$primaryKey, $formName)
    {

        // knowin table not  found in table
        $columns = Schema::getColumnListing($tb_name);
        //$autoIncName = $this->getAutoIncreamentName($tb_name);

        $coloptionsoftable = DB::table("coloptions")->where("formName", $formName)->where("tableName", $tb_name)->get();
        //dd( $coloptionsoftable);

        //if form not exist create one new   //important
        // form is agregate of table and form name

        foreach ($columns as $column) {
             $result = DB::table("coloptions")->where("formName", $formName)->where("tableName", $tb_name)->where("colName", $column)->get();
           //if column meta data not exist (EX : admin add new column) in coloptions table create one new
            if ($result->count() == 0) {
                DB::table("coloptions")->insert(["formName" => $formName, "tableName" => $tb_name, "colName" => $column, "eng_name" => $column, "arabic_name" => "", "autoIncreament" => $primaryKey, "inputType" => "text", "widget" => "", "bootstrap" => "col-md-2", "lookup" => "", "formType" => 0]);
            }
        }

        // if any colomn in meta data table not in table then delete them  (EX : admin delete or update new column)
        foreach ($coloptionsoftable as $colopt) {
            if (!in_array($colopt->colName, $columns)) {
                DB::table("coloptions")->where("formName", $formName)->where("tableName", $tb_name)->where("colName", $colopt->colName)->delete();
            }
        }
        
        return DB::table("coloptions")->where("formName", $formName)->where("tableName", $tb_name)->where("colName", $primaryKey)->get();

    }

    public function createLookupTable(){
        try{
             DB::statement($this->createTableQuery);
             $this->msgs[]="The Table has been created successfully";
             $this->init();
             
         }catch(\Exception $ex) {
            $this->msgs[] = "Error  .." . $ex->getMessage() . "___" . $ex->getLine();
           
         };        
    }


    public function  orderFormCols(){
       $form= DB::table("coloptions")->where("formName", $this->formName)->get();
      $i = 10;
       foreach($form as $f){
           DB::table("coloptions")->where("colOptions_id", $f->colOptions_id)->update(["ordering"=>$i]);
           $i+=10;
       }
       
        $this->msgs[]="orderFormCols: " . $this->formName;
    
    }

    public function  setFormBootstrap(){
        $form = DB::table("coloptions")->where("formName", $this->formName)->get();
      
        foreach($form as $f){
            DB::table("coloptions")->where("colOptions_id", $f->colOptions_id)->update(["bootstrap"=>trim($this->fieldsBootstrap)]);
           
        }
        $this->init();
        $this->msgs[]="setb fields Bootstrap: " . $this->formName;
    }

    public function  setInputType(){
        $form = DB::table("coloptions")->where("formName", $this->formName)->get();
      
        foreach($form as $f){
            DB::table("coloptions")->where("colOptions_id", $f->colOptions_id)->update(["inputType"=>trim($this->fieldsBootstrap)]);
           
        }
        $this->init();
        $this->msgs[]="setInputType: " . $this->formName;
     }


    public function CloneFormAsName(){

        if (empty(trim($this->cloningName))){
            $this->msgs[]="يجب وضع اسم  للفورم";
        }else{

            // check if name not exict
            $res = DB::table("coloptions")->where("formName" , trim($this->cloningName) )->get();

          //  dd($res->count());
           
            if($res->count() > 0){

                $this->msgs[]="يجب وضع اسم فريد  للفورم";


            }else{              
              
                $result=  DB::table("coloptions")->where("formName" , $this->formName )->get();
               
                foreach ($result as $item => $row) {
                    $rowChange = (array)$row;
                    $rowChange["formName"] = trim($this->cloningName);// change name of form to new
                    unset($rowChange["colOptions_id"]); // remove id to clone not douplucate
                    DB::table("coloptions")->insert($rowChange);
                }

                $this->init();             
                $this->msgs[]="تم نسخ الفورم الجديد :".$this->formName;

            }  
        }
     
    }

    public function SaveFormOption(){

        $frAttrs=[];         
        foreach ($this->formAttrKey as $key => $keyName) {
            $frAttrs[$keyName]=$this->formAttrVal[$key];        
        }

       // dd($frAttrs);

        $this->options['formAttrs']=\json_encode($frAttrs);
      
        $this->saveOptions();
    }

    public function calculateAttrField(){

     // if(empty($this->options['formAttrs'])) return "";
      $c=0;
      $this->formAttrKey=[];
      $this->formAttrVal=[];

      $formAttrs= \json_decode($this->options['formAttrs'], true) ;      
      if($formAttrs){
        foreach($formAttrs as $key => $val){

            $this->formAttrKey[$c] = $key;
            $this->formAttrVal[$c] = $val;
            
            $c +=1;
        }  
    }
     //   dd($this->formAttrKey , $this->formAttrVal);

    }

   
    public function addField(){

      //  $this->options['formAttrs'] = empty($this->options['formAttrs'])?'[]':$this->options['formAttrs'];   

        $formAttrs = \json_decode($this->options['formAttrs'] , true);  
      
        $formAttrs[""]="";       

        $this->options['formAttrs']= \json_encode($formAttrs);  

        $this->saveOptions();     

    } 
    
    public function removeAttr($k){
        $formAttrs = \json_decode($this->options['formAttrs'] , true);  
        unset($formAttrs[$k]);
        $this->options['formAttrs']= \json_encode($formAttrs);  

        $this->calculateAttrField();
        $this->saveOptions();     

    }   

    public function updatedLookTable($tb){

        $this->options["lookup"]=$tb."|";
        $this->lookcolumns = Schema::getColumnListing($tb);
      
    }

    public function updatedLookCol($col){

        $this->options["lookup"].= $col."|";
     
        $this->saveOptions();
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('hideTopDiv', []);
    }

    public function updatedOptions(){
        $this->saveOptions();
    }

    // shared functions;
    public function colOptionByColName($formName)
    {
        //  form MustBe Unique
        $opts = [];
        $rows = DB::table("coloptions")->where("formName", $formName)->get();
        foreach ($rows as $k => $row) {
            foreach ($row as $kn => $col) {
                $opts[$row->colName] = (array) $row;
            }
        }

        return $opts;
    }

    public function copyOptionsToForm()
    {

        foreach ($this->columns as $col) {

            $fromForm = $this->copyFromForm; //sorce of data
            $toForm = $this->copyToForm;
            $copiedColName = $this->copiedColName; //  targat to update

            $sql = "UPDATE `coloptions` set {$copiedColName} =
             (select {$copiedColName} from coloptions where formName = '{$fromForm}' and colName = '{$col}')
             where formName = '{$toForm}' and colName = '{$col}'";

            DB::select(DB::raw($sql));

        }

        $this->msgs[]="تم نسخ خيارات من قورم الى فورم ثاني بنجاح";

    }

    public function createNewForm()
    {
        // $formType;
        // $selTable;
        $res = DB::table("coloptions")->where("formName", $this->newFormName)->first();

        if (trim($this->newFormName != "")) { // if not spaces

            if ($res) {

                $this->msgs[]="you Can not create Form The name used";

            } else {

                $this->formType = (int) filter_var($this->formType, FILTER_SANITIZE_NUMBER_INT);
                $autoIncName = $this->getAutoIncreamentName($this->selTable);
                $res = $this->regTableInColOptions($this->selTable, $autoIncName, $this->newFormName, $this->formType);

                $this->msgs[]="The Form has  created Form";
            }

        } else {
            $this->msgs[]="the Name of form require";
        }

    }

    public function saveOptions()
    {
        $arrOfNull = ["arabic_name","formBootstrap","onEventFn" ,"lookup"  ,"dyInitVal" , "defaultVal" ,"validation" , "action" , "param1" , "param2" ,"param3" ,"param4" ,"param5"];
        $id = $this->options["colOptions_id"];
        $opTosave = $this->options;
        unset($opTosave["colOptions_id"]);

       // dd($opTosave);

        foreach($opTosave as $k => $val){
            if(in_array($k , $arrOfNull)){
                if(trim($val)=='')
                $opTosave[$k] = null;
            }
        }

       // dd($opTosave);

        DB::table("coloptions")->where("colOptions_id", $id)->update($opTosave);

      // $this->msgs[]="تم تحديث الخيارات بنجاح";

      //  $this->emit("refreshcomp");

    }

   

    public function updatedColName()
    {
        $this->getColOptions();
    }

    public function UpdatedFormName()
    {
        $this->getColOptions();
    }   

    public function getFormNames()
    {

        $this->formNames = [];
        $res = DB::table("coloptions")
            ->select('formName')
            ->where("tableName", $this->table)
            ->groupBy("formName")
            ->get();

        foreach ($res as $k => $rs) {
            $this->formNames[] = $rs->formName;
        }

    }

    public function getTableNames()
    {
        $blockTables = ["failed_jobs", "migrations", "password_resets", "personal_access_tokens", "sessions"];
        $Tb_names = DB::select('SHOW TABLES');
        $this->tableNames = [];
        foreach ($Tb_names as $tb_name) {
            foreach ($tb_name as $k => $tb) {
                if (!in_array($tb, $blockTables)) {
                    $this->tableNames[] = $tb;
                }

            }
        }
    }

   

    public function getAutoIncreamentName($tb_name)
    {
        $result = DB::select(DB::raw("SHOW KEYS FROM {$tb_name} WHERE Key_name = 'PRIMARY'"));
        return $result[0]->Column_name;
 
 
 
        // dd($result);

        // if(!empty($result)){   
           
        // }
        // else {

        //  $this->msgs[] = "يجب تعيين مفتاح أساسي  متزايد";

        //  $this->mount();

        //}
               
    }


    public function render()
    {
       // Session::flush();

        session()->put('table_Shorten', $this->table);
        session()->put('formName_Shorten', $this->formName);
        session()->put('colName_Shorten', $this->colName);

        session()->put('proName_Shorten', $this->appname);
        session()->put('proPage_Shorten', $this->currentPage);
        session()->put('proForm_Shorten', $this->appform);

        $this->calculateAttrField();

        $this->ic++;
        return view('livewire.shorten-opts');
    }
    
}