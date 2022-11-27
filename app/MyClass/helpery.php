<?php

namespace App\MyClass;
use DB;
use Illuminate\Support\Facades\Schema;

class Helpery{

static function getTableNames ($dbName){

        $arr = [];
    
        $tables = DB::select('SHOW TABLES');  

        // return  $tables;

        foreach ($tables as $value) {

            $op = "Tables_in_".$dbName;           
        
           // $arr .= "<option class='tbName'  >" .  $value->{$op} . "</option>";
            $arr[]= $value->{$op};
        }
        return $arr;
    }

static function getColNames ($tbName){
    //$res=[];
   return $columns = Schema::getColumnListing($tbName);
}  

static function setConfig ($k , $val){

    $res = DB::table('config')->where("mkey" , $k)->get();

    if($res->count() >0 ){
    
        DB::table('config')->where("mkey" , $k)->update(["mval" => $val]);

    }else{

        DB::table('config')->insert([  "mkey" =>  $k ,"mval" => $val]);
    }   

}

static function getConfig ($k){      
     
    $res = DB::table('config')->where("mkey" ,$k)->first();
    if($res)
        return $res->mval;
    else
        return false;
}    


}