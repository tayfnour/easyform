<?php

// create component dynamiclly

namespace App\Http\Livewire;

use DB;
use Livewire\Component;
use Schema;

class MultiInlineform extends Component
{

    public $table;
    public $formName = "Bills_in";
    // public $formHeader = "New Header Bill";
    //  public $formName = "Edit Bill";New Header Bill

    public $formHeader = "New Header Bill";

    public $inlineForm = [];
    public $inlineFormPar = [];
    public $rows = -1;
    public $columns = [];
    public $colOptions;
    public $primaryKey;
    public $ref;
    public $formType;
    public $msgs;
    public $strAuto = '';
    public $pickData = [];
    public $autoArr = [];
    public $totalBefore;
    public $totalVat;
    public $total;
    public $headerActive;
    public $colArrs;
    public $formHeaderData;
    public $headerFormValReady;

    protected $listeners = ["setHeaderFormData" => "setHeaderFormData"];

    public function mount($formName = null, $formheader = null, $headerActive = "active")
    {

        if (!empty($formName)) {
            $this->formName = $formName;
        }

        if (!empty($formHeader)) {
            $this->formHeader = $formHeader;
        }

        $this->headerActive = $headerActive;
        $this->colOptionByColName(); // convert  object  to Array
        $this->table = (reset($this->colOptions))['tableName'];
        $result = DB::select(DB::raw("SHOW KEYS FROM {$this->table} WHERE Key_name = 'PRIMARY'"));
        $this->primaryKey = $result[0]->Column_name;
        $this->formType = $this->colOptions[$this->primaryKey]['formType'];
        $this->columns = Schema::getColumnListing($this->table);

        $this->ref = $this->getRandomStr(10);

        if ($this->formType == 0) {
            $this->newRow();
        } else {
            $this->fillToUpdate();
        }

    }

    // function updated ($d){
    //  $this->emit("alerty");
    // }

    public function setHeaderFormData($valStatus, $cols, $data)
    {

        // dd($valStatus);
        $this->headerFormValReady = $valStatus;
        $this->formHeaderData = [$valStatus, $cols, $data];
    }

    public function getRandomStr($len)
    {
        return bin2hex(random_bytes($len));
    }
    // start function customize multi-inline form
    public function resetUnitAuto($row_id, $key, $val, $col)
    {
        $this->inlineFormPar[$row_id][$col] = $val;
        $this->inlineForm[$row_id][$col] = $key;
        $this->autoArr = null;
        $this->calculateTotalBeforeVat($row_id);

    }

    public function autoComplete($row_id)
    {

        $v = $this->inlineFormPar[$row_id]["product_id"];

        if (!empty($v)) {

            $strAuto = "<ul>";
            $res = DB::table("simpleproducts")
                ->select(["id", "name"])
                ->where("name", "like", "%" . $v . "%")
                ->orWhere("id", "like", "%" . $v . "%")
                ->get();

            foreach ($res as $key => $value) {
                $k1 = $value->id;
                $v2 = $value->name;
                $strAuto .= "<li style='cursor:pointer' wire:click='resetUnitAuto({$row_id},\"{$k1}\",\"{$v2}\" ,\"product_id\")' data-id='{$k1}'>{$v2}</li>";
            }

            if (strlen($strAuto) > 4) {
                $this->autoArr[$row_id]["product_id"] = $strAuto . "</ul>";
            } else {
                $this->autoArr = null;
            }

        } else {
            $this->autoArr = null;
        }

    }

    public function pikeFromArr($row_id)
    {
        $this->autoArr = null;
        $lookup = \json_decode($this->colOptions['discountType']['lookup'], true);
        $strAuto = "<ul>";
        foreach ($lookup as $key => $value) {
            $strAuto .= "<li style='cursor:pointer' wire:click='resetUnitAuto({$row_id},\"{$key}\",\"{$value}\",\"discountType\")' data-id='{$key}'>{$value}</li>";
        }

        $this->autoArr[$row_id]["discountType"] = $strAuto . "</ul>";

    }

