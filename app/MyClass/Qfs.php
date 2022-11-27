<?php
  
  namespace App\MyClass;
  use DB;

  class Qfs {
    
     public static function buildQueryFromString($string, $splitter1='|', $splitter2='=>', $splitter3='*'){

      $queryArray = [];  
      

      //split by |
      $subArray = explode($splitter1, $string);

      foreach ($subArray as $val) {
        

        // split by =
        $temp = explode($splitter2, $val);

        if(preg_match('/where[A-z]/', $temp[0]) || $temp[0]=='order' || $temp[0]=='paginate'){     

            $queryArray[$temp[0]] = explode($splitter3, $temp[1]);

        }else{

            $queryArray[$temp[0]] = $temp[1];
        }     

      }

      //dd($queryArray);

     foreach ($queryArray as $key => $value) {   
      
      $query;

      switch($key){
        case 'table':
            $query = DB::table($value);
            break;
        case 'select':
            $query = $query->select($value);
            break;
        case (preg_match('/where[A-z]/', $key)  ? true : false):
            $query = $query->where($value[0], $value[1], $value[2]);         
            break;
        case 'order':
            $query = $query->orderBy($value[0], $value[1]);           
            break;   
        case 'paginate':
            $query = $query->paginate($value[0],["*"],$value[1]);
            break;        
        case 'first':
            $query = $query->first();
            break;
        case 'insert':
            $query = $query->insert($value);
            break;
        case 'insertGetId':
            $query = $query->insertGetId($value);
            break;
        case 'update':
            $query = $query->insertGetId($value);
            break;
        case 'delete':
            $query = $query->delete();
            break;
        case 'count':
            $query = $query->count();
            break;
        case 'max': 
            $query = $query->max($value);
            break;
        case 'min':
            $query = $query->min($value);
            break;
        case 'avg':
            $query = $query->avg($value);
            break;
        case 'sum':
            $query = $query->sum($value);
            break;
        case 'exists':
            $query = $query->exists();
            break;
        case 'pluck':   
            $query = $query->pluck($value);
            break;
        case 'chunk':
            $query = $query->chunk($value);
            break;
        case 'chunkById':
            $query = $query->chunkById($value);
            break;
        case 'get':
            $query = $query->get();
            break;                  

      }

     
 
     }
 return $query;
    }

  }