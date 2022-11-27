<?php
namespace App\Http\Livewire;

use App\MyClass\Tree;
use DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Schema;

trait toolsExtends
{

    public $msgs = [];

    //ex ; $event_str="focus|onfucusdo|p1,p2 blur|onblurdo|p1,p2 input|oninputdo|p1,p2";
   public function buildEvents($eventStr)
    {

        if (empty($eventStr)) {
            return '';
        }

        $parsingString = "";
        $eventsArr = explode(" ", $eventStr);
        foreach ($eventsArr as $evArr) {
            $parStr = "";
            $eAr = [];
            $eAr = explode("|", $evArr);
            $parAr = explode(",", $eAr[2]);

            foreach ($parAr as $pr) {
                // $par = $this->{$pr};
                $par = $pr;
                $parStr .= is_numeric($par) ? "{$par}," : "'{$par}',";
            }

            $parsingString .= " wire:" . $eAr[0] . "=" . $eAr[1] . "(" . rtrim($parStr, ",") . ")";
        }
        return $parsingString;
    }

    public function getRandomStr($len)
    {
        return bin2hex(random_bytes($len));
    }

    // shared functions;
    public function colOptionByColName()
    {
        //  form MustBe Unique
        $rows = DB::table("coloptions")->where("formName", $this->formName)->get();
        foreach ($rows as $k => $row) {
            foreach ($row as $kn => $col) {
                $this->opts[$row->colName] = (array) $row;
            }
        }
    }

    public function getAutoIncreamentName($tb_name)
    {
        $result = DB::select(DB::raw("SHOW KEYS FROM {$tb_name} WHERE Key_name = 'PRIMARY'"));
        return $result[0]->Column_name;
    }

}

class SingleForm extends Component
{

    use toolsExtends;
    use WithFileUploads;

    protected $listeners = ['refreshcomp' => 'mount',"setDate" => "setDate", "validateForm" => "validateForm", "insertRow" => "insertRow", "resetHeaderForm" => "resetHeaderForm"];

    //prameters
   
    public $formName;
    public $formTranslate;
    public $formNameSwip;
    public $validateState;

    //public $UpdateId = 21;
    public $table;
    public $opts;
    public $columns;
    public $autoKey;
    public $formHtml;
    public $colArr = [];
    public $colArrPar = [];
    public $formType;
    public $autoArr = [];
    public $classify = [];
    public $photos = [];
    public $onePhoto =[];
    public $images = [];
    public $uploadImgs;
    public $photos_desc = [];
    public $ref;
    public $submit_visible;
    public $OnePhoroColName;
  //  public $p1 = "p1"; //test
 //   public $p2 = 555; //test

    public function mount($formName = null , $ref = null, $submit_visible = null)
    {

        if (isset($ref)) {
            $this->ref = $ref;
        }

        if (isset($formName)) {
            $this->formName = $formName;
        }else{
            $this->formName  = $this->table =  session("form_name") ? session("form_name") : "Create New Order"; 
        }

        //dd(session("form_name"));

        $this->submit_visible = $submit_visible;

        $this->init();

        

        // dd( $this->formName);

    }   

   

    public function getOptionsofCol($action , $colName , $relatedcol=null)
    {

     //   return "<option>$action</option>";

       $str= "<option  style='color:red'>Select Option</option>";;
       $res=null;
       $sql = $this->opts[$colName]["lookup"];          
       if(strlen($sql)>1){
          

            
        // $res = DB::table($lkupArr[0])->select([$lkupArr[1]])->groupBy($lkupArr[1])->get();
        if($action == 'raw'){
            $res = DB::select(DB::raw($sql));
        }

        if($action == 'related'){
          //  dd($this->colArr);
          

        if(!empty($this->colArr[$relatedcol])){

            $sql = str_replace ("?" , $this->colArr[$relatedcol] , $sql);
           // return "<option>{$sql}</option>";
            $res = DB::select(DB::raw($sql));
           }
        }
        

       // dd($res);
       if($res){
         foreach ($res as $k =>$val){
             $arr = (array)$val;
             $keyName = array_key_first($arr);            
             $str .= "<option>{$val->$keyName}</option>";
         }
          return $str;
        }else{
        return "<option>Select related Firstly </option>";
        }    
        
       }  
    }

     public function hydrate(){
        $this->dispatchBrowserEvent('hideTopDiv', []);
    }

    public function updatedFormNameSwip($val)
    {

        $resArr = explode("/", $val);

        // dd(ord($resArr[0][0]));

        $str = str_replace("\u{00a0}", '', $resArr[0]);

        $resqu = DB::table('coloptions')->where("formName", $str)->get();

        //  dd($resqu);

        if ($resqu->count() > 0) {
            $this->formName = $str;            
            $this->init();
        } else {

            $this->msgs[] = "يجب اختيار فورم";
        }

    }

