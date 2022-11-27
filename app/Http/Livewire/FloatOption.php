<?php

namespace App\Http\Livewire;
use DB;
use Livewire\Component;
use App\MyClass\Tree;
use App\MyClass\carFileExtention;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;

class FloatOption extends Component
{
    use carFileExtention;
    use WithPagination;
    use WithFileUploads;

    protected $listeners = ["setDate" => "setDate" , "refreshcomp" => "refreshcomp"];
    protected $paginationTheme = 'bootstrap';

    public $colArr = [];
    public $colArrPar = [];
    public $rows;

    public $validateState = 0;

    public $formOpts = []; // absolutation

    public $header_id;

    public $rowOpts = []; // array links  row_id with form name and typeof forme single or multiple // not reset // when add row must montion releted form

    public $refs = [];

    public $forms = [];

    public $hideFrame = [];

    public $msgs = [];

    public $stopAuto = true;

    protected $arrows = [];

    public $preventNewRowIfRowsZero = "false";

    public $searching;

    public $colArrTitle;

    public $photos=[];
    public $photosDesc=[];

    public $currentFn;
    public $currentCol;
    // public $floatDiv;

   

    // array of forms
    // ['paidtypes', 'productstate', 'trialbalances', 'entryheads', 'entries', 'Create New Invoice Header', 'Create New Invoice Body']

    public function mount( $fn = "floatOptions" , $col = 'colOptions_id')
    {

     //   dd(  $fn ,  $col );

        $this->forms = ['floatOptions'];
        $this->currentFn = $fn;
        $this->currentCol = $col;

      

        $this->init();
        // fill All Form Optios 

       // dd($this->formOpts);
       //  dd($this->refs);
    }

    public function updatedCurrentCol($sel)
    {
        
        $this->currentCol = explode("|",$sel)[0];

        $this->formsLoop();
    }

    public function excuteFunctionEndOfForm($row_id, $action){

         $opts = $this->geOptionsOfForm($this->currentFn);

        

        if ($action == "drawSelectableCol") {

            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white;z-index:1'>
                            <div class='row' >
                                <div class='col-6' >

                                <select wire:model='currentCol' class='m-1 form-select'>";
                                      $str.="<option value=''>Select Column</option>";
                                     foreach ($opts as $K => $val)
                                      $str.=  "<option>{$K}|{$val['inputType']}</option>";                                

                                $str.="</select>
                                </div>
                                <div class='col-6' >    
                                <select wire:model='currentFn' class='m-1 form-select'>
                                <option value='1'>picke Table</option>"; 
                                foreach ($this->getFormNames() as $K => $val)
                                $str.=  "<option>{$val->formName}</option>";  
                                $str.="</select>
                                </div>
                                </div>
                                </div>";
            return $str;
        }
    }

    public function getFormNames (){

        $res =  DB::table("coloptions")   
                    ->select('formName')           
                    ->groupBy("formName")                                                  
                    ->get();


        return $res;
      }

    public function init()
    {
        foreach ($this->forms as $form) {

            $this->formOpts[$form] = $this->geOptionsOfForm($form);
            $this->formOpts[$form]["cols"] = $this->sortColumns($this->formOpts[$form]);
            $this->formOpts[$form]["fn"] = reset($this->formOpts[$form])["formName"];
            $this->formOpts[$form]["tbName"] = reset($this->formOpts[$form])["tableName"];
            $this->formOpts[$form]["pk"] = reset($this->formOpts[$form])["autoIncreament"];
            $bootStrap = reset($this->formOpts[$form])["formBootstrap"];           
            $this->formOpts[$form]["bf"]= explode("|"  , $bootStrap);
            $this->formOpts[$form]["vis"] = reset($this->formOpts[$form])["formTitleVis"];
            $attr = reset($this->formOpts[$form])["formAttrs"];

           // dd( $this->formOpts[$form]["bf"]);

            $attrs = json_decode($attr, true);

            if (!empty($attrs)) {

                foreach ($attrs as $key => $value) {
                    $this->formOpts[$form][$key] = $value;
                }

            }

        }

        $this->formsLoop();
    }

 

    public function refreshcomp(){
        $this->init();
    }

     public function showFloatComponent($ComponentName){

        $this->floatDiv = $ComponentName;

     }

     public function hideFloatComponent(){

        $this->floatDiv = null;

     }


    public function showGlobalVar()
    {
       
       // dd($this->refs);

       dd($this);

      // $this->setPage(1 , 'entryheads');
    }

