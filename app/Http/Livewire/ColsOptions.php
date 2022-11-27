<?php

namespace App\Http\Livewire;

use DB;
use Livewire\Component;
use Schema;

class ColsOptions extends Component
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

    public $optColOption =[];

    public function mount()
    {

        $this->table = session("table") ? session("table") : $this->table;
        $this->formName = session("formName") ? session("formName") : $this->formName;
        $result = DB::select(DB::raw("SHOW KEYS FROM {$this->table} WHERE Key_name = 'PRIMARY'"));
        $this->columns = Schema::getColumnListing($this->table);
        $this->primaryKey = $result[0]->Column_name;
        $this->colName = session("colName") ? session("colName") : $this->primaryKey;
        $this->tableNames = $this->getTableNames();
        $this->optColOption =  $this->colOptionByColName("Default");

    //  dd(  $this->optColOption);

        $this->getTableNames();
        $this->getFormNames();
        $this->getColOptions();

    }

     // shared functions;
     public function colOptionByColName($formName)
     {
         //  form MustBe Unique
         $opts=[];
         $rows = DB::table("coloptions")->where("formName",$formName)->get();
         foreach ($rows as $k => $row) {
             foreach ($row as $kn => $col) {
                 $opts[$row->colName] = (array) $row;
             }
         }

         return $opts;
     }

    public function showMsg($msg)
    {
        $this->msgs[] = $msg;
        $this->dispatchBrowserEvent('showTopDiv', []);
    }

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

        $this->showMsg("تم نسخ خيارات من قورم الى فورم ثاني بنجاح");

    }

    public function createNewForm()
    {
        // $formType;
        // $selTable;
        $res = DB::table("coloptions")->where("formName", $this->newFormName)->first();

        if (trim($this->newFormName != "")) { // if not spaces

            if ($res) {

                $this->showMsg("you Can not create Form The name used");

            } else {

                $this->formType = (int) filter_var($this->formType, FILTER_SANITIZE_NUMBER_INT);
                $autoIncName = $this->getAutoIncreamentName($this->selTable);
                $res = $this->regTableInColOptions($this->selTable, $autoIncName, $this->newFormName, $this->formType);

                $this->showMsg("The Form has  created Form");
            }

        } else {
            $this->showMsg("the Name of form require");
        }

    }

    public function saveOptions()
    {

        $id = $this->options["colOptions_id"];
        $opTosave = $this->options;
        unset($opTosave["colOptions_id"]);

        DB::table("coloptions")->where("colOptions_id", $id)->update($opTosave);

        $this->showMsg("تم تحديث الخيارات بنجاح");
        $this->showMsg("لمراجعة جلب العمود");

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
                DB::table("coloptions")->insert(["formName" =>$formName, "tableName" => $tb_name, "colName" => $column, "eng_name" => $column, "arabic_name" => "", "autoIncreament" => $autoIncName, "inputType" => "text", "widget" => "", "bootstrap" => "col-md-6", "lookup" => "", "formType" => $formType]);
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
    }

    public function render()
    {
        session()->put('table', $this->table);
        session()->put('formName', $this->formName);
        session()->put('colName', $this->colName);

        $this->ic++;
        return view('livewire.cols-options');
    }
}
