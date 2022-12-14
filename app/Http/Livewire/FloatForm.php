<?php
namespace App\Http\Livewire;
use DB;
use stdClass;
use App\MyClass\Tree;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\MyClass\carFileExtention;
use Illuminate\Support\Facades\Log;

class FloatForm extends Component
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

    public $fOpts = []; 

    public $header_id;

    public $rowOpts = []; // array links  row_id with form name and typeof forme single or multiple // not reset // when add row must montion releted form

    public $refs = [];

    public $forms = [];

    public $hideFrame = [];

    public $msgs = [];

    public $stopAuto = true;

    protected $arrows = [];

    public $preventNewRowIfRowsZero = "false";

    public $searchCar;

    public $colArrTitle;

    public $photos=[];
    public $photosDesc=[];

    public $floatDiv;

    public $currentFn;
    public $currentCol;
    public $compParam1;
    public $compParam2;

    public function mount($fn)
    { 
       // dd($fn);
          $this->forms=[$fn];
          $this->init();
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

       // dd($this->formOpts);
        $this->formsLoop();
    }


    public function formsLoop()
    {
        Log::channel('mht')->info("start of formsLoop .. ");

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
            $page = str_replace(" ", "_", $fn);

            // dd($opts);

            //entryType many rows share with refrence with ont head table
            // point of start of form   

            // ?????? ???? ???????? ???????? ?????????? ???? ?????????? ???????? ?????????? ?????????? ???? ?????????? ?????? ?????????? ???? ???? ?????????? 
            //1//
            if ($action == 'getParentFormSource') {

                // display one row every time

                $page = str_replace(" ", "_", $fn);
               // dd($page , $fn);
        
               if($opts[$pk]["param2"]== null) dd("You Must Set PerPage number to  :" . $fn); 

           

                if($fn == 'carFileLinkDriver' &&  !empty($this->searchCar)){  
                  
                    $this->arrows[$page] = DB::table($table)->where("plateNumEng" ,"like" , "%".$this->searchCar."%")->paginate($opts[$pk]["param2"], ['*'], $page);
  
                    if($this->arrows[$page]->total()==0){
                        $this->msgs[]= "Your Search Is Empty , No Data Found..";
                        $this->arrows[$page] = DB::table($table)->orderBy($pk, "desc")->paginate($opts[$pk]["param2"], ['*'], $page);
                      }

                 }else{
                    $this->arrows[$page] = DB::table($table)->orderBy($pk, "desc")->paginate($opts[$pk]["param2"], ['*'], $page);
                 }
                 
              
                //  dd ($this->arrows[$page]);
                // if no items after delete
                // if (count($this->arrows[$page]->items()) == 0 && $this->arrows[$page]->total() > 0) {
                //     $this->previousPage($page);
                // }

                if ($this->arrows[$page]->total() == 0) {
                   // ?????? ?????? ???????????? ?????????? ???????????? ?????? ???? ?????????????? ????????????????????
                    $arr =["crudtrackers"];

                    if(!in_array($fn,$arr))
                    $this->createNewFormRow(null, $fn);

                } else {

                    $this->fillMultipleRow($opts, $this->arrows[$page]);

                 //   dd($this->refs);

                }
            }
             // get Parent From Refrence how defined in setParentRrefrence in column type refrence
             // ???????? ???? ???????? ???????? ?????? ???????? ?????????? ?????????? ???? ???????? ??????
             //2//
            if ($action == 'getParentRefrence') {               
               
                 
                if(!isset($this->refs[$opts[$pk]["param1"]][$opts[$pk]["param2"]]))
                    $this->msgs[] ="no parent refrence exist to form :" .$fn;
                 else{
                     
                   
                    $refVal = $this->refs[$opts[$pk]["param1"]][$opts[$pk]["param2"]];
                    $row = DB::table($opts[$pk]["param3"])->where($opts[$pk]["param4"], $refVal)->get();

                    //dd($refVal);

                       if ($row->Count() > 0) {

                            $this->fillMultipleRow($opts, $row);

                        }else{

                            $arr = ["p_entries", "suppliers" ,"carfiles"]; // not generate default row if empty
                            //  Log::channel('mht')->info("default New " . $fn);
                            if (!in_array($fn, $arr)) {
                            // generate if not have data rows //  ex: entries must have one row for erery entry head
                            // entries  Always related  to parent its table child not  independent
                                    $this->createNewFormRow(null, $fn);
                            }else{
                                $this->msgs[] = "???????????? ???????? ?????????? ???????? :" . $opts[$pk]["param1"]."/".$opts[$pk]["param2"];
                            }

                        }
                    }
                }

                // if($action == null){

                //     $main = [];

                //     $object = new stdClass();

                //     $this->msgs[]="no action defined for form :" . $fn;
    
                //     $this->rows = $this->rows + 1;

                  
                //         foreach ($opts['cols'] as $col => $value)
                //         {
                //             $object->$value  = null;
                //         }

                //         $main[] = $object;

                //      // dd($main , $opts);
    
                //     $this->fillMultipleRow($opts, $main, "group");
                //  }
          

             if (isset($opts["sourceQuery"]) && $opts["sourceQuery"] !==""){    

                $argArr=explode("|" , $opts["sourceQuery"]);

                if(count($argArr)==3){

                    $this->arrows[$page]=  DB::table($table)->where($argArr[1],$argArr[2])->orderBy($pk, "desc")->paginate($argArr[0], ['*'], $page);
                    if ($this->arrows[$page]->total() > 0) { 
                      $this->fillMultipleRow($opts, $this->arrows[$page]);
                    }

                }
                if(count($argArr)==1){

                $this->arrows[$page]=  DB::table($table)->orderBy($pk, "desc")->paginate($argArr[0], ['*'], $page);
                 if ($this->arrows[$page]->total() > 0) { 
                   $this->fillMultipleRow($opts, $this->arrows[$page]);
                 }
               }
           
           }
           
           if(isset($opts["sourceQuery"]) && $opts["sourceQuery"]==""){

                $main = [];

                $object = new stdClass();

                $this->msgs[]="no action defined for form :" . $fn;

                $this->rows = $this->rows + 1;

              
                    foreach ($opts['cols'] as $col => $value)
                    {
                        $object->$value  = null;
                    }
                 

                    $main[] = $object;

                    $this->formOpts[$fn][$pk]["form_translate"] .= "(Not Yet data inserted)";
                   
                   // dd($opts[$pk]["form_translate"]);

                 // dd($main , $opts);

                $this->fillMultipleRow($opts, $main);
             }
                 
            }
             // dd($this->refs);

    }

    public function fillMultipleRow($opts, $dataRows, $group = null)
    {
        //  Mht :  One row is  special state from multi row
        //  in same time we  deal with one row
        //  ???????????? ???????? ???????? ???????? ???? ??????????
        //  ?????? ???? ???????? ???? ?????????????? ???????? ????????????????
        //  ???? ?????? ???????? ???????? ???? ???????????? ?????????? ?????? ????????
        //  ?????????? ???????????? ???????????? ???????? ???? ???????? ????????

        $row = $this->rows;
        //$fvis = $opts["vis"];

        $type = "";
        $rowscount = count($dataRows);
        $ic = 0;
        $fn = $opts['fn'];


         // define if row infirst col for open new div

        foreach ($dataRows as $r => $rw) {

         // $rw row of database

            $row += 1;

           // if ($group == "group") {

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
         //   }

        // if($fn =="carfiles")  
        // dd($opts);

            $this->rowOpts[$row] = [$opts["fn"] , $type];

         //   if($fn)

          //  Log::channel('mht')->info( "formName :" .$fn);

            foreach ($opts["cols"] as $col) {

                //Log::channel('mht')->info( "inputType :{$fn}|" .$col."|");

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

                if( $opts["fn"] == 'Create New Invoice Body' && $col == 'product_id'){
                    
                   // dd($rw->$col);
                    $this->colArrTitle[$row][$col]="?????????? ?????????????? ???? ???????????? (" .$this->calculateProductCount($rw->$col) .')';
                      
                }

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


     public function openForm($form){
         DB::table("myprojects")->where("proForm" ,$form)->update(["isClose" =>1]);
         $this->init();
     }

     public function closeForm($form){
        DB::table("myprojects")->where("proForm" ,$form)->update(["isClose" =>0]);
        $this->init();
    }
     
    public function currrentColumnOptions($fn , $col){   

        $this->compParam1 = $fn ;
        $this->compParam2 = $col;
        $this->floatDiv ="float-option";      

    }

      public function refreshcomp(){
        $this->init();
    }


     public function showFloatComponent($ComponentName){

        $this->floatDiv = $ComponentName;

     }

     public function hideFloatComponent(){

        $this->floatDiv = null;
        $this->init();

     }


    public function showGlobalVar()
    {
       
      // $cc= explode("\\",get_class($this));
      
     //  dd(end($cc));

      // $this->setPage(1 , 'entryheads');

      dd($this);
    } 
  
    public function updatedPhotos (){

       // dd($this->photos);
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

                $this->msgs["entries"] = "?????????? ????????????";
            } else {
                $this->msgs["entries"] = "?????????? ?????? ????????????";
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

        $oldData = (array) DB::table($table)->where($pk, $id)->first();

        $newData = $this->colArr[$row_id];

        $diff_old = array_diff_assoc($oldData, $newData);
        $diff_new = array_diff_assoc($newData, $oldData);

        

     

        try {

            $result = DB::table($table)->where($pk, $id)->update($this->colArr[$row_id]);
            
            if(count($diff_old)>0){
                $this->msgs[$formName] = "???? ?????????? ???????? ????????";
            
                  DB::table("crudtrackers")->insert([
                    "tableName" => $table,
                    "primkey" => $pk,  
                    "id_value" => $id,       
                    "old_values" => json_encode($diff_old , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    "new_values" => json_encode($diff_new , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    "user_id" => 1,//Auth::user()->id,
                    "created_at" => date("Y-m-d H:i:s"),
                    "op_type" => "update",
             ]); 
            }
            $this->msgs[] = "???? ?????????? ?????????? ??????????";
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

        // createNewFormRow by row id // by formName
        // same Option  clone options
        // get opts by $row id ;
        // get opts by form name
        //  dd($row_id , $frName);
        // $this->refs=null;
        // dd($this->rows);
    

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
            
            if (isset($opts[$col]["defaultVal"])) {
                $this->colArr[$row][$col] = $opts[$col]["defaultVal"];
            }        
            if (isset($opts[$col]["param1"]) && $opts[$col]["param1"] == "generateDefaultRefOnNew") {
                $this->colArr[$row][$col] = $this->getRandomStr(10);
            }  

            //get Current Refrence         
            if (isset($opts[$col]["action"]) && $opts[$col]["action"] == 'getMainRef') {
                $p1 = $opts[$col]["param1"];
                $p2 = $opts[$col]["param2"];
                $ref = $this->refs[$p1][$p2];
                $this->colArr[$row][$col] = $ref;
            }
            
            if (!isset($this->colArr[$row][$col])) {
                $this->colArr[$row][$col] = null;
            }

        }

         // dd($this->colArr[$row]);
         // dd(empty($withReturnValue));

          

           try {

        

            $this->msgs[] = "???? ?????????? ???????? ????????";                    
            
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

            $this->msgs[] = "???? ?????????? ?????????? ???? ?????????? ???????????????????? ??????????";

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
      //  Log::channel('mht')->info("start of onNewRow ");


        $formName = $this->rowOpts[$row_id][0];
        $opts = $this->formOpts[$formName];

        // ?????????? ?????? ???????? ?????? ?????????? ???????????? ??????????
        if ($formName == "Create New Invoice Header") {

            $ref = $this->colArr[$row_id]['ref'];
            if (!DB::table("entryheads")->where("entry_no", $ref)->exists()) {
                DB::table("entryheads")->insert([
                    "entry_no" => $ref,
                    "decription" => "Automatic Entry",
                    "status" => 1,
                ]);
            }

            $this->msgs[] = "???? ?????? ?????? ?????? ?????????? ????????????";
           // Log::info("???? ?????? ?????? ?????? ?????????? ????????????");
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

    // ???????? ?????????????? ???????? ????????
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
    
    public function excuteAfterWidget($row_id ,$fn , $colName){


        if($fn=="simpleproducts_in"){
          //  ddd($row_id, $fn);
           return $this->buildImageWidget($row_id , $fn , $colName);
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
            $this->msgs[] = "???? ?????? ?????????? ??????????";

            $this->formsLoop();

        } catch (\Exception $ex) {
            $this->msgs[] = "Error  .." . $ex->getMessage() . "___" . $ex->getLine();
        }

    }
    public function render()
    {
        return view('livewire.float-form'  , ['bs_arrows' => $this->arrows]);
    }
}
