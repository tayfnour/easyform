<?php
namespace App\Http\Livewire;

use DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Schema;

class DbManageComponent extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $table;
    public $form;
    public $oldTable;
    public $formName;
    public $paginate = 5;
    public $tableModel;
    public $targetRef;
    public $SearchWord; // to filter table
    public $primaryKey;
    public $columns;
    public $colArr;
    public $tableNames;
    public $rows_result;
    public $autoStyle;

    public $counting = 0;

    public $rows_update = [];
    public $row_update_par = [];

    public $row_insert = [];
    public $messages = [];
    public $autoData = [];
    public $findRef = [];
    public $findRefPar = [];
    public $colopts;
    public $relations; // to get form/colname/pair for evry on to on or many relationship

    public $onePhoto = [];

    public $parentForm;
    public $parentCol;

    //  $prop
    //public $rows_

    protected $listeners = ['refreshcomp' => 'mount', 'saveOption' => 'saveOption', 'EditRow' => 'EditRow'];
    protected $paginationTheme = 'bootstrap';

    public function mount($parentForm = null, $SearchWord = null)
    {
        //if $targetRef is null  meaning  no entry data
        if (!is_null($parentForm)) {

            $result = $this->getRelations($parentForm);
            $this->relations = $result[0];
            $this->form = $result[1]->form_name_target;
            $this->table = $result[1]->table_name_target;
            $this->oldTable = $this->table;
            $this->targetRef = $result[1]->col_name_target;
            $this->SearchWord = $SearchWord;
            $this->parentForm = $parentForm;
            $this->parentCol = $result[1]->col_name_parent;

        } else {
            // if comp input  null
            $this->form = "Default";
            $this->table = "coloptions";

            if (session("db_form")) {
                $this->form = session("db_form");
                $rs = DB::table("coloptions")->where("formName", $this->form)->first();
                // get table from entry form
                $this->table = $rs->tableName;
                $this->oldTable = $this->table;
            }

        }

        // option of col options
        $this->optColOption = $this->colOptionByColName("coloptions");
        $this->colopts = $this->colOptionByColName($this->form);

        $this->tableChanged();

    }

    public function saveRelation()
    {

        // if no parent
        if (!is_null($this->parentForm)) {
            $data = [
                "form_name_parent" => $this->parentForm,
                "col_name_parent" => $this->parentCol,
                "table_name_target" => $this->table,
                "form_name_target" => $this->form,
                "col_name_target" => $this->targetRef,
            ];

            DB::table("relations")->insert($data);
            $this->messages[] = "the relation is saved";
        } else {
            $this->messages[] = "No Parent Form";
        }

    }

    public function changeToRealation($t_Form, $t_table, $targetRef)
    {
        $this->form = $t_Form;
        $this->table = $t_table;
        $this->targetRef = $targetRef;

        $this->tableChanged();
    }

    public function getRelations($parentForm)
    {
        $str = "<ul  style='direction:ltr'><li>Parent Form : $parentForm</li>";

        $res = DB::table("relations")->where("form_name_parent", $parentForm)->get();

        if ($res) {

            foreach ($res as $val) {
                $str .= "<li  wire:click='changeToRealation(\"$val->form_name_target\" , \"$val->table_name_target\" , \"$val->col_name_target\")'  style='cursor:pointer'>
               $val->form_name_target -> $val->col_name_target
               </li>";
            }

            $str .= "</ul>";

            return [$str, $res[0]];

        } else {
            return "no relation";
        }

    }

    public function getAutoValue($k, $v)
    {

        if (isset($this->colopts[$k]["lookup"])) {
            $lkupArr = explode("|", $this->colopts[$k]["lookup"]);
            $rs = DB::table($lkupArr[0])->where($lkupArr[2], $v)->first();
            // dd($rs->{$lkupArr[1]});
            if ($rs) {
                return $rs->{$lkupArr[1]};
            } else {
                return null;
            }

        } else {
            return null;
        }

    }

    public function setSelectValue($rowId, $colName, $k, $val)
    {
        //   $this->findRef[$rowId] =  $val;
        // we will add it $colName
        //  dd($k , $val);
        $this->findRefPar[$colName][$rowId] = $k;
        $this->autoData = null;
    }

    public function cleanFeild($rowId, $id, $colName)
    {

        DB::table($this->table)->where($this->primaryKey, $id)->update([$colName => null]);
        $this->findRefPar[$colName][$rowId] = null;
        $this->findRef[$colName][$rowId] = null;
        //  $this->findRef[$colName][$rowId]=null;
        // dd( $this->findRefPar);
    }

    public function autoComplete($rowId, $colName)
    {

        // dd( $this->colopts);

        if (!empty($this->findRef[$colName][$rowId])) {

            $v = $this->findRef[$colName][$rowId];

            //  dd($v);

            if (trim($v) != '') {

                $lkupArr = explode("|", $this->colopts[$colName]["lookup"]);

                // dd($colName , $this->colopts[$colName]);

                $strAuto = "<ul>";

                if ($this->colopts[$colName]["colType"] == "list") {
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
                    //  $click =
                    $strAuto .= "<li wire:click='setSelectValue( {$rowId} , \"{$colName}\",\"{$k1}\",\"{$v2}\")' style='cursor:pointer' id='{$k1}' >{$v2}</li>";
                }
                // strlen($strAuto) that mean there is result
                if (strlen($strAuto) > 4) {
                    $this->autoData[$rowId][$colName] = $strAuto . "</ul>";
                } else {
                    $this->autoData = null; // dd($this->colopts);
                    $this->autoData[$rowId][$colName] = "Enter Search Word";
                }

            }

        } else {
            $this->autoData = null; // dd($this->colopts);
            $this->autoData[$rowId][$colName] = "Enter Search Word";
        }

    }

    public function autoCompleteOpen($rowId, $colName)
    {
        $this->autoData = null; // dd($this->colopts);
        $this->autoData[$rowId][$colName] = "Enter Search Word";
        $this->messages[] = " function autoComplete" . $colName;
    }

    public function closeAllAuto()
    {
        $this->autoData = null;
    }

    public function closeAuto($rowId, $colName)
    {
        $this->autoData[$rowId][$colName] = null;
        $this->messages[] = " function closeAuto" . $colName;
    }

    public function getRandomStr($len)
    {
        return bin2hex(random_bytes($len));
    }

    public function getActionResult($opt){

    // MHT::very clean code  when pass $opt you pass 27 parameter

        if($opt['action']="getRandomStr"){
            return  $this->getRandomStr(intval($opt["param1"]));
        }
      
    }

    public function insertRowInTable()
    {

        // for empty previeous
        // $this->findRefPar=null;
        // $this->findRef = null;

        $opts = $this->colOptionByColName($this->form);

      //  dd($opts);

        $row = [];

        //$i=0;

        //dd($opts);

        foreach ($this->columns as $col) {

            // $this->targetRef: name of  targe col in child table
            // child table how appear inside form
            // if $this->targetRef not null sel input value from mount

            if ($col == $this->targetRef) {
                $row[$col] = $this->SearchWord;
            }else if(isset($opts[$col]["action"])) {

                if($opts[$col]["action"]=="generate_ref"){
                    $row[$col] =$this->getRandomStr(10);
                }
                  

            }else if (!is_null($opts[$col]["defaultVal"])) {           
               
                $row[$col] = $opts[$col]["defaultVal"];
            }
            // HTM: action here the dynamic default value its  ovverride static default value
            else if (isset($opts[$col]["action"])) {
                $row[$col] = $this->getActionResult($opts[$col]);
            } else {

                $row[$col] = null;
            }

           

        }
       
         // dd($row);
          //unset ($row[$this->p1rimaryKey]) ;
          // dd($row);
       
        try {
            $id = DB::table($this->table)->insert($row);
            $this->messages[] = "The new Row  successfuly id is :" . $id;
        } catch (\Exception $ex) {
            $this->messages[] = $ex->getMessage() . "___" . $ex->getLine();
        }
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

    public function getFormNames()
    {

        $this->formNames = [];
        $res = DB::table("coloptions")
            ->select('formName')
            ->where("tableName", $this->table)
            ->groupBy("formName")
            ->get();

        if ($res->count() > 0) {
            //dd($res->count());
            foreach ($res as $k => $rs) {
                $this->formNames[] = $rs->formName;
            }

        } else {

            $this->messages[] = "يجب عمل صفات وخيارات للجدول : " . $this->table;
        }

    }

    public function EditRow($id, $obj, $row)
    {

        foreach ($obj as $k => $val) {
            if (trim($val) == '') {
                $obj[$k] = null;
            }
        }

        $valArr = [];
        $this->rows_update = $obj;

        $colOptions = DB::table("coloptions")
            ->where("formName", $this->form)
            ->where("tableName", $this->table)->get();

        foreach ($colOptions as $col) {

            if (!empty($col->validation)) {
                $valArr["rows_update." . $col->colName] = $col->validation;
            }

        }

        // dd($valArr);

        if (count($valArr) > 0) {
            $this->validate($valArr);
        }

        // foreach($obj as $key => $val){
        //     if($key == "email_verified_at" || $key == "updated_at"  )
        //      $obj[$key]=date('Y-m-d H:i:s');
        //      if($key == "created_at")
        //      unset( $obj[$key]);        }

   //     dd($row, $this->onePhoto, $obj);

        if (count($this->onePhoto) > 0) {
            foreach ($this->onePhoto as $k => $photo) {
                foreach ($photo as $key => $ph) {
                    if ($row == $k) {
                        if (empty(trim($obj[$key]))) {
                            if (is_object($ph))
                            $obj[$key] = $ph->store($this->table, "global_images");
                        }
                    }
                }
                unset($this->onePhoto[$k]);
            }
        }

        try {
            DB::table($this->table)->where($this->primaryKey, $id)->update($obj);
            $this->messages[] = "The Row updated successfuly id is :" . $id;
        } catch (\Exception $ex) {
            $this->messages[] = $ex->getMessage() . "___" . $ex->getLine();
        }

    }

    public function tableChanged()
    {

        $this->getFormNames();

        //swap table to old if  no form name
        if (count($this->formNames) == 0) {
            $this->table = $this->oldTable;
            $this->getFormNames();
        } else {
            $this->oldTable = $this->table;
        }

        $this->form = $this->formNames[0];
        $this->resetPage(); // reset paginaition
        $this->row_update = [];
        $result = DB::select(DB::raw("SHOW KEYS FROM {$this->table} WHERE Key_name = 'PRIMARY'"));
        $this->columns = Schema::getColumnListing($this->table);
        //  $this->primaryKey = isset($this->targetRef)? $this->targetRef:$result[0]->Column_name;
        $this->primaryKey = $result[0]->Column_name;
        $this->autoStyle = "display:none";
        $this->colopts = $this->colOptionByColName($this->form);

        session()->put('db_form', $this->form);
    }

    public function getTableNames()
    {
        $blockTables = ["failed_jobs", "migrations", "password_resets", "personal_access_tokens", "sessions"];
        $Tb_names = DB::select('SHOW TABLES');
        $Arr = [];

        foreach ($Tb_names as $tb_name) {

            if (!in_array($tb_name->Tables_in_easypanel, $blockTables)) {
                $Arr[] = $tb_name;
            }

        }
        return $Arr;
    }

    public function updatedPaginators($page, $pageName)
    {

        $this->findRefPar = null;
        $this->findRef = null;
        //   $this->dispatchBrowserEvent('loadStates', []);
    }

    public function get_cols_options()
    {

        $res = DB::table("coloptions")->where("formName", $this->form)->where("tableName", $this->table)->get();

        // dd($res);

        foreach ($res as $rs) {

            $colOpt_arr[$rs->colName] = $rs;

        }

        if (isset($colOpt_arr)) {
            return (object) $colOpt_arr;
        } else {
            $this->messages[] = "لايوجد خيارات للجدول الرجاء انشاءها أولا";
        }

    }

    public function grtAutoIncreamentName($tb_name)
    {
        $result = DB::select(DB::raw("SHOW KEYS FROM {$tb_name} WHERE Key_name = 'PRIMARY'"));
        return $result[0]->Column_name;

    }

    public function render()
    {
        //doing every request

        session()->put('db_form', $this->form);

        if (empty($this->targetRef) || !in_array($this->targetRef, $this->columns)) {
            $rows = DB::table($this->table)->orderby($this->primaryKey, "desc")->paginate($this->paginate);
        } else {
            $rows = DB::table($this->table)->where($this->targetRef, "like", "%" . $this->SearchWord . "%")->orderby($this->primaryKey, "desc")->paginate($this->paginate);
        }

        return view('livewire.db-manage-component',
            [
                "opts" => $this->get_cols_options(),
                "table_names" => $this->getTableNames(),
                "rows" => $rows,
                "auto" => $this->primaryKey,
            ]
        );
    }
}