    public function autoPikeUnit($row_id)
    {
        $this->autoArr = null;
        $lookup = $this->colOptions['unit']['lookup'];

        // dd($lookup);

        $strAuto = "<ul>";
        $arr = explode("|", $lookup);
        $res = DB::table($arr[0])->select([$arr[1], $arr[2]])->get();

        foreach ($res as $key => $value) {
            $k1 = $value->{$arr[1]};
            $v2 = $value->{$arr[2]};
            $strAuto .= "<li style='cursor:pointer' wire:click='resetUnitAuto({$row_id},\"{$k1}\",\"{$v2}\" ,\"unit\")' data-id='{$k1}'>{$v2}</li>";
        }

        $this->autoArr[$row_id]["unit"] = $strAuto . "</ul>";

        // dd($this->autoArr);
    }

    public function calculateTotalBeforeVat($id)
    {
        //dd($this->inlineForm[$id]["quantity"]);
        $totalBefore = $this->inlineForm[$id]["quantity"] * $this->inlineForm[$id]["price"];
        $this->inlineForm[$id]["preTotal"] = $totalBefore;
        $vat = $this->inlineForm[$id]["vat"];

        $discount = $this->inlineForm[$id]["discount"];
        $discountType = $this->inlineForm[$id]["discountType"];
        $discountType = $discountType == null ? 1 : $discountType;
        if ($discount > 0 && $discountType == 1) {
            $totalBefore = $totalBefore - $discount;
        } else {
            $totalBefore = $totalBefore - ($totalBefore * $discount / 100);
        }
        $this->inlineForm[$id]["preTotal"] = $totalBefore;
        $this->inlineForm[$id]["vatVal"] = $totalBefore * ($vat / 100);
        $this->inlineForm[$id]["total"] = $totalBefore + $this->inlineForm[$id]["vatVal"];
        $this->calculateTotal();
    }

    // customize function

    public function hydrate()
    {
        $this->resetValidation();
       // $this->dispatchBrowserEvent('showTopDiv', []);
        $this->emit("alerty");
    }

    public function getLookupsForMultipleValue($res)
    {

        $getvalueArr = [];

        foreach ($res as $k => $row) {
            foreach ($row as $col => $val) {
                if ($this->colOptions[$col]["colType"] == "autoComplete") {

                    $lkupAr = explode("|", $this->colOptions[$col]["lookup"]);
                    $r1 = DB::table($lkupAr[0])->select([$lkupAr[1]])->where($lkupAr[2], $val)->first();

                    // dd($r1 , $val);

                    if ($r1) {
                        $getvalueArr[$col][$val] = $r1->name;
                    }

                }
                if ($this->colOptions[$col]["colType"] == "pickValue") {
                    $lkupAr = explode("|", $this->colOptions[$col]["lookup"]);
                    $r1 = DB::table($lkupAr[0])->select([$lkupAr[2]])->where($lkupAr[1], $val)->first();
                    $getvalueArr[$col][$val] = $r1->name;
                }
                if ($this->colOptions[$col]["colType"] == "pickeFromArr") {
                    $lkupAr = json_decode($this->colOptions[$col]["lookup"], true);
                    $getvalueArr[$col][$val] = $lkupAr[$val];
                }
            }
        }

        return $getvalueArr;
    }

    public function validation($forms)
    {

    }

    public function calculateTotal()
    {

        $this->totalBefore = 0;
        $this->totalVat = 0;
        $this->total = 0;

        foreach ($this->inlineForm as $row) {

            if ($row["preTotal"]) {
                $this->totalBefore += $row["preTotal"];
            }

            if ($row["vatVal"]) {
                $this->totalVat += $row["vatVal"];
            }

            if ($row["total"]) {
                $this->total += $row["total"];
            }

        }
    }

    public function fillToUpdate($ref = "74d70f79ad020903ec4e")
    {

        $this->ref = $ref;

        $res = DB::table('bills')
            ->where("bills.ref", $ref)
            ->get();

        $lookupVals = $this->getLookupsForMultipleValue($res);

        //  dd($lookupVals);
        //   $this->rows = $res->count()-1;

        foreach ($res as $k => $row) {

            foreach ($row as $col => $val) {

                if (isset($lookupVals[$col])) {
                    $this->inlineFormPar[$k][$col] = $lookupVals[$col][$val];
                }

                $this->inlineForm[$k][$col] = $val;

            }
            $this->rows = $k;
        }

        $this->calculateTotal();
    }

