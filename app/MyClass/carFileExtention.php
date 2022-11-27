<?php
namespace App\MyClass;
use DB;

trait carFileExtention{

    public $msgs = [];
    public $submit_visible;
    public $baseRef;
    public $targetRefCol;
    public $autoArr;
    public $billResult=[];

    public function calculateProductCount($product_id)
    {
        $res_purch = DB::table("bills")->where("product_id", $product_id)->sum("quantity");
        $res_sales = DB::table("invoices")->where("product_id", $product_id)->sum("quantity");
        return $res_purch - $res_sales;
    }

    public function getRandomStr($len)
    {
        return bin2hex(random_bytes($len));
    }

    

    public function getAutoIncreamentName($tb_name)
    {
        $result = DB::select(DB::raw("SHOW KEYS FROM {$tb_name} WHERE Key_name = 'PRIMARY'"));
        return $result[0]->Column_name;
    }

    public function  savePhoto($row_id , $colName){

        $filename = $this->photos[$row_id][$colName]->store( "simpleproducts", "global_images");
         
       // dd($filename);
          
        $id =  DB::table("up_images")->insertGetId([           
                "path" => "simpleproducts",
                "file_name" => $filename,
                "description" => $this->photosDesc[$row_id]["pic"],
                "timestamps" => date("Y-m-d H:i:s"),
            ]);
    
         $this->colArr[$row_id][$colName]= $id ;
        // $this->colArrPar[$row_id][$colName]= $id ;
         $this->saveRowOnUpdate($row_id, $colName);
       
        }
    
        public function  deletePhoto($row_id , $colName){
    
          $id =  $this->colArr[$row_id][$colName];
          
          
          $id =  DB::table("up_images")->where("id" , $id)->delete();
    
          $this->colArr[$row_id][$colName] = null;
          $this->colArrPar[$row_id][$colName] = null;
        
        
          $this->saveRowOnUpdate($row_id, $colName);
    
          
    
        //   dd( $id );
        }

      //image widget
      public function buildImageWidget($row_id, $fn , $colName){

        //ddd($row_id, $fn , $colName);
      
        $str="<div wire:key='$row_id' >";

        $id = $this->colArr[$row_id][$colName];     

        $res =  DB::table("up_images")->where("id" , $id)->first();

        if (isset($res->id)){

            $str .= "<div class='text-center' style='border:1px solid ddd; height:200px;padding:2px;margin-top:3px ; display: table-cell;
            vertical-align: middle;'>";   
            $str .= "<img src='http://localhost/global_images/{$res->file_name}' style='max-width:250px;width:100%;max-height:150px'>"; 
            $str .= "<div wire:click='deletePhoto($row_id , \"$colName\")' class='text-center' style='cursor:pointer'>Delete</div>"; 
            $str .= "</div></div>";

            if(isset($this->colArrPar[$row_id][$colName])){
                $this->colArrPar[$row_id][$colName]= $res->description;
            }
          
            unset($this->photos[$row_id]);

            return $str;
            
        }else if (isset($this->photos[$row_id][$colName])){

            $str .= "<div class='text-center' style='height:200px;padding:2px;margin-top:3px'>";  
            $str .= "<img src='{$this->photos[$row_id][$colName]->temporaryUrl()}' style='max-width:250px;width:100%;max-height:150px'>"; 
            $str .= "<div><input wire:model ='photosDesc.{$row_id}.pic'  class='form-control'  type='text'    ></div>";   
            $str .= "<div wire:click='savePhoto($row_id , \"$colName\")' class='text-center' style='cursor:pointer'>Save</div>"; 
            return $str."</div></div>";
        }else{            
            return "<div class='col-2'>No Image Upload</div>";
        }  
         
    } 

 
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

    public function addDefaultRowButton($row_id , $bTitle){
       
        $this->msgs[]="Append button to Areaa";

        $str = "<div class='col-2' >
                <button wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'>{$bTitle}</button>
                </div>";
        return $str; 
    }