    public function formsLoop()
    {
       // Log::channel('mht')->info("start of formsLoop .. ");

        $this->rows = -1;
        $this->colArr = [];
        //$this->colArrPar = [];
        $this->refs = [];

        // autocomplete must check value first of get default value while we enter  new value
        // $this->colArrPar = []; releted to colArr

        foreach ($this->forms as $form) {

            $pk = $this->formOpts[$form]["pk"];
            $fn = $this->formOpts[$form]["fn"];
            $table = $this->formOpts[$form]["tbName"];
            $opts = $this->formOpts[$form];
            $action = $opts[$pk]["action"];

            // dd($opts);

            //entryType many rows share with refrence with ont head table
            // point of start of form   

            // اما ان يكون مصدر الفور هو كويري وغير متعلق بجدول اب ويكون اما لوحده او اب لجدول 
            //1//
            if ($action == 'getParentFormSource') {

                if($opts[$pk]["param2"]== null) dd("You Must Set PerPage number to  :" . $fn);

                        $page ="floatOptions";

                       // dd($this->currentFn ,  $this->currentCol);
                        $this->arrows[$page] = DB::table("coloptions")
                        ->where('colName' , $this->currentCol)
                        ->where("formName", $this->currentFn)
                        ->paginate($opts[$pk]["param2"], ['*'], $page);
                    
                        
                }
                    
                    
                if ($this->arrows[$page]->total() == 0) {
                   // اذا كان الجدول فارغا فينشىء سطر من الالقيم الافتراضية
                   
                       dd("غير موجود في جدول الميتا");
                } else {

                    $this->fillMultipleRow($opts, $this->arrows[$page], "group");

                 //   dd($this->refs);

                }
            
             // get Parent From Refrence how defined in setParentRrefrence in column type refrence
             // واما ان يكون فورم ابن يكون متعلق بمرجع من جدول اخر
             //2//
            if ($action == 'getParentRefrence') {
                 
                 
                // dd($opts[$pk]);
                // first pick refrence


                if(!isset($this->refs[$opts[$pk]["param1"]][$opts[$pk]["param2"]]))
                    $this->msgs[] ="no parent refrence exist to form :" .$fn;
                 else{

                    $refVal = $this->refs[$opts[$pk]["param1"]][$opts[$pk]["param2"]];
                    $row = DB::table($opts[$pk]["param3"])->where($opts[$pk]["param4"], $refVal)->get();

                        if ($row->Count() > 0) {

                            $this->fillMultipleRow($opts, $row, "group");

                        } else {

                        $arr = ["p_entries", "suppliers"]; // not generate default row if empty
                        //  Log::channel('mht')->info("default New " . $fn);
                        if (!in_array($fn, $arr)) {
                        // generate if not have data rows //  ex: entries must have one row for erery entry head
                        // entries  Always related  to parent its table child not  independent
                                $this->createNewFormRow(null, $fn);
                        }

                        }
                    }
                }

               
            }

    // dd($this->refs);

    }

    public function fillMultipleRow($opts, $dataRows, $group = null)
    {
        //  Mht :  One row is  special state from multi row
        //  in same time we  deal with one row
        //  الفورم ممكن يكون الاب او الابن
        //  يجب ان نحدد في البداية مصدر معلوماته
        //  كل سطر يمثل فورم له خيارات اوبشن وله داتا
        //  غالبا العمود المصدر يكون في فورم مفرد

        $row = $this->rows;
        $fvis = $opts["vis"];

        $type = "";
        $rowscount = count($dataRows);
        $ic = 0;

        $fn = $opts['fn'];


         // define if row infirst col for open new div

        foreach ($dataRows as $r => $rw) {

         // $rw row of database

            $row += 1;

            if ($group == "group") {

                if (($rowscount - 1) == 0) {
                    $type = "oneRow";
                } else if ($ic == 0) {
                    $type = "start";
                } else if ($ic == ($rowscount - 1)) {
                    $type = "end";
                } else {
                    $type = "none";
                }

                $ic++;
            }

           // dd($opts);

            $this->rowOpts[$row] = [$opts["fn"], $type];

         //   if($fn)

          //  Log::channel('mht')->info( "formName :" .$fn);

            foreach ($opts["cols"] as $col) {

                Log::channel('mht')->info( "inputType :{$fn}|" .$col."|");

                if ($opts[$col]['inputType'] == 'auto') {

                    if ($this->stopAuto == true) {
                        $this->colArrPar[$row][$col] = $this->getLookUpValues($opts, $col, $rw->$col);
                    }                  

                }                
                if ($opts[$col]['inputType'] == 'classify') {

                    $this->colArrPar[$row][$col] = $this->getLookUpValues($opts, $col, $rw->$col);
                  

                }                
                if ($opts[$col]["action"] == "setParentRrefrence") {
                    //$parentForm = $opts[$col]["param1"];
                    //$parentCol = $opts[$col]["param2"];
                    $this->refs[$fn][$col] = $rw->$col;
                 
                } 

                // if( $opts["fn"] == 'Create New Invoice Body' && $col == 'product_id'){
                    
                //    // dd($rw->$col);
                //     $this->colArrTitle[$row][$col]="العدد الموجود في المخزن (" .$this->calculateProductCount($rw->$col) .')';
                      
                // }

                  $this->colArr[$row][$col] = $rw->$col;           

            }

        }

        $this->rows = $row;

                    
      if (method_exists($this ,"endOfFillingWithData")){
          $this->endOfFillingWithData();
        }
      
        
        //  dd( $this->refs);
        // if($this->rows == 6)
        // dd($this->colArr);
    }

