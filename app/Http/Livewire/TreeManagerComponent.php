<?php

namespace App\Http\Livewire;

use DB;
use App\MyClass\Tree;
use App\MyClass\Helpery;
use Livewire\Component;
use Illuminate\Support\Str;

class TreeManagerComponent extends Component
{

    public  $tbName= "plans";
    public  $parent=0;
    public  $code=0;
    public  $is_end;
    public  $node;
    public  $newNode;
    public  $newChild;
    public  $confirming="";
    public  $tbNameAll;
    public  $colName;
    public  $list_order;
    public $SearchIn;
    public  $autoTreeSearch;
    public $ic;
   // public  $filter = 41;

    public  $filter;

   
    protected $listeners = ['createTreeTable' => 'createTreeTable'  ];


    public function mount(){

        $this->tbName =  session("tableTree") ? session("tableTree") : $this->tbName;
       
    }

    function createTreeTable($name){
      

        $res = $this->getConfig("Tree_Names");

       if( $res == false){  // if  table name not exist
            $this->setConfig("Tree_Names" , $name );
            DB::statement("create Table {$name} like catagories");
            DB::table($name)->insert([]);
            session()->flash('message', "THE TABLE Created .." );
            $this->addFirstNode($name);
    }else{         
        
        if(!Str::contains($res , $name )){
            $this->setConfig("Tree_Names" ,$res .','. $name );
            DB::statement("create Table {$name} like catagories");
            $this->addFirstNode($name);
            session()->flash('message', "THE TABLE Created .." );

        }else{
            session()->flash('message', "THE TABLE IS EXIST .." );
        }
    }

    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('loadStates', []);
    }


    function IsNodeHasChildren($code){

    $count = DB::table($this->tbName)->where("parent_id" , $code )->count();

    if ($count>0) 
        return true ;
    else 
        return false;
    }

    public function confirmDelete()
    {
        $this->confirming = "ok";
    }

    public function updateOrder($code , $order){
        DB::table($this->tbName)->where("code", $code)->update(["list_order"=> $order]);
    }

    public function kill($parent , $code)
    {

        if(!$this->IsNodeHasChildren($code)){
            DB::table($this->tbName)->where("parent_id", $parent)->where("code", $code)->delete();  
            $this->confirming = "";
            session()->flash('message', "Delete Node Successfully" );
        }else{
            session()->flash('message', "You Can not Delete Node Have Children" );
        }
    
    }

    function addFirstNode($name){

        DB::table($name)->insert([
            'name'=> "اول عنصر",
            'code'=> 1,
            'parent_id' => 0,
            'is_end'=> 0,
            'list_order' =>0,
            'updated_at' => now(),
            'created_at' => now()
            
        ]);
    }
public function addNode($parent , $newNode ){

        $maxOrder = DB::table($this->tbName)->where("parent_id",$parent)->max("list_order") + 10;
        $new_code = DB::table($this->tbName)->max("code") + 1;
        DB::table($this->tbName)->insert([
            'name'=> $newNode,
            'code'=> $new_code,
            'parent_id' => $parent,
            'is_end'=> 0,
            'list_order' => $maxOrder,
            'updated_at' => now(),
            'created_at' => now()
        ]);

        $this->newNode ="";
        $this->newChild="";
    }

    public function addChild($code , $newNode , $is_end){

        // when child code became parent
      // dd($is_end);
      $maxOrder = DB::table($this->tbName)->where("parent_id",$code)->max("list_order") +10;
     
        $msg ="";
        if($is_end == 1){
            $msg ="You Cant Add Child Node Under End Node";
        }else{
            $new_code = DB::table($this->tbName)->max("code") + 1;
            DB::table($this->tbName)->insert([
                'name'=> $newNode,
                'code'=> $new_code,
                'parent_id' => $code,
                'is_end'=> 0,
                'list_order' =>$maxOrder,
                'updated_at' => now(),
                'created_at' => now()
            ]);

        $msg ="You Added Child Node ". $newNode;
        }

        $this->newChild ="";
        $this->newNode ="";

        session()->flash('message', $msg );
    }



