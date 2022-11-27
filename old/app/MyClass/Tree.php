<?php
namespace App\MyClass;
//use App\Models\catagory;
use DB;

class Tree { 

function __constructor(){}

 function createDynamicTree($tbName , $filter = null){
            
      //  $start = microtime(true);    

            $rootArr =[]; 
            $levels =[];   

        // without filter;
        if( $filter == null ) {            
            $roots= DB::table($tbName)->where ("parent_id" , 0)->orderby('list_order')->get();
            $childs=DB::table($tbName)->where ("parent_id" ,">", 0)->orderby('list_order')->get();

        }else{ 
            // if filter tree
            // with filter select only  branche group
            $roots= DB::select("select * from {$tbName}  where parent_id= 0 and code = {$filter}");
            $rowcount = [$filter];
            $code_arr = [];
            

            while ( count($rowcount) != 0){       

                $arr = implode ("," , $rowcount);

                    $result = DB::select("select code from {$tbName}  where parent_id in ({$arr})");       

                $rowcount =[];

                if( $result!==null ){

                        foreach ( $result as $rs){

                            $rowcount[] =  $rs->code;
                        }

                }

                    $code_arr = array_merge($code_arr ,  $rowcount);
            }

            $str_code = implode ("," , $code_arr);

            if (empty($str_code)){

                $childs= DB::select("select * from {$tbName} where code in ({$filter})");

            }else{

                $childs= DB::select("select * from {$tbName} where code in ({$str_code})");
            }
            
        }
        

        
        // dd($childs);

            $decode = 1;

            foreach($roots as $root){

                $levels[0][] =  ["parent" =>$root->parent_id ,"name" => $root->name, "childs" =>  $root->code ,"is_end"=> $root->is_end , "decode" => strval($root->code) , "series" => $root->name , "list_order" =>  $root->list_order] ;
                $decode++;
            //                         parent of level                                parent of next level
            }      
            
        
            
            // flatren  multi dimention arr 
            $i = 0;  // Leveles counter
              while  ($i >= 0){
                foreach($childs as $child){
                    foreach ($levels[$i] as $lev){                
                        if($lev["childs"] ==  $child->parent_id ) {
                          $levels[$i+1][] =  [
                               "parent" =>$child->parent_id ,
                               "name" => $child->name,
                               "childs" =>  $child->code ,
                               "is_end"=> $child->is_end ,
                               "decode" =>strval($child->code) ."_".  $lev["decode"]  , 
                               "series" => $lev["series"]."|".$child->name , 
                               "list_order" =>  $child->list_order 
                               ];
                        }

                    }       
                }      
                
                if (!isset($levels[$i+1]) ){

                    $i=-1;  //out of loop

                } else{

                        $i++;
                    }
        } 

        // echo "<pre>";
        //  print_r($levels);
        // dd();


        // collecting tree from childs to   parent
        $lev=[];

            $le = count($levels)-1; 

            for  ($u = $le ; $u >= 0 ; $u--){
                // echo $u;
                    for ($y = 0 ; $y < count($levels[$u]) ; $y++){

                        $code = $levels[$u][$y]["childs"];
                        $parent = $levels[$u][$y]["parent"];
                        $is_end = $levels[$u][$y]["is_end"];
                        $series = $levels[$u][$y]["series"];
                        $decode = $levels[$u][$y]["decode"];
                        $list_order = $levels[$u][$y]["list_order"];
                        $checked =  $is_end?"checked":'';

                        $crud =  "<div id='div-{$code}' class='crud_container' ></div>";                    
                    
                        if (isset($lev[$u+1][$levels[$u][$y]['childs']])){
                            
                          $lev[$u][$levels[$u][$y]['parent']][] ="<li  class='tree_container' data-series='{$series}' data-decode='{$decode}'  ><span class='decode'>{$decode}</span><span  id='li-{$code}' class='caret' data-decode='{$decode}' data-order='{$list_order}'  data-level='{$u}-{$le}' wire:click.debounce.500ms='setGlobalVar({$code} ,{$parent},{$is_end},{$list_order})' >".$levels[$u][$y]['name']."</span><input class='is_end' type='checkbox' onclick='return false;' {$checked}><ul id='ul-{$code}' class='nested' >".implode(" " , $lev[$u+1][$levels[$u][$y]['childs']])."</ul></li>";
                        
                        }else{
            
                          $lev[$u][$levels[$u][$y]['parent']][] ="<li  class='tree_container' data-series='{$series}' data-decode='{$decode}'  data-level='{$u}-{$le}' ><span class='decode'>{$decode}</span><span id='li-{$code}' class='caret' data-decode='{$decode}' data-order='{$list_order}' data-level='{$u}-{$le}' wire:click.debounce.500ms='setGlobalVar({$code}  , {$parent} , {$is_end} , {$list_order})' >".$levels[$u][$y]['name']."</span><input class='is_end' type='checkbox' onclick='return false;' {$checked}></li>";
                    
                        }
                    
                    
                    }      
                        
            }

      // dd( (microtime(true) - $start)*1000 );
        
      
    return  "<ul class='tree' id='myUL' style='direction:rtl'>".implode(" ",$lev[0][0])."</ul>";

 }

