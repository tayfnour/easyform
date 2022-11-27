<?php
namespace App\Http\Livewire;
use DB;
use stdClass;
use App\MyClass\formBuilder;
USE App\MyClass\Qfs;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SmartFormBuilder extends Component
{   
    use WithPagination;
    use WithFileUploads;
   // use carFileExtention;
   // use salesPointsExt;    

    protected $listeners = ["savePosInvoice"=> "savePosInvoice" ,"setDate" => "setDate" , "refreshcomp" => "init"];
    protected $paginationTheme = 'bootstrap';

    public $colArr = [];
    public $colArrPar = [];
    public $rows;

    public $validateState = 0;

    private $formOpts = []; // absolutation
    public $formData = [];

    public $header_id;

    public $rowOpts = []; // array links  row_id with form name and typeof forme single or multiple // not reset // when add row must montion releted form

    public $refs = [];

    public $forms = [];

    public $hideFrame = [];

    public $msgs = [];

    public $stopAuto = true;

    private $arrows = [];

    public $preventNewRowIfRowsZero = "false";

    public $searchCar;

    public $colArrTitle;

    public $photos=[];
    public $photosDesc=[];

    public $floatDiv;
    public $floatForm;

    public $currentFn;
    public $currentCol;
    public $compParam1;
    public $compParam2;
    public $startTime;

    public $passToAlpine;
    public $categories;
    public $customers;

    public $dynamicQuery=[];

    public $requestTime=0;

    public $formlayouts=[];

    public $filter=[];

    public $cc;

    public $validator;

    public $proName;
    public $proPage;


    public $publicFO;
    
    public function mount()
    { 
      //  dd(request()->query());

        $this->init();
    }

    public function init()
    {
       /// $this->startTime = microtime(true);
        //Log::channel('mht')->info("start of init : " .$this->startTime);

        $this->msgs[] = "تم تنشيط الصفحة";
        $this->forms=[];

       //  $this->cc= explode("\\",get_class($this));  
       // dd(get_class($this));
       // get form tables options and names to this componant
       // fil if fOpts

        $this->proName =  session('proName_Shorten') ? session('proName_Shorten') : "fleet";
        $this->proPage = session("proPage_Shorten") ? session("proPage_Shorten") : "testingPage";

     
     //   $formName = session("formName_Shorten") ? session("formName_Shorten") : "myprojects";
    
      
     $res = DB::table("myprojects")->where("isActive" ,"A")->where("proName", $this->proName)->where("proPage" , $this->proPage )->orderBy("ordering", "asc")->get();

     //dd($res) ;
       if(count($res)==0){
        echo("you must define at least one form") ;
        return;
       }
       

        foreach ($res as $k => $v) {  
            $fn= $v->proForm;                   
            //$this->fOpts[$v->proForm] = ["proForm"=>$v->proForm,"isOpen"=>$v->isOpen , "isFloat"=>$v->isFloat, "description"=>$v->description ];
            $this->formOpts[$fn]= $this->geOptionsOfForm($fn);
            $this->formOpts[$fn]["_cols"] = $this->sortColumns($this->formOpts[$fn]);
            $this->formOpts[$fn]["_fAttrs"] =json_decode($v->formAttrs , true);
            $this->formOpts[$fn]["_fn"] =  $fn ;//reset($this->formOpts[$fn])["formName"];
            $this->formOpts[$fn]["_tbName"] = reset($this->formOpts[$fn])["tableName"];
            $this->formOpts[$fn]["_pk"] = reset($this->formOpts[$fn])["autoIncreament"];
                       
            $this->formData[$fn]["formBS"]=$v->formBS;
            $this->formData[$fn]["isFloat"]=$v->isFloat;
            $this->formData[$fn]["_fprops"] = (array) $v; 
            $this->formData["_formsArr"][]=$fn;
        }

        session()->put("formOpts" ,$this->formOpts );

     // dd($this->formOpts);

       //example of build query
       // dd(Qfs::buildQueryFromString('table=>myprojects|wherea=>isActive*=*A|whereb=>proPage*=*NewTemplate|get=>'));
       //dd(Qfs::buildQueryFromString('table=>myprojects|wherea=>isActive*=*A|whereb=>proPage*=*NewTemplate|paginate=>4*fffr'));
       
       $this->formsLoop();
    }

    public function sortColumns($opts)
    {
       $newArr = [];

        foreach ($opts as $k => $ops) {
            if ($k != "cols") {
                $newArr[$ops["ordering"]] = $k;
            }

        }
        ksort($newArr);
        return array_values($newArr);
    }

    public function geOptionsOfForm($formName)
    {
        //  form MustBe Unique
        $arrOfOpts = [];
        $rows = DB::table("coloptions")->where("formName", $formName)->get();
        foreach ($rows as $k => $row) {
            foreach ($row as $kn => $col) {
                $arrOfOpts[$row->colName] = (array) $row;
            }
        }

        return $arrOfOpts;
    }

    public function formsLoop()
    {
        $this->colArr = [];
   
        $this->refs = [];

        $this->formlayouts=null;

        $this->formOpts = session("formOpts");

       
        // autocomplete must check value first of get default value while we enter  new value

      //  if(count($this->formOpts["_formsArr"])==0)$this->formOpts["_formsArr"][]="myproject";

        foreach ($this->formData["_formsArr"] as $form) {

            $pk = $this->formOpts[$form]["_pk"];
            $fn = $this->formOpts[$form]["_fn"];
            $table = $this->formOpts[$form]["_tbName"];
            $fdata = $this->formData[$form];
            //$action = $opts[$pk]["action"];
            $page = str_replace(" ", "_", $fn); 

            unset($this->formData[$fn]['autos']); //remove autocomplete values to refresh
            unset($this->formData[$fn]['rows']); // remove rows from formOpts  to refresh          

            $queryString = "table=>{$table}|";

       //     dd($opts["queryMaster"] , $opts["_fprops"]["formQuery"] );
       //Log::channel('mht')->info("Query($fn):".$opts["_fprops"]["formQuery"]);

       //    if(isset($opts["queryMaster"])){
      //  dd($opts["_fprops"]["formQuery"]);

             if(!empty($fdata["_fprops"]["formQuery"])){  

                if (!empty($fdata["_fprops"]["queryRef"])) {
                    //dd($this->refs);

                    //Log::channel('mht')->info($opts["QueryRefrence"]);

                    // get data at refrence
                    $refArr = explode("|" , $fdata["_fprops"]["queryRef"]);

                    //dd($this->refs);
                    if(isset($this->refs[$refArr[0]])){

                        $refVal =  $this->refs[$refArr[0]][$refArr[1]];

                    }else{   
                        
                        $this->refs[$refArr[0]][$refArr[1]] = null;

                        $refVal = null;
                    }

                   $queryString .= "whered=>{$refArr[1]}*=*{$refVal}|";

                   
                }

                if(isset($opts["formFilter"])){

                 //   dd("inside form filter");
                  
                    $val = isset($this->filter[$fn])?$this->filter[$fn]:"";   
                     
                   // Log::channel('mht')->info("QueryRefrence"."|val=>|" .$val."|col=>|". $opts["formFilter"]);

                 
                    if(!empty($val)){  

                        $col = $opts["formFilter"];

                        $queryString .= "wheref=>{$col}*like*%{$val}%|";

                       // Log::channel('mht')->info("queryString=>".  $queryString.$opts["queryMaster"] );
                       
                       // $this->resetPage($fn);
                    }                   
                  
                }

            
                 
                 $result = Qfs::buildQueryFromString($queryString.$fdata["_fprops"]["formQuery"]);    
                 $this->arrows[$page]= $result->links(); 

                   // Log::channel('mht')->info($fn."=>".json_encode($this->arrows[$page]));
              
            }

            else{
            // if(isset($this->formOpts[$form]["cols"])) {

                $result = [];

                $object = new stdClass();

                 foreach($this->formOpts[$form]["_cols"] as $val){

                    $object->$val  = null;

                }

                $result[] = $object;

             }

               
              
        //    }

            // else draw form without query
           //dd($result);
                    
           if (isset($result)) {               

                foreach($result as $row_id => $row){

                        $rowArr = (array)$row;
                     //   Log::channel('mht')->info("formName:".$fn);
                        foreach ($rowArr as $col => $value)
                            {
                             //   Log::channel('mht')->info("colName:".$col);

                             if(isset($this->formOpts[$fn][$col])){

                                if($this->formOpts[$fn][$col]["colType"]=="ref"){
                                      $this->refs[$fn][$col]=$value;                                   
                                } 
                                if($this->formOpts[$fn][$col]["inputType"]=="auto"){                                
                                       $val = $this->getLookUpValues($this->formOpts[$fn],$col,$value);
                                       if(!empty($val))                            
                                           $this->formData[$fn]['autos'][$row_id][$col] = $val;
                                        // else
                                        //    $this->formOpts[$fn]['autos'][$row_id][$col] = null;
                               }                                          
                             }else{

                                $this->msgs[]="you must add attribute to column :".$col;
                             }
                                
                            

                            }
                        
                        
                        $this->formData[$fn]['rows'][$row_id] = $rowArr;

                }       
             
            }   

            $this->buildForms();

        }  
     
    }

    // if change in source data use formsloop
    // if no change in source data use buildForms

    public function buildForms(){  
      //  dd($this->formData);
        foreach ($this->formData["_formsArr"] as $fn) {
                  $this->formlayouts[$fn] = formBuilder::buildForm($this->formOpts[$fn], $this->formData[$fn] , $this->validator);  
                  
        }    
    }

    public function setAutoFeild ($model,$id){

        $modelArr = explode("__", $model);
        $fn=$modelArr[0];
        $rowId=$modelArr[1];
        $col=$modelArr[2];
        $this->formData[$fn]["rows"][$rowId][$col] = $id;
    
        $this->updatedFormOpts($id, "$fn.rows.$rowId.$col");
    }

    
    public function updatedFormData($val, $RowColName)
    {
      // dd($val , $RowColName); 
      $this->formOpts = session("formOpts");
             
       $rowArr = explode(".", $RowColName);

       $fn =  $rowArr[0];
       $row_id = $rowArr[2];
       $col = $rowArr[3];

    
      // dd($this->formOpts[$fn]);

        if($rowArr[1] =="autos"){       
            $this->autoComplete($fn,$col,$row_id,$val);
        }

       if($rowArr[1] =="rows"){         
       
       $table= $this->formOpts[$fn]["_tbName"];
       $pk = $this->formOpts[$fn]["_pk"];
       $id = $this->formData[$fn]["rows"][$row_id][$pk];

       $this->formData[$fn]["temps"][$row_id][$col]=$val;

       $data_row=$this->formData[$fn]["rows"][$row_id];

       $continue = true;

       $vali_row = [$row_id=>$data_row];
      // dd($vali_row);

       $validateString = $this->formOpts[$fn][$col]["validation"];

      if(!empty($validateString)){

           $rules = ['*.'.$col => $validateString];

       // dd($rules);
           $this->validator = Validator::make($vali_row,$rules)->errors()->toArray();  


           if (count($this->validator) > 0) {
               $this->formData[$fn]["temps"][$row_id][$col]=$val;
               $this->formsLoop();
               $continue=false;
           }

      }


      if(!empty($this->formOpts[$fn][$col]["logicalVal"]))
      {

        $operators=["+","=","*","/","(",")","",""];
        $inp = $this->formOpts[$fn][$col]["logicalVal"];
        $pttn='@([-/+\*=\/\(\)\?\:])@';
        $cols = $this->formOpts[$fn]["_cols"];

        $inpArr = explode(";",$inp);

        // dd($inpArr);

        foreach ($inpArr as $k => $input){
        
         if($input !=""){

            $out=preg_split( $pttn, preg_replace( '@\s@', '', $input ), -1, PREG_SPLIT_DELIM_CAPTURE );
      
               // dd($out);

                $str = "";
         
                $first= reset($out);

                if(in_array($first , $cols)){                
                    array_shift($out) ;
                    array_shift($out) ;  
                }
                
                foreach ($out as $key => $val){
                
                    if(in_array($val , $cols)){       

                    $v = $this->formData[$fn]["rows"][$row_id][$val];

                    $str.=$v; // replace with value of current 

                    }else{
                    $str.=$val; // or set charchter like to be
                    }
                
                }     
                
                if(in_array($first , $cols)){
                    $str='$d='.$str.";";                    
                }else{
                    $str=$str.";"; 
                }
                
                //dd($str);
                eval($str);
                               
                if(in_array($first , $cols))
                $this->formData[$fn]["rows"][$row_id][$first]=$d; 
            }
          }
      
        }

       // Process as Cell level
       if($continue){

           $row = $this->formData[$fn]["rows"][$row_id] ;

           try {
              // dd($val);
               $result = DB::table($table)->where($pk, $id)->update($row);
               $this->formsLoop();
            } catch (\Exception $e) {
              $this->msgs[] = $e->getMessage();
           }

       }

    }else{
       // $this->formOpts[$fn]["temps"][$row_id][$col]=$val;
        $this->formsLoop(); 

    }
       
        // Hook One
        // Process as row level
       // if(method_exists ($this ,"Calc_Fields_Values")){

       //      $vali_row =[$row_id=>$data_row];

       //     $this->validator = Validator::make($vali_row,
       //     [
       //         '*.price' => 'required|numeric',
       //         '*.product_id' => 'required|numeric',

       //     ])->errors()->toArray();

         


       //    // dd($validator->errors()->get("preTotal"));

       //     if (count($this->validator) == 0) {
     
       //      list($updatedRow , $continue) = $this->Calc_Fields_Values($fn ,$data_row , $row_id , $pk );

       //      if($continue==false){
       //         $this->formOpts[$fn]["rows"][$row_id] = $updatedRow;

       //         try{
       //             DB::table($table)->where($pk , $id)->update($updatedRow);
       //             $this->validator=[];
       //             $this->formsLoop();

       //           } catch (\Exception $e) {
       //             $this->msgs[] = $e->getMessage();
       //          }

       //      }

       //      }    
       // }
        
       
   }

   public function updateImageInfo($sup_id , $name , $price , $packing , $desc , $img_id){



   // dd($id , $name , $price , $desc , $img_id);

    DB::table("imageinfo")->where("img_id" , $img_id)->update([         
        "desc" => $desc,
        "price"=> $price,
        "packing" =>$packing,
        "supplier_id" =>$sup_id     
    ]);
    $this->msgs[] = "تم تحديث معلومات الصورة بنجاح";
    $this->formsLoop();
    
   }
   
   public function saveClassifyImage($model ,$sup_id, $price,$packing,$desc,$ref){

  //  dd($model ,$sup_id , $price, $desc,$val);
  
        $modelArr = explode("__", $model);
        $fn=$modelArr[0];
        $rowId=$modelArr[1];
        $col=$modelArr[2];
        $tb=$this->formOpts[$fn]["tbName"];

      //  dd($this->formOpts[$fn]["_photosinfo"][$rowId][$col]);

        // $ref = $this->formOpts[$fn]["rows"][$rowId][$col];
        // $sup_id =isset($this->formOpts[$fn]["_photosinfo"][$rowId][$col]["suplier"])?$this->formOpts[$fn]["_photosinfo"][$rowId][$col]["suplier"]:null;
        // $price =isset($this->formOpts[$fn]["_photosinfo"][$rowId][$col]["price"])?$this->formOpts[$fn]["_photosinfo"][$rowId][$col]["price"]:0;
        // $desc =isset($this->formOpts[$fn]["_photosinfo"][$rowId][$col]["desc"])?$this->formOpts[$fn]["_photosinfo"][$rowId][$col]["desc"]:null;

        if(empty($ref)){
           
            $ref = DB::table($tb)->max("img_ref")+1;
        }

    //    $this->formOpts[$fn]["rows"][$rowId][$col] = $ref;
    
        $filename = $this->formOpts[$fn]["_photos"][$rowId][$col]->store( $fn , "global_images");
         
        // dd($filename);
            
          $id =  DB::table("imageinfo")->insertGetId([           
                  "folder" => $fn,
                  "path" => $filename,
                  "desc" => $desc,
                  "price"=> $price,
                  "packing" =>$packing,
                  "supplier_id" =>$sup_id,
                  "img_ref" => $ref,
                
              ]);
 
          unset($this->formOpts[$fn]["_photos"][$rowId][$col]);  
          unset($this->formOpts[$fn]["_photosinfo"][$rowId][$col]);
          $this->updatedFormOpts($ref , "$fn.rows.$rowId.$col");
 
          $this->msgs[] = "the Image Save syccessfully";

          $this->formsLoop();

   }
   public function hideValidation(){

        $this->validator = [];
        $this->formsLoop();
   }


   public function Calc_Fields_Values($fn , $data_row , $row_id , $pk){

      //dd(func_get_args());
      //VALIDATE $DATA_ROW
      //  dd($data_row);

     //  ["data_row.price"=>"numiric"];

       // $this->validate([
       //     "formOpts.$fn.rows.$row_id.price" => "numeric"           
       // ]);

      if($fn == "invoices"){   

           $data_row["quantity"] = trim($data_row["quantity"]) =='' ? 1: $data_row["quantity"];
           $data_row["price"] =  trim($data_row["price"]) ==''  ? 0 : $data_row["price"];

           if($data_row["discountType"]=="1"){
                 $data_row["preTotal"] = $data_row["quantity"]*($data_row["price"]-$data_row["discount"]);
           }else{
                 $data_row["preTotal"] = $data_row["quantity"]*($data_row["price"]-($data_row["price"]*$data_row["discount"]/100));
           }
         
           $data_row["vatVal"] = round($data_row["preTotal"]*$data_row["vat"]/100 , 2);
           $data_row["total"] = $data_row["preTotal"]+$data_row["vatVal"];
          
           return [$data_row,false];
      /*              
       $data_row =[
       "invoice_id" => 1,
       "ref" => "340d785810acaf7c4fdf",
       "product_id" => "33",
       "price" => 111,
       "quantity" => 3,
       "unit" => 1,
       "discount" => 0,
       "discountType" => "1",
       "preTotal" => 333,
       "vat" => 15,
       "vatVal" => 49.95,
       "total" => 382.95,
       "product_image" => null,
       ];
       */

       

      }else{
         return [$data_row,true];
      }
   }

  

   public function hideAuto($fn,$col,$rowId){
           
            $this->formOpts[$fn]["autoDiv"][$rowId][$col] = "";
            $this->formsLoop();
   }

   public function autoComplete($fn,$col,$row_id,$val= null)
   {
       $this->stopAuto = false;
       $strAuto='';
       $opts = $this->formOpts[$fn];

       //dd($fn,$col,$row_id,$val);

       if (!empty($val)) {
          $lkupArr = explode("|", $opts[$col]["lookup"]);
          $res = DB::table($lkupArr[0])
                         ->select([$lkupArr[1], $lkupArr[2]])
                         ->get();
         foreach ($res as $key => $value) {
            $k1 = $value->{$lkupArr[1]}; // always id
            $v2 = $value->{$lkupArr[2]}; // always name
            $strAuto .= "<li style='cursor:pointer' data-val='$k1' >{$v2}</li>";
            $this->formOpts[$fn]["autoDiv"][$row_id][$col] = "<ol>".$strAuto."</ol>";
         }      
                         
        //  dd( $strAuto);               

       } 

    
       $this->formsLoop();

   }

    public function getLookUpValues($opts, $colName, $id)
    {
        //mht: 0 : table Name -- 1:id  --2: name of lookup

        $lkupArr = explode("|", $opts[$colName]["lookup"]);

        // dd( $lkupArr , $id , $colName);

        if (count($lkupArr) > 2) {
            $res = DB::table($lkupArr[0])
                ->select($lkupArr[2])
                ->where($lkupArr[1], $id)
                ->first();      

            if ($res) {
                return $res->{$lkupArr[2]};
            } else {
                return null;
            }

        } else {
            $this->msgs[] = "No Lookup Option exist for " . $colName;
        }

    }
    
    //create Default value By row_id or by form

    public function createNewFormRow($frName)
    {
      $this->formOpts = session("formOpts");
      //we store formoption in session to speed program
   
       $opts = $this->formOpts[$frName];  
       // get form Options 
      // try to set private no send every request
       $table = $opts["_tbName"];
       
        // Hook One
        if(function_exists("Get_Modified_Options"))
        $opts = Get_Modified_Options($opts , $row_id);
       // dd($opts["cols"]);
       
       $newRow = [];


        foreach ($opts["_cols"] as $col) {   
            
            if (isset($opts[$col]["defaultVal"])) {
                $newRow[$col] = $opts[$col]["defaultVal"];
            }    
            // get value of cols from function
            if(isset($opts[$col]["dyInitVal"])){
                $refArr = explode("|" , $opts[$col]["dyInitVal"]);
                
                if($refArr[0]=="getRef"){
                $refVal =  $this->refs[$refArr[1]][$refArr[2]];
                $newRow[$col] = $refVal;
                }

                if($refArr[0]=="generateRef"){
                $newRow[$col] = $this->getRandomStr(10);
                }    
            }

            if($opts[$col]["inputType"]=="auto"){
                unset($this->formOpts[$frName]['autos']);
            }
         
            if (!isset($newRow[$col])) {
                 $newRow[$col] = null;
             }
             
            // if (isset($opts[$col]["param1"]) && $opts[$col]["param1"] == "generateDefaultRefOnNew") {
                        //     $newRow[$col] = $this->getRandomStr(10);
                        // }  
            // //get Current Refrence         
            // if (isset($opts[$col]["action"]) && $opts[$col]["action"] == 'getMainRef') {
            //     $p1 = $opts[$col]["param1"];
            //     $p2 = $opts[$col]["param2"];
            //     $ref = $this->refs[$p1][$p2];
            //     $newRow[$col] = $ref;
            // }
            
           

        }

     //   dd($newRow); //test new row for new form

      
           try {   
                             
            $this->resetPage($frName);
            $id = DB::table($table)->insertGetId($newRow); 
            $this->msgs[] = "successes in creating new row";          
                    
            } catch (\Exception $ex) {
                $this->msgs[] = "Error  .." . $ex->getMessage() . "___" . $ex->getLine();
            }

           
            $this->formsLoop();
       
    }

   


     public function openForm($form){
         DB::table("myprojects")->where("proForm" ,$form)->update(["isOpen" =>1]);
         $this->init();
     }

     public function openFloatForm($form){
       $this->floatForm = $form;
     }

     public function closeForm($form){
        DB::table("myprojects")->where("proForm" ,$form)->update(["isOpen" =>0]);
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

        $this->floatForm =  $this->floatDiv = null;
        $this->init();

     }


    public function showGlobalVar()
    {
      dd($this->formOpts["productscompare"]);
    } 
  
    public function updatedPhotos (){
        $this->formsLoop();
    }

    public function updatedFilter($val , $fn){

        
        if(!empty(trim($val)) || $val == "0"){
            $this->filter[$fn] = $val;
            $this->resetPage($fn);
        }   

      

        $this->formsLoop();
    }

    public function updatedPaginators()
    {
        $this->photos=[];
      //  $this->colArrPar=[];
        $this->formsLoop();
    }

   

    public function setDate($row_id, $colName, $v)
    {
        //dd($colName , $v);
        $this->colArr[$row_id][$colName] = $v;

        $this->saveRowOnUpdate($row_id, $colName);

        //$this->validateHeader();

    }

    public function deleteImage($model , $id , $type=null){

        $modelArr = explode('__',$model);

        $fn=$modelArr[0];
        $rowId=$modelArr[1];
        $col=$modelArr[2];


         
       DB::table("imageinfo")->where("img_id", $id)->delete(); 

       $this->msgs[] = "Image deleted successfully";


       if($type == null)
       $this->updatedFormOpts(null , "$fn.rows.$rowId.$col");

       $this->formsLoop();
     }

     

    public function saveImage($model,$desc ,$row_id){

       // dd($model , $desc , $row_id);

        $modelArr = explode('__',$model);

        $fn=$modelArr[0];
        $rowId=$modelArr[1];
        $col=$modelArr[2];

       // dd($fn , $rowId , $col);

        $filename = $this->formOpts[$fn]["_photos"][$rowId][$col]->store( $fn , "global_images");
         
        dd($filename);
           
         $id =  DB::table("imageinfo")->insertGetId([           
                 "folder" => $fn,
                 "path" => $filename,
                 "desc" => $desc,
               //  "timestamps" => date("Y-m-d H:i:s"),
             ]);

         unset($this->formOpts[$fn]["_photos"][$rowId][$col]);  
     
         $this->updatedFormOpts($id, "$fn.rows.$rowId.$col");

         $this->msgs[] = "the Image Save syccessfully";
    }


    public function cleanAuto($row_id, $colName)
    {
        // dd($row_id ,$colName);
        $this->colArrPar[$row_id][$colName] = null;
        $this->colArr[$row_id][$colName] = null;
      //  $this->saveRowOnUpdate($row_id , $colName);
    }

    
 
    public function hydrate()
    {
        $this->dispatchBrowserEvent('hideTopDiv', []);
        $this->dispatchBrowserEvent('getStateOfEle', []);
    }
  
    public function setSelectValue($fn,$row_id,$col,$k,$val)
    {
       $this->formData[$fn]['rows'][$row_id][$col] = $k;
       $this->formData[$fn]['autos'][$row_id][$col] = $val;    
       $this->formData[$fn]['autoDiv'][$row_id][$col] = null ;

       $table = $this->formOpts[$fn]["_tbName"];
       $pk = $this->formOpts[$fn]["_pk"];
       $id = $this->formData[$fn]["rows"][$row_id][$pk];
        
       try {
        // dd($val);
         $result = DB::table($table)->where($pk, $id)->update([$col=>$k]);
         $this->formsLoop();
      } catch (\Exception $e) {
        $this->msgs[] = $e->getMessage();
     }


       $this->formsLoop();
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

        $table = $opts["_tbName"];

        $pk = $opts["_pk"];

        $id = $this->colArr[$row_id][$pk];

        $fn = $opts["_fn"];

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
       
       // $this->requestTime++;
       // Log::channel('mht')->info("before render : " . (microtime(true) - $this->startTime));
       // dd($this->arrows);
        return view('livewire.smart-form-builder',[
            'paginate'=> $this->arrows                
        ]);
        
    }
}