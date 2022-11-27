<?php

namespace App\Http\Livewire;
use DB;
use Livewire\Component;
use App\MyClass\invoiceExtend;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;


class AppsBuilder extends Component
{   

    use invoiceExtend;
    use WithPagination;

    protected $listeners = ["setDate" => "setDate"];
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

    // array of forms
    // ['paidtypes', 'productstate', 'trialbalances', 'entryheads', 'entries', 'Create New Invoice Header', 'Create New Invoice Body']

    public function mount($formAll = ['Create New Invoice Header', 'suppliers' ,'Create New Invoice Body', 'operations', 'entryheads', 'entries', 'trialbalances', 'Create New Product'])
    {

        $this->forms = $formAll;

        // fill All Form Optios

        foreach ($this->forms as $form) {

            $this->formOpts[$form] = $this->geOptionsOfForm($form);
            $this->formOpts[$form]["cols"] = $this->sortColumns($this->formOpts[$form]);
            $this->formOpts[$form]["fn"] = reset($this->formOpts[$form])["formName"];
            $this->formOpts[$form]["tbName"] = reset($this->formOpts[$form])["tableName"];
            $this->formOpts[$form]["pk"] = reset($this->formOpts[$form])["autoIncreament"];
            $this->formOpts[$form]["bf"] = reset($this->formOpts[$form])["formBootstrap"];
            $this->formOpts[$form]["vis"] = reset($this->formOpts[$form])["formTitleVis"];
            $attr = reset($this->formOpts[$form])["formAttrs"];

            $attrs = json_decode($attr, true);

            if (!empty($attrs)) {

                foreach ($attrs as $key => $value) {
                    $this->formOpts[$form][$key] = $value;
                }

            }

        }

        // dd($this->formOpts);
        // dd($this->refs);

        $this->formsLoop();
    }

    public function showGlobalVar()
    {
       $this->setPage(1 , 'entryheads');
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

            // dd($opts);

            //entryType many rows share with refrence with ont head table
            // point of start of form   

            // اما ان يكون مصدر الفور هو كويري وغير متعلق بجدول اب ويكون اما لوحده او اب لجدول 
            //1//
            if ($action == 'getParentFormSource') {

                // display one row every time

                $page = str_replace(" ", "_", $fn);
               // dd($page , $fn);
        
               if($opts[$pk]["param2"]== null) dd("You Must Set PerPage number to  :" . $fn); 

                if($fn == 'Create New Invoice Header' &&  !empty($this->searching)){  
                  
                    $this->arrows[$page] = DB::table($table)->where("bill_id" , $this->searching)->paginate($opts[$pk]["param2"], ['*'], $page);
  
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
                   // اذا كان الجدول فارغا فينشىء سطر من الالقيم الافتراضية
                    $this->createNewFormRow(null, $fn);

                } else {

                    $this->fillMultipleRow($opts, $this->arrows[$page], "group");

                 //   dd($this->refs);

                }
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

                        $arr = ["entries"]; // not generate default row if empty
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

            $this->rowOpts[$row] = [$opts["fn"], $type];

            foreach ($opts["cols"] as $col) {

                if ($opts[$col]['inputType'] == 'auto') {

                    if ($this->stopAuto == true) {
                        $this->colArrPar[$row][$col] = $this->getLookUpValues($opts, $col, $rw->$col);
                    }                  

                }                
                if ($opts[$col]['inputType'] == 'classify') {

                    $this->colArrPar[$row][$col] = $this->getLookUpValues($opts, $col, $rw->$col);
                  

                }                
                if ($opts[$col]["action"] == "setParentRrefrence") {
                    $parentForm = $opts[$col]["param1"];
                    $parentCol = $opts[$col]["param2"];
                    $this->refs[$parentForm][$parentCol] = $rw->$col;
                 
                } 

                if( $opts["fn"] == 'Create New Invoice Body' && $col == 'product_id'){
                    
                   // dd($rw->$col);
                    $this->colArrTitle[$row][$col]="العدد الموجود في المخزن (" .$this->calculateProductCount($rw->$col) .')';
                      
                }

                  $this->colArr[$row][$col] = $rw->$col;           

            }

        }

        $this->rows = $row;

        //  dd( $this->refs);
        // if($this->rows == 6)
        // dd($this->colArr);
    }

    public function updatedSearching (){
        $this->formsLoop();
    }

    public function updatedPaginators()
    {
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

    public function saveRowOnUpdate($row_id, $col)
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

            DB::table($table)->where($pk, $id)->update($this->colArr[$row_id]);
            $this->msgs[] = "تم تعديل السجل بنجاح";
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
        $this->saveRowOnUpdate("{$row_id}.{$colName}");
    }

    //create Default value By row_id or by form

    public function createNewFormRow($row_id, $frName = null , $overCol=null , $overVal=null )
    {

        // createNewFormRow by row id // by formName
        // same Option  clone options
        // get opts by $row id ;
        // get opts by form name
        //  dd($row_id , $frName);
        // $this->refs=null;
        // dd($this->rows);
    
        // if(!is_null($overArr)){
        //    $overCol = $overArr[0];
        //    $overVal = $overArr[1];
        // }
    

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

        foreach ($opts["cols"] as $col) {            

            if ($opts[$col]["action"] == "setParentRrefrence") {
                if ($opts[$col]['param4'] == "generate") {
                    $this->colArr[$row][$col] = $this->getRandomStr(10);
                }
            }

            if (isset($opts[$col]["defaultVal"])) {
                $this->colArr[$row][$col] = $opts[$col]["defaultVal"];
            }

            if ( !is_null($overCol) &&  $overCol == $col ) {
                $this->colArr[$row][$col] = $overVal ;
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

        //  dd($this->colArr[$row]);

           try {

            $id = DB::table($table)->insertGetId($this->colArr[$row]);

            $this->msgs[] = "تم اضافة السجل بنجاح";

            $this->onNewRow($row, $id);

            $this->formsLoop();

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

    //-------------------------------------------------------

    // public function validateHeader()
    // {

    //     $this->validateState = 0;

    //     $valArr = [];

    //     foreach ($this->hOpts["cols"] as $col) {
    //         if ($this->hOpts[$col]["validation"]) {
    //             $valArr["colArr.0." . $col] = $this->hOpts[$col]["validation"];
    //         }
    //     }
    //     // dd($valArr , $this->colArr);

    //     if (count($valArr) > 0) {
    //         $this->validate($valArr);
    //     }

    //     $this->validateBody();

    // }

    // public function validateBody()
    // {

    //     $valArr = [];

    //     foreach ($this->colArr as $k => $v) {

    //         if ($k > 0) {
    //             foreach ($this->bOpts["cols"] as $col) {
    //                 if ($this->bOpts[$col]["validation"]) {
    //                     $valArr["colArr." . $k . "." . $col] = $this->bOpts[$col]["validation"];
    //                 }
    //             }
    //         }

    //     }

    //     if (count($valArr) > 0) {
    //         $this->validate($valArr);
    //     }

    //     //  $this->msgs[]="validation Complete Ok";
    //     $this->validateState = 1;

    // }

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
        Log::channel('mht')->info("Loading view");
        return view('livewire.apps-builder' , ['bs_arrows' => $this->arrows]);
    }
}