    public function init()
    {
       
        $this->opts = [];
        $this->colOptionByColName();      
        $this->table = (reset($this->opts))["tableName"];
        $this->columns = Schema::getColumnListing($this->table);
        $this->autoKey = $this->getAutoIncreamentName($this->table);
        $this->formType = $this->opts[$this->autoKey]["formType"];
        $this->formTranslate =$this->opts[$this->autoKey]["form_translate"];
        $this->validateState = null;
        $this->colArr = [];
        $this->colArrPar = [];
        $this->msgs[] = "Start init function in single Form Component,form type :". $this->formType;
        $this->UpdateId=null;
        $this->resetValidation();


     //   dd( $this->colArr);
        if ($this->formType == 1) {
            $this->fillForm();
        } else {
            $this->setDefaultValue();
        }

       
    }

    // public function updatedOnePhoto($colName){  
    // } 

    public function updatedColArr($colName)
    {
        //dd($colName);
        $this->validateForm();       
    }

    public function deletePic($id, $colName)
    {

        DB::table("up_images")->where("id", $id)->delete();

        $this->getAttachImages($colName);

        $this->msgs[] = "تم حدف السجل بنجاح";
    }

    public function storeImages($colName, $lookup)
    {

        //  dd( $this->photos_desc ,  $this->photos) ;

        $valArr["colArr." . $colName] = "required";
        $this->validate($valArr);

        $valArr1["photos_desc.*"] = "required";
        $this->validate($valArr1);

        $lkup = explode("|", $lookup);

        // $str= Str::random(15);
        if(count($this->onePhoto)>0){

        }

        if (count($this->photos) > 0) {

            $ev_id = intval($this->colArr[$colName]);

            if (intval($ev_id)) {

                foreach ($this->photos as $key => $photo) {

                    $valArr1["photos_desc." . $key] = "required";
                    $this->validate($valArr1);

                    $filename = $photo->store($this->table, "global_images");

                    DB::table($lkup[0])->insert([
                        "event_id" => $ev_id,
                        "path" => $this->table,
                        "file_name" => $filename,
                        "description" => $this->photos_desc[$key],
                        "timestamps" => date("Y-m-d H:i:s"),
                    ]);

                    unset($this->photos[$key]);
                    unset($this->photos_desc[$key]);

                    //  $this->getPhotos($lookup , $colName);

                    # code...
                }
                $this->getAttachImages($colName);
            }
        }
    }

    public function getAttachImages($colName)
    {

        // dd($colName);

        if (!empty($this->colArr[$colName])) {

            $fval = $this->colArr[$colName];

            $lkupArr = \explode("|", $this->opts[$colName]["lookup"]);

            $strAuto = "<div style='display:flex; ;width:100%;overflow-y:auto;'>";

            $res = DB::table($lkupArr[0])->where($lkupArr[1], "like", "%" . $fval . "%")->orWhere($lkupArr[2], "like", "%" . $fval . "%")->get();
            // infutur get id of image table
            foreach ($res as $key => $value) {
                $k1 = $value->{$lkupArr[2]};
                $v2 = $value->{$lkupArr[1]};
                $path = config("livewire.storge_img") . $value->file_name;
                $strAuto .= "<div wire:click='deletePic({$value->id},\"{$colName}\")' style='margin:3px 0px ;cursor:pointer;padding:75px 5px;float:right;background-color: #ddd'>X</div><div style='cursor:pointer;margin:3px 0px 3px 5px;border:1px solid #ddd' wire:click='setSelectValue(\"{$colName}\",{$k1},\"{$v2}\")' ><div style='text-align:center' >{$k1}:{$v2}</div><img src='{$path}'  style='width:150px ; height:150px'   ></div>";
            }

            if (strlen($strAuto) > 4) {
                $this->autoArr[$colName] = $strAuto . "</div>";
            } else {
                $this->autoArr = null;
            }

        } else {
            $this->autoArr = null;
        }

    }

    public function setGlobalVar($code, $parent, $name, $colName)
    {
        $this->colArr[$colName] = $code;
        $this->colArrPar[$colName] = $name;
        //   dd( );
    }

    public function getClassify($colName, $id = null)
    {

        $lkupArr = explode("|", $this->opts[$colName]["lookup"]);
        //   $tbName=$this->opts[$colName]["lookup"];
        $tree = new Tree();
        $this->classify[$colName] = $tree->createSimpleTree($lkupArr[0], $colName);

        // dd( $this->classify[$colName]);

    }

    public function closeAuto($colName)
    {
        $this->autoArr[$colName] = null;
        $this->classify[$colName] = null;
    }

    //Customization
    public function cleanAuto($colName)
    {
        $this->colArrPar[$colName] = null;
        $this->colArr[$colName] = null;
    }