    public function linkDriverWithAssetCar(){

        $this->msgs[]="Link Driver with Asset Car";


        $this->floatForm="cartypeclasses";

    }

    public function filterByPlate(){
        $this->resetPage("carFileLinkDriver");
        $this->formsLoop();
    }

    public function excuteFunctionEndOfForm($row_id, $action)
    {

        if($action == "filterByCarPlate"){

            $str ="
            <div class='col-2' >
            <input wire:click='filterByPlate' type='button' class='btn btn-primary wd100'  value='بحث عن مركبة'  >
            </div>
            <div class='col-2' >
            <input wire:model.lazy='searchCar' type='text' class='form-control wd100' placeholder='رقم اللوحة'>
            </div>
           
            ";

            return $str;

        }   

        if($action == "buttonAddNewCarToDriver"){

            $str = "<div class='col-2' >
                    <input wire:click='createNewFormRow(null,\"carfiles\")' type='button' class='btn btn-primary wd100'  value='إضافة مركبة جديدة'  >
                    </div>";
            $str .= "<div class='col-2' >
                    <input wire:click='linkDriverWithAssetCar({$row_id})' type='button' class='btn btn-primary wd100'  value='ربط مركبة مع سائق'  >
                    </div>
                    ";

            return $str;
        }

        if($action == "addNewCarFile"){

            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white;z-index:1'>
            <div class='row' >
                <div class='col-2' >
                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value='اضافة عربة جديد'  >
                </div>
                <div class='col-2' >
                <input  wire:click='delRow({$row_id})' type='button' class='btn btn-info wd100'  value='حذف عربة '  >
                </div>
            </div>
            </div>";
            return $str;
        }

        
        if($action == "addcartypeclasse"){

            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white;z-index:1'>
            <div class='row' >
                <div class='col-2' >
                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value='اضافة تصنيف نوع المركبة'  >
                </div>
                <div class='col-2' >
                <input  wire:click='delRow({$row_id})' type='button' class='btn btn-info wd100'  value='حذف عربة '  >
                </div>
            </div>
            </div>";
            return $str;
        }

        if($action == "addcartype"){

            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white;z-index:1'>
            <div class='row' >
                <div class='col-2' >
                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value='اضافة نوع المركبة'  >
                </div>
                <div class='col-2' >
                <input  wire:click='delRow({$row_id})' type='button' class='btn btn-info wd100'  value='حذف نوع مركبة '  >
                </div>
            </div>
            </div>";
            return $str;
        }

        if($action == "addNewManufacture"){

            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white;z-index:1'>
            <div class='row' >
                <div class='col-2' >
                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value='اضافة صانع مركبة'  >
                </div>
                <div class='col-2' >
                <input  wire:click='delRow({$row_id})' type='button' class='btn btn-info wd100'  value='حذف صانع مركبة '  >
                </div>
            </div>
            </div>";
            return $str;
        }

        if($action == "addCarModel"){

            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white;z-index:1'>
            <div class='row' >
                <div class='col-2' >
                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value='اضافة موديل مركبة'  >
                </div>
                <div class='col-2' >
                <input  wire:click='delRow({$row_id})' type='button' class='btn btn-info wd100'  value='حذف موديل  مركبة '  >
                </div>
            </div>
            </div>";
            return $str;
        }

        
        if($action == "addAdditionals"){

            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white;z-index:1'>
            <div class='row' >
                <div class='col-2' >
                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value='اضافة ملحق مركبة'  >
                </div>
                <div class='col-2' >
                <input  wire:click='delRow({$row_id})' type='button' class='btn btn-info wd100'  value='حذف ملحق  مركبة '  >
                </div>
            </div>
            </div>";
            return $str;
        }

        if($action == "addFuelType"){

            $str = "<div class='col-2' >
                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value='اضافة نوع وقود'  >
                </div>
                <div class='col-2' >
                <input  wire:click='delRow({$row_id})' type='button' class='btn btn-info wd100'  value='حذف نوع مركبة'  >
                </div>
            ";
            return $str;
        }
        


        


    
}


}