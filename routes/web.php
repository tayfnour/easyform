<?php

use App\MyClass\Tree;
use App\Models\catagory;
use App\MyClass\Helpery;
use App\Http\Livewire\CatTree;
use App\Http\Livewire\CartComponent;
use App\Http\Livewire\HomeComponent;
use App\Http\Livewire\ShopComponent;
use App\Http\Livewire\ArticleManager;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\DetailComponent;
use Illuminate\Http\Request;

use App\Http\Livewire\DbManageComponent;

use App\Http\Livewire\HtmlEditorComponent;
use App\Http\Livewire\TreeManagerComponent;




Route::get('editorExpress/{comp_name?}/',function($comp_name ='smart-form-builder'){   

      //dd($comp_name);
      //$comp_name = $comp_name === null  ?  : $comp_name;

      //continu;
  
      $compFeature = json_decode (Helpery::getConfig("feature_components_sys") , true);
    
      return view('layouts.design-platform', 
      [
          "compNames" => explode(",",$compFeature["comp_names"]),
          "compName" => $comp_name,
          "js" => Helpery::getConfig("{$comp_name}_js"),
          "css" => Helpery::getConfig("{$comp_name}_css")        
      ]);
  
  })->setDefaults(['$comp_name' => 'he-init']);

Route::get('/all_articles',function () {

    $str = "";
    $results =  DB::table("article")->where("id",">" , 130)->where("id" , "<=",160)->get();

    foreach( $results as $res){      
        $str.= $res->article ."\n\n"; 
    }
    return "<textarea>".$str."</textarea>";

});


Route::get('/rcr' , function(){

   $treeStr="<ul>"; 
   $treearr=[];
   $strArr=[];

  $opCount=0;

    $tbCount =  DB::table("plans")->count();

    

   $res = DB::table("plans")->where("parent_id" , 0)->get(); 

    foreach($res  as $rs){        
        $treearr[0][0][$rs->code]= "{$rs->name}";
        $opCount++;  
    }

  // ($treearr);

 $lv = 0;

   while ($opCount < $tbCount) { 

    //while ($lv < 5) {    
  
    foreach($treearr[$lv] as $k => $v ){
      
     foreach ($v as $k1 => $v1 ){        
        $res = DB::table("plans")->where("parent_id" , $k1)->get();
        if($res->count()>0){
        foreach ($res as $rs){           
            $treearr[$lv+1][$rs->parent_id][$rs->code] = "{$rs->name}";
            $opCount++;
            }
        }
     }
   }
     $lv++;
  }

  $str= "<ul>";


 // return($treearr);


  //for ($lve = 0 ; $lve < count($treearr) ; $i++){


//     $str.="<li><ul>";


      
      foreach($treearr[5] as $k => $v) { 
           //dd($k,$v) ;      

          foreach ($v as $k1 => $v1 ){
               $strArr[$k][] = "<li>$v1</li>";
             }
       }


    $strArr1=[];

       foreach($treearr[4] as $k => $v) { 
        //dd($k,$v) ;      

        foreach ($v as $k1 => $v1 ){

           // dd($strArr);

             if(isset($strArr[$k1])){

                $str =implode(" " ,$strArr[$k1]);

                $strArr1[$k][] = "<li>$v1<ul>$str</ul></li>";                 
             }else{
                $strArr1[$k][] = "<li>$v1</li>";
             }
            

           }
     }

     $strArr2=[];

     foreach($treearr[3] as $k => $v) { 
        //dd($k,$v) ;      

        foreach ($v as $k1 => $v1 ){

           // dd($strArr);

             if(isset($strArr1[$k1])){

                $str =implode(" " ,$strArr1[$k1]);

                $strArr2[$k][] = "<li>$v1<ul>$str</ul></li>";                 
             }else{
                $strArr2[$k][] = "<li>$v1</li>";
             }
            

           }
     }

     $strArr3=[];

     foreach($treearr[2] as $k => $v) { 
        //dd($k,$v) ;      

        foreach ($v as $k1 => $v1 ){

           // dd($strArr);

             if(isset($strArr2[$k1])){

                $str =implode(" " ,$strArr2[$k1]);

                $strArr3[$k][] = "<li>$v1<ul>$str</ul></li>";                 
             }else{
                $strArr3[$k][] = "<li>$v1</li>";
             }
            

           }
     }

     $strArr4=[];

     foreach($treearr[1] as $k => $v) { 
        //dd($k,$v) ;      

        foreach ($v as $k1 => $v1 ){

           // dd($strArr);

             if(isset($strArr3[$k1])){

                $str =implode(" " ,$strArr3[$k1]);

                $strArr4[$k][] = "<li>$v1<ul>$str</ul></li>";                 
             }else{
                $strArr4[$k][] = "<li>$v1</li>";
             }
            

           }
     }

     $strArr5=[];

     foreach($treearr[0] as $k => $v) { 
        //dd($k,$v) ;      

        foreach ($v as $k1 => $v1 ){

           // dd($strArr);

             if(isset($strArr4[$k1])){

                $str =implode(" " ,$strArr4[$k1]);

                $strArr5[$k][] = "<li>$v1<ul>$str</ul></li>";                 
             }else{
                $strArr5[$k][] = "<li>$v1</li>";
             }
            

           }
     }


//    //  return $strArr;

//        $strArr1=[];

//        foreach($strArr as $k3 => $val3) { 

//         if(isset($treearr[1][$k3])){

//          foreach ($treearr[1][$k3] as $k4 => $val4){
           
//               $strArr1[$k4] .= "<li>".$val4."</li>";
              
    //    }

    //    $strArr1 [$k4] = $val3 . "<ul>" .  $strArr1 [$k4];
    // }
        
    //          // dd("<li>".$val3."</li>");


    //    }


   
     // $str.="</li>";
 // }


 $i=0;
 $str="";
 
//  foreach($treearr as $k => $v){

//     // 1 - >5
  
//     foreach($v as $k1 => $v1 ){

//         // all parent
        
//         //  echo $k1 ."-";

//        // dd($k1 , $v1 );
        
//         foreach ($v1 as $k2 => $v2){

//             $str ="<li>$v2</li>";
            
//             if (isset($treearr[$k+1])){

//                 $str.="<ul>".$treearr[$k+1][$k1][$k2];
//             }

//          //  echo  $k2 .":". $treearr[$k][$k1][$k2] ."<br>";

//              // $str .="<ul>".$v2  ;
//         }
       
       
//     }

   
   
// }    

// $str .="</ul>";
    


 //dd($opCount);
//return ($treearr);

 return (implode(" ", $strArr5[0]));

  //  $arr[ $rs->id ]=[$rs->parent_id , $rs->id , $rs->name] ;    
   // dd($arr);

   
});