    public function closeForm($colName)
    {

        $this->autoArr[$colName] = null;
    }

    public function setDate($colName, $v)
    {
        //dd($colName , $v);
        $this->colArr[$colName] = $v;
        $this->validateForm();

    }

    public function setSelectValue($colName, $k, $v)
    {
        $this->colArrPar[$colName] = $v;
        $this->colArr[$colName] = $k;
        $this->autoArr[$colName] = null;

    }

    public function selectRow($colName, $v)
    {
        $this->colArrPar[$colName] = $v;
        $this->colArr[$colName] = $v;
        $this->autoArr[$colName] = null;

        $this->UpdateId = $v;

        $this->fillForm();

    }

    public function getRowIdFromtable($colName)
    {

        if (!empty($this->colArr[$colName])) {

            $tbName = "";
            $id;
            $select = [];
            $where;

            $val = $this->colArr[$colName];

            $lkupArr = explode("|", $this->opts[$colName]["lookup"]);

        //dd( $lkupArr);

            foreach ($lkupArr as $k => $v) {

                if ($k == 0) {
                    $tbName = $v;
                }

                if ($k == 1) {
                    $id = $v;
                    $select[] = $v;
                }

                if ($k > 1) {
                    $select[] = $v;
                }

            }

            $selectStr = implode(",", $select);

           // dd($selectStr);

            $str = "select {$selectStr} from {$tbName} where CONCAT_ws({$selectStr}) like '%{$val}%' or {$id}='$val'";
            $result = DB::select(DB::raw($str));

           //  dd( $result );

            $strAuto = "<table class='table' ><tr>";

            foreach ($select as $th) {
                $strAuto .= "<th>$th</th>";
            }
            $strAuto .= "</tr>";

            foreach ($result as $row) {
                $strAuto .= "<tr>";
                foreach ($row as $k1 => $v1) {
                    if ($k1 == $id) {
                        $strAuto .= "<th wire:click=\"selectRow('{$colName}' , {$v1} )\" style=\"cursor:pointer\">$v1</th>";
                    } else {
                        $strAuto .= "<th>{$v1}</th>";
                    }

                }
                $strAuto .= "</tr>";
            }
            if (strlen($strAuto) > 4) {
                $this->autoArr=null;
                $this->autoArr[$colName] = $strAuto . "</table>";
            } else {
                $this->autoArr = null;
            }

        } else {
            $this->autoArr = null;
        }

    }

    public function compOpen($colName)
    {
        if (!empty($this->colArr[$colName])) {
            
            // this value autoArr for every feild and every field has value
            $this->autoArr[$colName] = "open";
        }
    }

    public function autoComplete($colName)
    {

      //  dd($colName);

        if (!empty($this->colArrPar[$colName])) {

            $v = $this->colArrPar[$colName];

            

            $lkupArr = explode("|", $this->opts[$colName]["lookup"]);

            $strAuto = "<ul>";

            if ($this->opts[$colName]["colType"] == "list") {
                $res = DB::table($lkupArr[0])
                    ->select([$lkupArr[2], $lkupArr[1]])
                    ->get();
            } else {
                $res = DB::table($lkupArr[0])
                    ->select([$lkupArr[2], $lkupArr[1]])
                    ->where($lkupArr[2], "like", "%" . $v . "%")
                    ->orWhere($lkupArr[1], "like", "%" . $v . "%")
                    ->get();
            }

            foreach ($res as $key => $value) {
                $k1 = $value->{$lkupArr[2]};
                $v2 = $value->{$lkupArr[1]};
                $strAuto .= "<li style='cursor:pointer' wire:click='setSelectValue(\"{$colName}\",\"{$k1}\",\"{$v2}\")' >{$v2}</li>";
            }

            if (strlen($strAuto) > 4) {
                $this->autoArr[$colName] = $strAuto . "</ul>";
            } else {
                $this->autoArr = null;
            }

        } else {
            $this->autoArr = null;
        }

    }

    public function validateForm()
    {
        $this->validateState = 0;
        $this->emit("setHeaderFormData", $this->validateState, $this->table, $this->colArr);
        $valArr = [];

        foreach ($this->columns as $col) {
            if ($this->opts[$col]["validation"]) {
                $valArr["colArr." . $col] = $this->opts[$col]["validation"];
            }

        }

        if (count($valArr) > 0) {
            $this->validate($valArr);
        }

        $this->validateState = 1;
        $this->emit("setHeaderFormData", $this->validateState, $this->table, $this->colArr);
    }