    public function updatedPhotos (){
        $this->formsLoop();
    }

    public function updatedSearching (){
        $this->formsLoop();
    }

    public function updatedPaginators()
    {
        $this->colArrPar=[];
        $this->formsLoop();
    }

    public function updatedColArr($val, $RowColName)
    {

        $rowArr = explode(".", $RowColName);
        $row_id = $rowArr[0];
        $col = $rowArr[1];
        $formName = $this->rowOpts[$row_id][0];
        //dd( $formName , $this->rowOpts);
        $opts = $this->formOpts[$formName];
        $debtorSum = 0;
        $creditorSum = 0;

        //  $ref = $this->colArr[$row_id]["entry_no"];

        if ($formName == 'entries') {

            foreach ($this->colArr as $rw_id => $row) {

                if ($this->rowOpts[$rw_id][0] == "entries") {

                    foreach ($row as $k => $v) {

                        if ($k == "debtor") {
                            $debtorSum += intval($v);
                        }

                        if ($k == "creditor") {
                            $creditorSum += intval($v);
                        }

                    }

                }
            }

            if ($debtorSum == $creditorSum) {

                $this->msgs["entries"] = "القيد متوازن";
            } else {
                $this->msgs["entries"] = "القيد غير متوازن";
            }

        }

        if ($formName == 'Create New Invoice Body') {           
            $this->moveBillItemToEntries ($row_id, $col);              
        }
 
        $this->saveRowOnUpdate($row_id, $col);

    }

    public function setDate($row_id, $colName, $v)
    {
        //dd($colName , $v);
        $this->colArr[$row_id][$colName] = $v;

        $this->saveRowOnUpdate($row_id, $colName);

        //$this->validateHeader();

    }

    

    public function saveRowOnUpdate($row_id)
    {

        // $rowArr = explode(".",$RowColName);
        // $row_id =  $rowArr[0];
        // $col = $rowArr[1];

        // dd($row_id, $col);

        $formName = $this->rowOpts[$row_id][0];

        $opts = $this->formOpts[$formName];

        // dd( $row_id , $formName ,$opts );

        $table = $opts["tbName"];

        $pk = $opts["pk"];

        $id = $this->colArr[$row_id][$pk];

        //  dd($row_id ,$col, $table ,$pk ,  $id);

       

        try {

            $result = DB::table($table)->where($pk, $id)->update($this->colArr[$row_id]);
            
            if ($result) {
                $this->msgs[] = "تم التعديل بنجاح";
            } else {
                $this->msgs[] = "لم يتم التعديل";
            }
            
           // $this->formsLoop();

        } catch (\Exception $ex) {
            $this->msgs[] = "Error  .." . $ex->getMessage() . "___" . $ex->getLine();
        }
        $this->stopAuto=true;

       
        $this->formsLoop();
    }

    public function cleanAuto($row_id, $colName)
    {
        // dd($row_id ,$colName);
        $this->colArrPar[$row_id][$colName] = null;
        $this->colArr[$row_id][$colName] = null;
      //  $this->saveRowOnUpdate($row_id , $colName);
    }

    //create Default value By row_id or by form