    public function setEndChildState($parent ,$code ,$chechboxState){
        $chechboxState = $chechboxState?1:0;
        $msg="";

        if ($this->IsNodeHasChildren($code) && $chechboxState == 1){
            $msg.="You Can Not Make This End Node ; it Has On or More Children ";
            $this->is_end = 0;
        }

        if ($this->IsNodeHasChildren($code)==false && $chechboxState == 0){
            DB::table($this->tbName)->where("parent_id", $parent)->where("code", $code)->update(["is_end"=>$chechboxState]);
            $msg.="This Node Became Not End Node You can Add cheldren for it";
        }

        if($this->IsNodeHasChildren($code)==false &&  $chechboxState == 1 ){
            DB::table($this->tbName)->where("parent_id", $parent)->where("code", $code)->update(["is_end"=>$chechboxState]);
            $msg.="This Node Became End Node ";
        }

       
        session()->flash('message', $msg );
        
    }

    public function setNodeName($parent ,$code ,$node){     
      //  dd($parent ,$code ,$node);   
        DB::table($this->tbName)->where("parent_id", $parent)->where("code", $code)->update(["name"=>$node]);
        session()->flash('message','NAME OF NODE UPDAETED...');
    }

    function setGlobalVar( $code , $parent , $is_end , $list_order){   
        $row =  DB::table($this->tbName)->where("parent_id", $parent)->where("code", $code)->first();
        $this->parent =$parent;
        $this->code =$code;
        $this->is_end =$row->is_end;
        $this->node =$row->name;
        $this->confirming = "";
        $this->list_order = $list_order;
        $this->newNode="";
        $this->newChild="";

    }   
    
    public function setConfig ($k , $val){

        $res = DB::table('config')->where("mkey" , $k)->get();
 
        if($res->count() >0 ){
        
            DB::table('config')->where("mkey" , $k)->update(["mval" => $val]);
 
        }else{
 
            DB::table('config')->insert([  "mkey" =>  $k ,"mval" => $val]);
        }   
 
    }

    function getConfig ($k){       
        $res = DB::table('config')->where("mkey" ,$k)->first();
        if($res)
            return $res->mval;
        else
            return false;
    }  
    
    function updatedSearchIn($val){

        if(!empty($val))  {
            $str="";
            $res = DB::table($this->tbName)->where("code",$val)->orWhere("name","like","%".$val."%")->get();

            foreach($res  as $rs){
                $str .= "<li wire:click=\"setSearch($rs->code)\" style='cursor:pointer;border-bottom:1px solid #ddd' >".$rs->code." | ".$rs->name."</li>";
            }
            
            $this->autoTreeSearch=$str;
        }else{

            $this->filter=null;
            $this->autoTreeSearch  =null;

        } 
    }

    function setSearch($code){

        $exWhile = true; 
        $returnVal; 

           // get code of root parent of leaf

            while ($exWhile==true){

                $res = DB::table($this->tbName)->select("parent_id","code")->where("code",$code)->first();

                if($res->parent_id == 0){
                
                    $returnVal = $res->code;
                    $exWhile = false;
                

                }else{

                    $code = $res->parent_id;
                }

            

        }

        $this->filter = $returnVal;
        $this->SearchIn = $code;
        $this->autoTreeSearch = null;
    }

    function updatedtbName(){
        $this->SearchIn = null;
        $this->filter   = null ;
    }

    public function render()
    {

        session()->put('tableTree', $this->tbName);
        $this->ic++;

        $tree = new Tree();    
        return view('livewire.tree-manager-component',
        [
            "Dtree" =>  $tree->createDynamicTree($this->tbName , $this->filter),
            "tableNames"=>  explode(",",$this->getConfig("Tree_Names")),
            "allTable" => Helpery::getTableNames("easypanel"),
            "colNams"  => Helpery::getColNames($this->tbNameAll),            
        ]
        )->layout("layouts.topBarLayout");;
    }
}
