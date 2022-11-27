<?php
namespace App\MyClass;


 class Vali {

     function __constructor(){}

    static  function  validate($coloptions , $f) {
      
         $validation_arr=[];
         $propArr =[];
       
        foreach( $coloptions as $op){

            if($op["empty_Vali"]==1){
                $propArr[] = "required";
            }

            if($op["minCharachter"]>1){
                $propArr[] = "min:".$op["minCharachter"];
            }

            if($op["maxCharachter"]>1){
                $propArr[] = "max:".$op["maxCharachter"];
            }
             
            if($op["email_Vali"]==1){
                $propArr[] = "email";
            }



          
            if(count($propArr)==1)
               $validation_arr["{$f}.".$op["colName"]]= $propArr[0];
             elseif (count($propArr)>1)
               $validation_arr["{$f}.".$op["colName"]]= implode("|" ,$propArr);

            $propArr = [];

        }
    
        return  $validation_arr;
    }
}   