    public function createNewFormRow($row_id , $frName = null , $withReturnValue = null )
    {

     

        $this->rows = $this->rows + 1;
        $row = $this->rows;

        if ($frName !== null) {
            $opts = $this->formOpts[$frName];
            $this->rowOpts[$row] = [$frName, "none"]; //?

        } else {
            $this->rowOpts[$row][0] = $opKey = $this->rowOpts[$row_id][0]; //?
            $this->rowOpts[$row][1] = "none";
            $opts = $this->formOpts[$opKey];
        }

        // mht we get option to get default values
        // dd($this->rowOpts);

        $table = $opts["tbName"];
       
        // Hook One
        if(function_exists("Get_Modified_Options"))
        $opts = Get_Modified_Options($opts , $row_id);

       // dd($opts["cols"]);
       



        foreach ($opts["cols"] as $col) {            

        
            if (isset($opts[$col]["action"]) && $opts[$col]["action"] == "generateDefaultRefOnNew") {
                $this->colArr[$row][$col] = $this->getRandomStr(10);
            }  

            if (isset($opts[$col]["defaultVal"])) {
                $this->colArr[$row][$col] = $opts[$col]["defaultVal"];
            }

           
            if (isset($opts[$col]["action"]) && $opts[$col]["action"] == 'getMainRef') {

                //  dd($col);
                $p1 = $opts[$col]["param1"];
                $p2 = $opts[$col]["param2"];
                $ref = $this->refs[$p1][$p2];
                $this->colArr[$row][$col] = $ref;

            }
            
            if (!isset($this->colArr[$row][$col])) {
                $this->colArr[$row][$col] = null;
            }

            // if ($frName=="entries" &&  $col =="bill_num") {
            //     $this->colArr[$row][$col] = null;
            // }

        }

         // dd($this->colArr[$row]);
         // dd(empty($withReturnValue));

          

           try {

        

            $this->msgs[] = "تم إضافة تتبع جديد";                    
            
            $id = DB::table($table)->insertGetId($this->colArr[$row]);

            $this->colArr[$row][$opts["pk"]] = $id;

                 //insert new row content in crudtrackers table.
                 DB::table("crudtrackers")->insert([
                    "id_value" => $id,
                    "tableName" => $table,
                    "primkey" => $opts["pk"],           
                    "new_values" => json_encode($this->colArr[$row]),
                    "user_id" => 1,//Auth::user()->id,
                    "created_at" => date("Y-m-d H:i:s"),
                    "op_type" => "insert",
                ]);

            $this->msgs[] = "تم اضافة السجل من القيم الافتراضية بنجاح";

            $this->onNewRow($row, $id);

            if(empty($withReturnValue)){

                $this->formsLoop();
                
            }else{
                //dd($id);
                return $id;
            }         

        } catch (\Exception $ex) {
            $this->msgs[] = "Error  .." . $ex->getMessage() . "___" . $ex->getLine();
        }

    }

    public function onNewRow($row_id, $id)
    {

        // dd($row_id , $id);
        Log::channel('mht')->info("start of onNewRow ");


        $formName = $this->rowOpts[$row_id][0];
        $opts = $this->formOpts[$formName];

        // انشاء قيد جديد عند انشاء فاتورة جديدة
        if ($formName == "Create New Invoice Header") {

            $ref = $this->colArr[$row_id]['ref'];
            if (!DB::table("entryheads")->where("entry_no", $ref)->exists()) {
                DB::table("entryheads")->insert([
                    "entry_no" => $ref,
                    "decription" => "Automatic Entry",
                    "status" => 1,
                ]);
            }

            $this->msgs[] = "تم عمل رأس قيد موافق للمنتج";
            Log::info("تم عمل رأس قيد موافق للمنتج");
        }

       
    }
    // function createNewEntryHeader(){

