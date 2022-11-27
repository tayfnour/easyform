<?php
namespace App\MyClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class formBuilder
{
   
    private static $validator;  

    public function __construct(){}

    public static function buildForm($fOps , $formData ,$validator)
    {

       
        self::$validator = $validator;
        
        $pk = $fOps['_pk'];

        $fn = $fOps['_fn'];

        $fields = "";
        // $fields is filled
        

        if (isset($formData['rows'])) {

          $sliding = count($formData['rows'])==1?'':'sliding';

            foreach ($formData["rows"] as $rowId => $row) {

                $fields .= "<div id='{$fn}_{$rowId}' class='row sliding-style sliding'><div style='height: 5px;'><span class='heightAuto'>+</span></div>";

                foreach ($row as $col => $value) {
                    $fields .= self::switchType($fOps, $formData, $col, $rowId);               
                }
                $fields .= "</div>";

               //  if($fOps["fn"]== "invoices")
               //  Log::channel('mht')->info("start of init : " . $fields );
               // if($fOps["fn"]== "invoices" && $col== "invoice_id")
                
            }
           
        }else{
            
            $fields = "<div class='row'><div class='col-md-12'><p class='text-center' style='color:orange' >No Content of fields defined</p></div></div>";
             // dd($fields);
        }

       // 

       $count = isset($formData['rows'])?count($formData['rows']):0;

//[$pk]["form_translate"], $fOps["fn"] , $count ,$tbName

        return self::getFormlayout($fields , $formData , $fOps , $count);
    }


    public static function getFormlayout($fields , $formData ,$fOps , $count)
    {

        $fn = $fOps["_fn"];
        $tb = $fOps["_tbName"];
        $pk = $fOps["_pk"];
       // $tr = $fOps[$pk]['form_translate'];
        $tr = $formData["_fprops"]["description"] ;     //  dd($fields);

        

       // $formName.=" ($count)";

        return "<div class = 'col-12 h-25' style='padding:0px; border-radius:8px 8px 0px 0px'>
                    <div class ='form-header'  style=padding:4px;border-radius: 8px 8px 0px 0px;'>
                        <span wire:click=\"\$emit('setFormAndTableName','{$tb}' , '{$fn}')\" style='cursor:pointer;line-height:30px'>
                        $tr({$fn})
                        </span>
                    </div>                   
                </div>

                <div class = 'col-12 h-50' >
                    <div class='form-body' style='padding:10px'>{$fields}</div>
                </div>

                <div class = 'col-12 h-25' >
                    <div class='form-bottom'  style='background:yellow'></div>
                </div>";   
                        
       }

    public static function switchType($fopts, $formData  ,$col, $rowId)
    {
   
      if(isset($fopts[$col])) {
        switch ($fopts[$col]["inputType"]) {
            case 'text':
                return self::textField($fopts, $formData, $col, $rowId);
                break;
            case 'auto':
                return self::autoField($fopts,  $formData, $col, $rowId);
                break;
            case 'date':
                return self::dateField($fopts, $formData, $col, $rowId);
                break;
            case 'image':
                return self::imageField($fopts, $formData, $col, $rowId);
                break;
            case 'classify':
                return self::imageclassify($fopts, $formData, $col, $rowId);
                break;
            case 'file':
                return self::file();
                break;
            case 'submit':
                return self::submit();
                break;
            default:
                return self::noneField();
                break;
        }
     }  
    }

    public static function textField($fopts, $formData, $col, $rowId)
    {
        $fn = $fopts["_fn"];
        $bs = $fopts[$col]["bootstrap"];
        $key = $fn.'.'.$col.'.'.$rowId;
    
      if(isset(self::$validator[$rowId.'.'.$col])){

        $val = "<span style='color:blue'>value:(". $formData['temps'][$rowId][$col] .")is Error</span><br>";
        
        $val .= preg_replace('/\d+\./',' ',self::$validator[$rowId.'.'.$col][0]);

      }else{
              
            $val='';
      }
       
        return "<div class='$bs mb-1'  >
                    <div class='form-group'>
                        <label for='{$key}'>{$fopts[$col]['arabic_name']}</label>
                        <input type='hidden' wire:model='formData.{$fn}.temps.{$rowId}.{$col}' />
                        <input type='text' wire:blur='hideValidation()'  wire:dirty.class='dertyclass' class='form-control input-gen' id='{$key}' wire:model.debounce.1500ms='formData.{$fn}.rows.{$rowId}.{$col}' />
                        <div style='direction:ltr; text-align:left' >$val</div>
                    </div>
                </div>";
    }

