<?php
namespace App\MyClass;
use DB;
trait newTemplateExtention
{
    public function getWidgetBeforeContent($rid , $fn)
    {
        if($fn=='myprojects'){
            $str = $this->colArr[$rid]["proName"]."-".$this->colArr[$rid]["myproj_id"];
            return  "<div style='border:1px dotted blue'>{$str}</div>";
        }
        
    }

    function getRightSideContent($rid , $fn)
    {
        if($fn=='myprojects'){
            $str = "right Sid form";
            return  "<div class='col-2 p-2' style='border:1px dotted blue'><label>{$str}</label></div>";
        }
    }

    function getLeftSideContent($rid , $fn)
    {
        if($fn=='myprojects'){
            $str = "left Sid form";
            return  "<div class='col-2' style='border:1px dotted blue'>{$str}</div>";
        }
    }

    // public function getWidgetAfterContent($rid , $fn)
    // {
    //     if($fn=='myprojects'){
    //         $str = $this->colArr[$rid]["proName"]."-".$this->colArr[$rid]["myproj_id"];
    //         return  "<div style='border:1px dotted blue'>{$str}</div>";
    //     }
        
    // }

    //image widget
    public function getWidgetAfterContent($row_id, $fn){

        if($fn=='a_test'){            
      
        $str="<div class='col-12' >";

        $id = $this->colArr[$row_id]["pic"];

        $res =  DB::table("up_images")->where("id" , $id)->first();

        if (isset($res->id)){

            $str .= "<div class='text-center' style='padding:2px;margin-top:3px'>";   
            $str .= "<img src='http://localhost/global_images/{$res->file_name}' style='width:100%;max-height:150px'>"; 
            $str .= "<div wire:click='deletePhoto($row_id , \"pic\")' class='text-center' style='cursor:pointer'>Delete</div>"; 
            $str .= "</div></div>";
            $this->colArrPar[$row_id]['pic']= $res->description;
            unset($this->photos[$row_id]);

            return $str;
            
        }else if (isset($this->photos[$row_id]["pic"])){

            $str .= "<div class='text-center' style='padding:2px;margin-top:3px'>";  
            $str .= "<img src='{$this->photos[$row_id]["pic"]->temporaryUrl()}' style='width:100%;max-height:150px'>"; 
            $str .= "<div><input wire:model ='photosDesc.{$row_id}.pic'  class='form-control'  type='text'    ></div>";   
            $str .= "<div wire:click='savePhoto($row_id , \"pic\")' class='text-center' style='cursor:pointer'>Save</div>"; 
            return $str."</div></div>";
        }else{            
            return "<div>No Image Upload</div>";
        }   

    }   
  }
}