    // }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('hideTopDiv', []);
    }
    //autocomplete
    public function setSelectValue($row_id, $colName, $k, $v)
    {

        //  dd($row_id , $colName , $k, $v);

        // $k id of project

        $formName = $this->rowOpts[$row_id][0];

       

        if ($colName == "product_id") {
            $proDetails = DB::table("simpleproducts")->where("id", $k)->first();
            $this->colArr[$row_id]["price"] = $proDetails->buyprice;
            $this->colArr[$row_id]["quantity"] = 1;
            $this->calculateOtherField($row_id, $colName);
           
            //  dd(  $proDetails->buyprice );
        }

        // dd($row_id ,$colName , $k, $v);

        $this->colArr[$row_id][$colName] = $k;
        $this->colArrPar[$row_id][$colName] = $v;
        $this->autoArr[$row_id][$colName] = null;

 //   dd($this->colArr[$row_id]);

        if ($formName == 'Create New Invoice Body') {           
            $this->moveBillItemToEntries ($row_id, $colName);              
        }


        $this->saveRowOnUpdate($row_id, $colName);

        //   dd( $this->colArr[$row_id]);

    }

    public function setGlobalVar($code, $row_id, $name, $colName)
    {

        // dd($code,  $row_id, $name, $colName);
        $this->colArr[$row_id][$colName] = $code;
        $this->colArrPar[$row_id][$colName] = $name;

        $this->saveRowOnUpdate($row_id, $colName);

    }

    // شجرة تصنيفات مجال معين
    public function getClassify($row_id, $colName)
    {

        $opKey = $this->rowOpts[$row_id][0];

        $opts = $this->formOpts[$opKey];

        $lkupArr = explode("|", $opts[$colName]["lookup"]);
        //   $tbName=$this->opts[$colName]["lookup"];
        $tree = new Tree();
        $this->autoArr[$row_id][$colName] = $tree->createSimpleTree($lkupArr[0], $colName, $row_id);

        // dd( $this->classify[$colName]);
    }

    public function autoComplete($row_id, $colName)
    {

        $this->stopAuto = false;

        $opKey = $this->rowOpts[$row_id][0];

        $opts = $this->formOpts[$opKey];

        if (!empty($this->colArrPar[$row_id][$colName])) {

            //  dd($this->colArrPar);

            $v = $this->colArrPar[$row_id][$colName];

            $lkupArr = explode("|", $opts[$colName]["lookup"]);

            // MHT : from now  $lkupArr[0]=table Name and  $lkupArr[1]=id and rest is other column

            // dd(count($lkupArr));

            $strAuto = "<ul>";

            if (count($lkupArr) == 5) {
                $res = DB::table($lkupArr[0])
                    ->select([$lkupArr[1], $lkupArr[2]])
                    ->where($lkupArr[2], "like", "%" . $v . "%")
                    ->orWhere($lkupArr[1], "like", "%" . $v . "%")
                    ->where($lkupArr[3], $lkupArr[4])
                    ->get();
            } else {

                if ($opts[$colName]["colType"] == "list") {
                    $res = DB::table($lkupArr[0])
                        ->select([$lkupArr[1], $lkupArr[2]])
                        ->get();
                } else {
                    $res = DB::table($lkupArr[0])
                        ->select([$lkupArr[2], $lkupArr[1]])
                        ->where($lkupArr[2], "like", "%" . $v . "%")
                        ->orWhere($lkupArr[1], "like", "%" . $v . "%")
                        ->get();
                }

            }

            foreach ($res as $key => $value) {

                $k1 = $value->{$lkupArr[1]}; // always id
                $v2 = $value->{$lkupArr[2]}; // always name

                $strAuto .= "<li style='cursor:pointer' wire:click='setSelectValue({$row_id} ,\"{$colName}\",\"{$k1}\",\"{$v2}\")' >{$v2}</li>";
            }

            if (strlen($strAuto) > 4) {

                $this->autoArr[$row_id][$colName] = $strAuto . "</ul>";

                //  dd($this->autoArr);
            } else {
                $this->autoArr[$row_id][$colName] = null;
            }

        } else {
            $this->autoArr[$row_id][$colName] = null;
        }

        $this->formsLoop();

    }

    public function getLookUpValues($opts, $colName, $id)
    {
        //mht: 0 : table Name -- 1:id  --2: name of lookup

        // if(empty($id)) return null;

        // dd($opts, $colName, $id);

        $lkupArr = explode("|", $opts[$colName]["lookup"]);

        // dd( $lkupArr);

        if (count($lkupArr) > 2) {
            $res = DB::table($lkupArr[0])
                ->select($lkupArr[2])
                ->where($lkupArr[1], $id)
                ->first();

            // if($colName == 'supplier')
            // dd($res->{$lkupArr[2]});

            if ($res) {
                return $res->{$lkupArr[2]};
            } else {
                return null;
            }

        } else {
            $this->msgs[] = "No Lookup Option exist for " . $colName;
        }

    }

    public function compOpen($colName)
    {
        if (!empty($this->colArr[$colName])) {

            // this value autoArr for every feild and every field has value
            $this->autoArr[$colName] = "open";
        }
    }

    public function closeAuto($colName)
    {
        $this->autoArr[$colName] = null;
        $this->formsLoop();

        //  $this->classify[$colName] = null;
    }

    public function delRow($row_id)
    {

        $opKey = $this->rowOpts[$row_id][0];

        $opts = $this->formOpts[$opKey];

        $table = $opts["tbName"];

        $pk = $opts["pk"];

        $id = $this->colArr[$row_id][$pk];

        $fn = $opts["fn"];

        $page = str_replace(" ", "_", $fn);

        try {

            DB::table($table)->where($pk, $id)->delete();
            $this->msgs[] = "تم حذف السجل بنجاح";

            $this->formsLoop();

        } catch (\Exception $ex) {
            $this->msgs[] = "Error  .." . $ex->getMessage() . "___" . $ex->getLine();
        }

    }
    public function render()
    {
        return view('livewire.float-option'  , ['bs_arrows' => $this->arrows]);
    }
}