    public static function autoField($fopts, $formData, $col, $rowId)
    {
        $fn = $fopts["_fn"];
        $lookup = $fopts[$col]["lookup"];
        $bs = $fopts[$col]["bootstrap"];
        $key = $fn.'.'.$col.'.'.$rowId;
        $val =  $formData['rows'][$rowId][$col];
        $val1=  isset($formData['autos'][$rowId][$col])?$formData['autos'][$rowId][$col]:'';


    
      if(isset(self::$validator[$rowId.'.'.$col])){
        $err = "<span style='color:blue'>value:(". $formData['temps'][$rowId][$col] .")is Error</span><br>";        
        $err .=preg_replace('/\d+\./',' ',self::$validator[$rowId.'.'.$col][0]);
        $err .= "<div style='direction:ltr; text-align:left' >$err</div>";
      }else{
              
            $err='';
      }

    //   if(isset($fopts["autoDiv"][$rowId][$col]) && !empty($fopts["autoDiv"][$rowId][$col])){
    //        $auto="<div class='auto-complete'>".$fopts["autoDiv"][$rowId][$col]."</div>"; 
    //   }else{
    //        $auto=null;
    //   }

    //$model =

      $url= "'http://127.0.0.1:8000/api/auto/$lookup/{$fn}__{$rowId}__{$col}/'";
       
        return "<div class='$bs mb-1'
                x-data=\"{auto_data:null,
                getAutoData(word){       
                 fetch($url+word)
                .then((response) => response.text())
                .then((text) => this.auto_data = text);}}\" >            
                        <div class='form-group'>
                            <label for='{$key}'>{$fopts[$col]['arabic_name']}</label>
                            <input type='hidden' wire:model='formData.{$fn}.rows.{$rowId}.{$col}' />
                            <input value='{$val1}' x-ref='{$fn}__{$rowId}__{$col}' class='form-control input-gen;' type='text'  @focus='getAutoData(\$el.value)' @input='getAutoData(\$el.value)' >
                            $err 
                            <div  class='auto-complete' x-html='auto_data'></div>
                        </div>
                </div>";
    } 

    public static function dateField($fopts, $formData, $col, $rowId){

        $fn = $fopts["_fn"];
        $bs = $fopts[$col]["bootstrap"];
        $key = $fn.'.'.$col.'.'.$rowId;
       // $widget= empty($fopts[$col]['widget'])?'':$fopts[$col]['widget'];
    
      if(isset(self::$validator[$rowId.'.'.$col])){

        $val = "<span style='color:blue'>value:(". $formData['temps'][$rowId][$col] .")is Error</span><br>";
        
        $val .=preg_replace('/\d+\./',' ',self::$validator[$rowId.'.'.$col][0]);

      }else{
              
            $val='';
      }
       
        return "<div class='$bs mb-1'  >
                    <div class='form-group'>
                        <label for='{$key}'>{$fopts[$col]['arabic_name']}</label>
                        <input type='hidden' wire:model='formData.{$fn}.temps.{$rowId}.{$col}' />
                        <input type='text' wire:blur='hideValidation()'  wire:dirty.class='dertyclass' class='form-control input-gen datepicker' id='{$key}' wire:model.debounce.1500ms='formData.{$fn}.rows.{$rowId}.{$col}' />
                        <div style='direction:ltr; text-align:left' >$val</div>
                    </div>
                </div>";

    }  

    public static function imageField($fopts, $formData, $col, $rowId){

        $fn = $fopts["_fn"];
        $bs = $fopts[$col]["bootstrap"];
        $lb = $fopts[$col]['arabic_name'];
        $val =  $formData['rows'][$rowId][$col];
        $model =$fn."__".$rowId."__".$col;

       if(!empty($val)){
         $img = DB::table('imageinfo')->where('img_id',$val)->first();    
         $path ="http://localhost/global_images/{$img->path}";
         $desc = $img->desc;
         $val =  $img->img_id;
         $action="<input type='button' class='btn btn-secondary'  @click='\$wire.deleteImage(\"$model\" , $val)' value='Delete Image' />";
         }else if (isset($formData["_photos"][$rowId][$col])){
        $desc='';
        $path = $formData["_photos"][$rowId][$col]->temporaryUrl();
        $action ="<input type='button' class='btn btn-primary'  @click='\.saveImage(\"$model\" ,\$refs.{$model}_desc.value,\$refs.{$model}_id.value)' value='Save Image' />";
         }else{
        $path='http://localhost/global_images/no_image.jpg';
        $desc='no_image';
        $action=null;
      }
       // DB::table

      return "<div class='$bs' x-data=''>
        <div style='position: relative'>
            <label >{$lb}</label>
            <input id='inp_1000' x-ref='picOne' type='file' wire:model='formData.{$fn}._photos.{$rowId}.{$col}' style='display:none'>
            <input type='hidden' x-ref='{$model}_id'  class='form-control' value='$val'>
            <input type='text' x-ref='{$model}_desc'  class='form-control' value='$desc'>
            <span @click='\$refs.picOne.click()'
             style='left: 2px;margin-bottom:-28px;background-color:white;float:left ;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:-33px;  z-index: 100;'>Upload
            </span>
            <div class='p-1 text-center'>
              <img class='img-thumbnail' src='$path' style='height:200px' > 
            </div>
            <div class='p-1 text-center'>
            $action
            </div>             
        </div>
    </div>";

    } 

    public static function imageclassify($fopts, $formData, $col, $rowId){ //type int

        $fn = $fopts["_fn"];
        $bs = $fopts[$col]["bootstrap"];
        $lb = $fopts[$col]["arabic_name"];
        $val =  $formData['rows'][$rowId][$col];
        $model ="$fn"."__".$rowId."__"."$col";
        $widgets="";
        $supinpSearch="<input type='text' class='form-control' @input='getSupplierNames(\$el.value)' />";
        $suplier= "formData.{$fn}._photos.{$rowId}.{$col}.suplier";
        $url= "'http://127.0.0.1:8000/api/supplierNames/'";
        // $supList ="";
        //$supliers=DB::table('suppliers')->limit(5)->get();
        // foreach($supliers as $sup){
        //     $supList .= "<option style='cursor:pointer' @click='\$refs.suplier_name.value=\$el.text;\$refs.suplier_id.value=\$el.value;supVisible = false' value='{$sup->supplier_id}'>{$sup->name}</option>";
        // }
      
       // $action="<input type='button' class='btn btn-secondary'  @click='\$wire.saveImage(\"$model\" , $val)' value='Delete Image' />";
     
       if (isset($fopts["_photos"][$rowId][$col])){
        $path = $fopts["_photos"][$rowId][$col]->temporaryUrl();     
        $action="<input type='button' class='btn btn-primary'  @click=\"saveimage('$model',$val)\" value='Save Image' />";
        $widgets="<div class='col-md-3'>
                  <div class='card' x-data= \"{
                     supVisible  : false,
                     supNames:'<li>insert word heree</li>',
                     saveimage(model , val){
                        \$wire.saveClassifyImage(model ,\$refs.suplier_id.value ,\$refs.price.value , \$refs.packing.value , \$refs.desc.value , val);
                    },                    
                    getSupplierNames(word){
                        fetch($url+word)
                        .then((response) => response.text())
                        .then((text) => this.supNames = text);     
                    } 
                    }\" >
                    <div class='card-body' @click.away='supVisible = false'>
                            <div  style='position:relative' >
                                <input @focus='supVisible=true' type='text' x-ref='suplier_id'  class='form-control' placeholder='suplier'>
                                <div x-show='supVisible' style='border:1px solid #ccc;background-color:white;position:absolute;z-index:100'><div>$supinpSearch</div><ul x-html='supNames'></ul></div>
                                <input type='text' x-ref='suplier_name'  class='form-control'>                                
                                <div class='input-group'>
                                <span class='input-group-text'>price</span>
                                <input type='text' x-ref='price' class='form-control' placeholder='price'  >
                                <span class='input-group-text'>packing</span>
                                <input type='text' x-ref='packing'  class='form-control'  placeholder='packing'  >
                                </div>
                                <textarea x-ref='desc' class='form-control' placeholder='description'></textarea>
                            </div>
                            <div>
                                <img src='$path' class='img-thumbnail' style='height:200px' >
                            </div>                      
                            <div class='p-1 text-center'>
                            $action
                            </div>
                     </div>
                   </div>
               </div>";
       }

       if(!empty($val)){

            $imgs = DB::table('imageinfo')
            ->join('suppliers', 'imageinfo.supplier_id', '=', 'suppliers.supplier_id')
            ->where('imageinfo.img_ref',$val)->orderBy("img_id","desc")->get(); 
         
            foreach($imgs as $img){
                $path ="http://localhost/global_images/{$img->path}";
                $desc = $img->desc;
                $img_id =  $img->img_id;
                $price = $img->price;
                $packing = $img->packing;
                $suplier = $img->supplier_id;
                $sup_name=$img->name;
               
                $action1 ="<input  type='button' class='btn btn-warning'  wire:click='deleteImage(\"$model\",$img_id,1)' value='Delete Image' />
                <input @click='getfieldValues();' type='button' class='btn btn-primary'  value='Update Image' />
                <input x-ref='img_id' type='hidden' value='$img_id' />";

                     $widgets.="<div class='col-md-3'>                   
                            <div class='card' x-data= \"{
                                supVisible : false , 
                                suplier_name:null ,
                                supNames:'<li>insert word here</li>' ,
                                word:null ,
                                getfieldValues(){
                                 \$wire.updateImageInfo(\$refs.suplier_id.value , \$refs.suplier_name.value , \$refs.price.value, \$refs.packing.value , \$refs.desc.value , \$refs.img_id.value);
                                },
                                getSupplierNames(word){
                                    fetch($url+word)
                                    .then((response) => response.text())
                                    .then((text) => this.supNames = text);     
                                }                              
                                
                                }\" >
                                <div class='card-body' @click.away='supVisible = false'>
                                            <div>
                                                <input @focus='supVisible=true' type='text' x-ref='suplier_id' class='form-control' value='{$suplier}'>
                                                <div x-show='supVisible' style='border:1px solid #ccc;background-color:white;position:absolute;z-index:100'><div>$supinpSearch</div><ul x-html='supNames'></ul></div>
                                                <input type='text' x-ref='suplier_name'  class='form-control' value='{$sup_name}'>                                               
                                                <div class='input-group'>
                                                <span class='input-group-text'>price</span>
                                                <input type='text' x-ref='price' class='form-control ' value='{$price}'  placeholder='price'  >
                                                <span class='input-group-text'>packing</span>
                                                <input type='text' x-ref='packing'  class='form-control ' value='{$packing}'  placeholder='packing'  >
                                                </div>
                                                <textarea x-ref='desc' class='form-control' style='text-align:right;direction:rtl' >{$desc}</textarea>
                                            </div>
                                            <div class='p-1 text-center'>
                                                <img src='$path' class='img-thumbnail' style='height:200px' >
                                            </div>                                            
                                            <div class='p-1 text-center'>
                                            $action1
                                            </div>
                                </div>
                            </div>                                                 
                        </div>";
            }        
       }


       
       return "<div class='$bs' x-data=''>
                <div style='position: relative'>
                    <label >{$lb}</label>
                    <input id='inp_1000' x-ref='picOne' type='file' wire:model='formData.{$fn}._photos.{$rowId}.{$col}' style='display:none'>
                    <input type='text' wire:model='formData.{$fn}.rows.{$rowId}.{$col}'  class='form-control'>
                    <span @click='\$refs.picOne.click()'
                    style='left: 2px;margin-bottom:-28px;background-color:white;float:left ;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:-33px;  z-index: 100;'>Upload
                    </span>                        
                    <div class='mb-2 border p1' style='display:flex;width:100%;overflow-x:auto;'>                   
                    $widgets
                    </div>                                 
                </div>
            </div>";    

    }    

    public static function noneField()
    {
        return null;
    }

}