    public function getLookUpValues($colName, $id)
    {
            

        //0 : table Name -- 1: name of lookup   -- 2: id  
        $lkupArr = explode("|", $this->opts[$colName]["lookup"]);

        if (count($lkupArr) > 2) {
            $res = DB::table($lkupArr[0])
                ->select($lkupArr[1])
                ->where($lkupArr[2], $id)
                ->first();

            //  dd($res->{$lkupArr[1]});

            if ($res) {
                return $res->{$lkupArr[1]};
            } else {
                return null;
            }

        } else {
            $this->msgs[] = "No Lookup Option exist for " . $colName;
        }

    }

    public function setDefaultValue()
    {

        foreach ($this->columns as $col) {

            $this->colArr[$col] = $this->opts[$col]["defaultVal"];

            if ($this->opts[$col]["colType"] == "max_id") {
                $logVal = explode("|", $this->opts[$col]["logicalVal"]);
                $max = DB::table($logVal[0])->max($logVal[1]);

                $this->colArr[$col] = $max + 1;
            }

            if ($this->opts[$col]["colType"] == "ref") {

                // $this->ref only for outer refrence

                if (!empty($this->ref)) {
                    $this->colArr[$col] = $this->ref;
                } else {
                    $this->ref = $this->getRandomStr(10);
                    $this->colArr[$col] = $this->ref;
                }

            }
        }
    }

    public function fillForm()
    {

        //dd($this->UpdateId);

     if(empty ($this->UpdateId)){
        $res = DB::table($this->table)->first();
     }else{
        $res = DB::table($this->table)->where($this->autoKey, $this->UpdateId)->first();
     }


        foreach ($res as $k => $v) {

            if ($this->opts[$k]["colType"] == "ref") {
                $this->ref = $v;
            }

            if ($this->opts[$k]["inputType"] == "auto" || $this->opts[$k]["inputType"] == "classify") {
                //  dd( $this->getLookUpValues($k,$v));
                $this->colArrPar[$k] = $this->getLookUpValues($k, $v);
            }
            $this->colArr[$k] = $v;
        }
    }

    public function updateRow()
    {

        $valArr = [];

        foreach ($this->opts as $col => $row) {
            if ($row["validation"]) {
                $valArr["colArr." . $col] = $row["validation"];
            }
         }

        if (count($valArr) > 0) {
            $this->validate($valArr);
        }

        if(count($this->onePhoto)>0)
         {
          foreach($this->onePhoto  as $k =>$photo){
            $this->colArr[$k] = $photo->store($this->table, "global_images"); 
            $this->onePhoto[$k] = null;
         } 
        }

        $id = $this->colArr[$this->autoKey];

        try {
            DB::table($this->table)->where($this->autoKey, $id)->update($this->colArr);
            $this->msgs[] = "تم تحديث السجل بنجاح";
        } catch (\Exception $ex) {
            $this->msgs[] = "Error  .." . $ex->getMessage() . "___" . $ex->getLine();
        }

      //  $this->dispatchBrowserEvent('hideTopDiv', []);

    }

    public function resetHeaderForm($ref = null)
    {
        $this->ref = $ref;
        $this->colArr = [];
        $this->setDefaultValue();
    }

    public function insertRow()
    {
        $this->validateForm();
       

        // foreach ($this->columns as $col) {
        //     if ($this->opts[$col]["validation"]) {
        //         $valArr["colArr." . $col] = $this->opts[$col]["validation"];
        //     }

        // }

       // dd($this->onePhoto , count($this->onePhoto) , $this->colArr );

        if(count($this->onePhoto)>0)
         {
          foreach($this->onePhoto  as $k =>$photo){
            $this->colArr[$k] = $photo->store($this->table, "global_images"); 
            $this->onePhoto[$k] = null;
         } 

       //   dd($this->onePhoto , count($this->onePhoto) , $this->colArr );   
         
        }

        $arr = $this->colArr;
        unset($arr[$this->autoKey]);

        try {
            DB::table($this->table)->insert($arr);

            $this->msgs[] = "تم ادخال السجل بنجاح في الفورم";
            $this->colArr = [];
            $this->colArrPar = [];
            $this->autoArr = [];

            $this->setDefaultValue();

        } catch (\Exception $ex) {
            $this->msgs[] = "Error  .." . $ex->getMessage() . "___" . $ex->getLine();
        }
    }

    public function getActions()
    {

        if (!$this->submit_visible == "hidden") {

            $actionHtml = "<div class='col-12 m-4'>";

            if ($this->formType == 0) {
                $actionHtml .= "<input type='button' wire:click='insertRow' class='btn btn-primary' value='insert Row' > ";
            } else {
                $actionHtml .= "<input type='button' wire:click='updateRow' class='btn btn-success'  value='Update Row'> ";
            }
            return $actionHtml . "</div>";

        } else {
            return null;
        }
    }

    public function render()
    {
        session()->put('form_name', $this->formName);
        return view('livewire.single-form', [
            "formNames" => DB::table("coloptions")->orderBy("formName")->get()->groupBy(['tableName', 'formName']),
        ]);
    }

}