 function createSimpleTree($tbName , $colName  , $row_id  , $filter = null){
            
    //  $start = microtime(true);    

          $rootArr =[]; 
          $levels =[];   

      // without filter;
      if( $filter == null ) {            
          $roots= DB::table($tbName)->where ("parent_id" , 0)->orderby('list_order')->get();
          $childs=DB::table($tbName)->where ("parent_id" ,">", 0)->orderby('list_order')->get();

      }else{ 
          // if filter tree
          // with filter select only  branche group
          $roots= DB::select("select * from {$tbName}  where parent_id= 0 and code = {$filter}");
          $rowcount = [$filter];
          $code_arr = [];
          

          while ( count($rowcount) != 0){       

              $arr = implode ("," , $rowcount);

                  $result = DB::select("select code from {$tbName}  where parent_id in ({$arr})");       

              $rowcount =[];

              if( $result!==null ){

                      foreach ( $result as $rs){

                          $rowcount[] =  $rs->code;
                      }

              }

                  $code_arr = array_merge($code_arr ,  $rowcount);
          }

          $str_code = implode ("," , $code_arr);

          if (empty($str_code)){

              $childs= DB::select("select * from {$tbName} where code in ({$filter})");

          }else{

              $childs= DB::select("select * from {$tbName} where code in ({$str_code})");
          }
          
      }
      

      
      // dd($childs);

          $decode = 1;

          foreach($roots as $root){

              $levels[0][] =  ["parent" =>$root->parent_id ,"name" => $root->name, "childs" =>  $root->code ,"is_end"=> $root->is_end , "decode" => strval($root->code) , "series" => $root->name , "list_order" =>  $root->list_order] ;
              $decode++;
          //                         parent of level                                parent of next level
          }      
          
      
          
          // flatren  multi dimention arr 
          $i = 0;  // Leveles counter
            while  ($i >= 0){
              foreach($childs as $child){
                  foreach ($levels[$i] as $lev){                
                      if($lev["childs"] ==  $child->parent_id ) {
                        $levels[$i+1][] =  [
                             "parent" =>$child->parent_id ,
                             "name" => $child->name,
                             "childs" =>  $child->code ,
                             "is_end"=> $child->is_end ,
                             "decode" =>strval($child->code) ."_".  $lev["decode"]  , 
                             "series" => $lev["series"]."|".$child->name , 
                             "list_order" =>  $child->list_order 
                             ];
                      }

                  }       
              }      
              
              if (!isset($levels[$i+1]) ){

                  $i=-1;  //out of loop

              } else{

                      $i++;
                  }
      } 

      // echo "<pre>";
      //  print_r($levels);
      // dd();


      // collecting tree from childs to   parent
      $lev=[];

          $le = count($levels)-1; 

          for  ($u = $le ; $u >= 0 ; $u--){
              // echo $u;
                  for ($y = 0 ; $y < count($levels[$u]) ; $y++){

                      $code = $levels[$u][$y]["childs"];
                      $parent = $levels[$u][$y]["parent"];
                      $is_end = $levels[$u][$y]["is_end"];
                      $series = $levels[$u][$y]["series"];
                      $decode = $levels[$u][$y]["decode"];
                      $list_order = $levels[$u][$y]["list_order"];
                      $checked =  $is_end?"checked":'';
                      $name =$levels[$u][$y]['name'];
                            
                  
                      if (isset($lev[$u+1][$levels[$u][$y]['childs']])){
                          
                        //$lev[$u][$levels[$u][$y]['parent']][] ="<li  class='tree_container' data-series='{$series}' data-decode='{$decode}'  ><span class='decode'>{$decode}</span><span  id='li-{$code}' class='caret' data-decode='{$decode}' data-order='{$list_order}'  data-level='{$u}-{$le}' wire:click.debounce.500ms='setGlobalVar({$code} ,{$parent},{$is_end},{$list_order})' >".$levels[$u][$y]['name']."</span><input class='is_end' type='checkbox' onclick='return false;' {$checked}><ul id='ul-{$code}' class='nested' >".implode(" " , $lev[$u+1][$levels[$u][$y]['childs']])."</ul></li>";
                      
                        $lev[$u][$levels[$u][$y]['parent']][] ="<li  class='tree_li'><span  wire:click.debounce.500ms='setGlobalVar({$code} ,{$row_id},\"{$name}\",\"{$colName}\")' >". $name ."</span><ul  class='nested1' >".implode(" " , $lev[$u+1][$levels[$u][$y]['childs']])."</ul></li>";
                    
                    }else{
          
                       // $lev[$u][$levels[$u][$y]['parent']][] ="<li  class='tree_container' data-series='{$series}' data-decode='{$decode}'  data-level='{$u}-{$le}' ><span class='decode'>{$decode}</span><span id='li-{$code}' class='caret' data-decode='{$decode}' data-order='{$list_order}' data-level='{$u}-{$le}' wire:click.debounce.500ms='setGlobalVar({$code}  , {$parent} , {$is_end} , {$list_order})' >".$levels[$u][$y]['name']."</span><input class='is_end' type='checkbox' onclick='return false;' {$checked}></li>";
                        $lev[$u][$levels[$u][$y]['parent']][] ="<li  class='tree_li'><span  wire:click.debounce.500ms='setGlobalVar({$code} ,{$row_id},\"{$name}\",\"{$colName}\")' >". $name ."</span></li>";
                    
                      }
                  
                  
                  }      
                      
          }

    // dd( (microtime(true) - $start)*1000 );
      
    
  return  "<ul class='tree' id='myUL' style='direction:rtl'>".implode(" ",$lev[0][0])."</ul>";

}



}