    public function newRow()
    {

        if (count($this->inlineForm) == 0) {
            $this->rows = 0;
        } else {
            $this->rows = max(array_keys($this->inlineForm)) + 1;
        }

        foreach ($this->columns as $col) {
            if (isset($this->colOptions[$col]["defaultVal"])) {
                $this->inlineForm[$this->rows][$col] = $this->colOptions[$col]["defaultVal"];
            } else if (!empty($this->colOptions[$col]["colType"]) && $this->colOptions[$col]["colType"] == 'ref') {
                $this->inlineForm[$this->rows][$col] = $this->ref;
            } else {
                $this->inlineForm[$this->rows][$col] = null;
            }

            if (isset($this->colOptions[$col]["colType"]) && $this->colOptions[$col]["colType"] == "autoComplete") {
                $this->inlineFormPar[$this->rows][$col] = null;
            }

            if (isset($this->colOptions[$col]["colType"]) && $this->colOptions[$col]["colType"] == "pickeFromArr") {
                $this->inlineFormPar[$this->rows][$col] = "ر.س";
            }

        }

        $this->msgs = ["تم إضافة سطر جديد !"];

    }

    public function deleteRow($id, $row_id)
    {

        DB::table($this->table)->where($this->primaryKey, $id)->delete();
        $this->removeRow($row_id);
        $this->fillToUpdate();

    }

    public function removeRow($k)
    {
        unset($this->inlineForm[$k]);
        $this->msgs = [$k . ": تم إزالة سجل  رقم"];
    }

    public function upRows()
    {

        foreach ($this->inlineForm as $k => $row) {

            if (!isset($row[$this->primaryKey]) || trim($row[$this->primaryKey]) === '') {
                DB::table($this->table)->insert($row);
            } else {
                DB::table($this->table)->where($this->primaryKey, $row[$this->primaryKey])->update($row);
            }
        }
        $this->fillToUpdate();
        $this->msgs = ["تم التحديث بنجاح"];

    }

    public function saveRows()
    {

      

        $valState = isset($this->formHeaderData[0])?$this->formHeaderData[0]:0;      
       
        if ($valState == 1) {

        $valArr = [];

          $colOptions = DB::table("coloptions")
              ->where("formName", $this->formName)
              ->where("tableName", $this->table)->get();

          foreach ($colOptions as $col) {
              if ($col->validation) {
                  $valArr["inlineForm.*." . $col->colName] = $col->validation;
              }

          }

          if (count($valArr) > 0) {
              $this->validate($valArr);
          } 

      
        DB::beginTransaction();
       try{

         DB::table($this->formHeaderData[1])->insert($this->formHeaderData[2]);
        
      
        foreach ($this->inlineForm as $k => $row) {             
          DB::table($this->table)->insert($row);          
        }  

          
          DB::commit();      
       
          $this->inlineForm = [];
          $this->formHeaderData=[];
          $this->ref = $this->getRandomStr(10);
          $this->newRow();
          $this->emit("resetHeaderForm" , $this->ref);
          $this->msgs[] = "حفظ الفاتورة الالكترونية  بنجاح";

          }catch (\Exception $ex) {
            DB::rollback(); 
            $this->msgs[] = "Error  .." . $ex->getMessage() . "___" . $ex->getLine();
              // something went wrong
          }  
       
        } else {         

            $this->msgs[] = "يجب تعبئة الفورم  الرئيسي بقيم صحيحة ";          

        }
    }

    public function colOptionByColName()
    {
        $rows = DB::table("coloptions")->where("formName", $this->formName)->get();
        // dd( $rows);
        foreach ($rows as $k => $row) {
            foreach ($row as $kn => $col) {
                $this->colOptions[$row->colName] = (array) $row;
            }
        }
    }

    public function render()
    {
      // $this->msgs[] = "Start the App";
      // $this->dispatchBrowserEvent('showTopDiv', []);

        return view('livewire.multi-inlineform');
    }
}
