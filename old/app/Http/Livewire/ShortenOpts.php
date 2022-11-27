<?php

namespace App\Http\Livewire;

use DB;
use Livewire\Component;
use Schema;

class ShortenOpts extends Component
{
    public $table = "bill_headers";
    // public $formName ="Default";
    public $formName = "products_update";
    public $dbName = "easypanel";
    public $formNames = [];
    public $tableNames;
    public $colName;
    public $columns;
    public $primaryKey;
    public $ic = 0;
    public $options = [];
    public $msgs;

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

    public $optColOption = [];

    protected $listeners = ['saveFormAttr'=>'saveFormAttr'];

    public function mount()
    {

        $this->table = session("table_Shorten") ? session("table_Shorten") : $this->table;
        $this->formName = session("formName_Shorten") ? session("formName_Shorten") : $this->formName;
        $result = DB::select(DB::raw("SHOW KEYS FROM {$this->table} WHERE Key_name = 'PRIMARY'"));
        $this->columns = Schema::getColumnListing($this->table);
        $this->primaryKey = $result[0]->Column_name;
        $this->colName = session("colName_Shorten") ? session("colName_Shorten") : $this->primaryKey;
        $this->tableNames = $this->getTableNames();
        $this->optColOption = $this->colOptionByColName("Default");

        //  dd(  $this->optColOption);

        $this->getTableNames();
        $this->getFormNames();
        $this->getColOptions();
        
       // dd($this->options);
    }


    public function saveFormAttr($frAttrs){

     //   dd($frAttrs);

        $this->options['formAttrs']=\json_encode($frAttrs);

        $this->saveOptions();
    }

    public function fillFormAttr(){

     // if(empty($this->options['formAttrs'])) return "";

      $str="";
      $formAttrs= \json_decode($this->options['formAttrs'], true) ;     

        foreach($formAttrs as $key => $val){
          
          $str .= "<span wire:click='removeAttr(\"{$key}\")'  class='addAttr' >-</span><input class='formAttrKey' type='text' placeholder='Key' style='width:30%' value='{$key}'>
                <input class='formAttrVal' type='text' placeholder='Value' style='width:60%' value='{$val}'>";
       }     

       return $str;
    }

     public function calcFormAttrs(){

        $this->options['formAttrs'] = is_null($this->options['formAttrs'])?'[]':$this->options['formAttrs'];
        $this->saveOptions();

        return  $this->fillFormAttr();
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

    public function hydrate(){
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

    // public function showMsg($msg)
    // {
    //     $this->msgs[] = $msg;
    //     $this->dispatchBrowserEvent('showTopDiv', []);
    // }

    //  public function hydrate(){

    // }

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
        $arrOfNull = ["dyInitVal" , "defaultVal" ,"validation" , "action" , "param1" , "param2" ,"param3" ,"param4" ,"param5"];
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

        $this->msgs[]="تم تحديث الخيارات بنجاح";

        $this->emit("refreshcomp");

    }

    public function updatedTable($tb)
    {
        $this->getTableNames();
        $this->getFormNames();
        $this->columns = Schema::getColumnListing($this->table);
        //  dd($this->formNames);
        if (count($this->formNames) == 0) {
            $this->formNames[0] = $this->table;
        }

        $this->formName = $this->formNames[0];
        $this->colName = $this->columns[0];
        $this->getColOptions();
    }

    public function updatedColName()
    {
        $this->getColOptions();
    }

    public function UpdatedFormName()
    {
        $this->getColOptions();
    }

    public function getColOptions()
    {
        $res = $this->regTableInColOptions($this->table, $this->colName, $this->formName);
        // if there are options for this form then :
        if (count($res) > 0) {
            $this->options = (array) $res[0];
        } else {
            // if there are not any optios Create Default Options
            $this->primaryKey = $this->getAutoIncreamentName($this->table);
            $res = $this->regTableInColOptions($this->table, $this->primaryKey, "Default");
            $this->options = (array) $res[0];
        }

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

    public function regTableInColOptions($tb_name = "program_notes", $primaryKey, $formName, $formType = 0)
    {

        // knowin table not  found in table
        $columns = Schema::getColumnListing($tb_name);
        $autoIncName = $this->getAutoIncreamentName($tb_name);
        $coloptionsoftable = DB::table("coloptions")->where("formName", $formName)->where("tableName", $tb_name)->get();
        //dd( $coloptionsoftable);

        //if  form not exist create one new
        foreach ($columns as $column) {
            $result = DB::table("coloptions")->where("formName", $formName)->where("tableName", $tb_name)->where("colName", $column)->get();
            if ($result->count() == 0) {
                DB::table("coloptions")->insert(["formName" => $formName, "tableName" => $tb_name, "colName" => $column, "eng_name" => $column, "arabic_name" => "", "autoIncreament" => $autoIncName, "inputType" => "text", "widget" => "", "bootstrap" => "col-md-6", "lookup" => "", "formType" => $formType]);
            }
        }

        // if change column name will delete olde
        foreach ($coloptionsoftable as $colopt) {
            if (!in_array($colopt->colName, $columns)) {
                DB::table("coloptions")->where("formName", $formName)->where("tableName", $tb_name)->where("colName", $colopt->colName)->delete();
            }
        }

        return DB::table("coloptions")->where("formName", $formName)->where("tableName", $tb_name)->where("colName", $primaryKey)->get();

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
        session()->put('table_Shorten', $this->table);
        session()->put('formName_Shorten', $this->formName);
        session()->put('colName_Shorten', $this->colName);

        $this->ic++;
        return view('livewire.shorten-opts');
    }
    
}
