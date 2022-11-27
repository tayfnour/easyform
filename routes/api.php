<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products/{id}', function ($id) {

    $customer = DB::table("customers")->insert(["name"=>"customer_name_1"]);

    return response(  $customer , 200 , ["1"=>"hello"]);
    
});

Route::get('/supplierNames/{word?}', function ($word=null) {
    
    if(!empty($word)){
        $strAuto="";
        $suppliers = DB::table("suppliers")->where("name","like","%".$word."%")->get();

        if($suppliers){
        foreach($suppliers as $supplier){
            $strAuto.="<li style='cursor:pointer' data-supName='$supplier->name' value='$supplier->supplier_id' @click='\$refs.suplier_name.value=\$el.textContent;\$refs.suplier_id.value=\$el.value;supVisible = false'>".$supplier->name."</li>";
        }        
          return $strAuto;   

        }else{
            return "<li  class='menu_li'>no data</li>";
        }
        }else{
            return "<li  class='menu_li'>insert name</li>";
        }


});

Route::get('/auto/{tb}/{model}/{word?}', function ($lookup , $model , $word=null) {

if(!empty($word)){
    $strAuto="";
    $lkupArray = explode("|",$lookup);
    $auto_data = DB::table($lkupArray[0])->select([$lkupArray[1],$lkupArray[2]])->where($lkupArray[2] ,"Like",'%'.$word.'%')->get();
    foreach ($auto_data as $key => $value) {
        $k1 = $value->{$lkupArray[1]}; // always id
        $v2 = $value->{$lkupArray[2]}; // always name
        $strAuto .= "<li @click='\$refs.$model.value=\"$v2\";auto_data=null;\$wire.setAutoFeild(\"$model\",$k1)' class='menu_li' data-val='$k1' >{$v2}</li>";
      
     }
        return $strAuto;   
    }else{
        return "<li  class='menu_li'>no data</li>";
    }    
});