Route::get('/rq' , function(){

    return 0;
});

//Route::get('/he/{comp_name}', HtmlEditorComponent::class); //html  Editor

Route::post('/editor/{comp_name?}',function($comp_name ='he-init'){
    
    dd($comp_name);
    return view('layouts.HtmlEditorLayout');

});



Route::post('/saveConfiqCss/',function(Request $req){   
    Helpery::setConfig("{$req->fileName}_css" , $req->css);
    return true;
});

Route::post('/saveConfiqJs/',function(Request $req){   
    Helpery::setConfig("{$req->fileName}_js" , $req->js);
    dd($req->js);
});


Route::get('/tm' , TreeManagerComponent::class);   // tree Manager



Route::get('/tree',CatTree::class);

Route::get('/shop',ShopComponent::class);

Route::get('/cart',CartComponent::class)->name('product.cart');

Route::get('/',HomeComponent::class);

Route::get('/ar',ArticleManager::class);

Route::get('/product/{slug}',DetailComponent::class)->name('product.detail');


Route::get('/caty', function () {
    $rootArr =[]; 
    $levels =[];   

    $roots= catagory::where ("parent_id" , 0)->get();
    $childs= catagory::where ("parent_id" ,">", 0)->get();

    foreach($roots as $root){
        $levels[0][] =  ["parent" =>$root->parent_id ,"name" => $root->name, "childs" =>  $root->code];
    //                         parent of level                                parent of next level
    }         
     
    // flatren  multi dimention arr 
    $i = 0; 

    while  ($i >= 0){  

        foreach($childs as $child){

            foreach ($levels[$i] as $lev){
                
            if($lev["childs"] ==  $child->parent_id ){
                $levels[$i+1][] =  ["parent" =>$child->parent_id ,"name" => $child->name, "childs" =>  $child->code];
            }

            }       
        }      
        
        if (!isset($levels[$i+1]) ){

            $i=-1;

        } else{

                $i++;
            }
} 

$lev=[];

   $le = count($levels)-1;


    for  ($u = $le ; $u >= 0 ; $u--){
           // echo $u;
            for ($y = 0 ; $y < count($levels[$u]) ; $y++){
                    
              
                if (isset ($lev[$u+1][$levels[$u][$y]['childs']])){
                    
                    $lev[$u][$levels[$u][$y]['parent']][] ="<li>".$levels[$u][$y]['name']."<ul>".implode(" " , $lev[$u+1][$levels[$u][$y]['childs']])."</ul></li>";
                
                }else{

                    if ($u< $le) {
                        
                        $lev[$u][$levels[$u][$y]['parent']][] ="<li>".$levels[$u][$y]['name']."</li>";
                    
                        }else {
    
                        $lev[$u][$levels[$u][$y]['parent']][] ="<li>".$levels[$u][$y]['name']."</li>";
                    }
                

                }
              
            
            }      
                
    }

  
   return  "<ul style='direction:rtl'>".implode(" ",$lev[0][0])."</ul>";
   
        
});


// Route::middleware(['auth:sanctum', 'verified'])->get('/ad', function () {
//     return view('vendor.admin.home');
// })->name('admin');

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');


// Route::middleware(['auth:sanctum', 'verified'])->get('/db',  DbManageComponent::class)->name('db');