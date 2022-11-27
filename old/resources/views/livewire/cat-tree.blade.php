<div  style='display:flex;direction:rtl;padding:10px:150px; height:600px; align-items: top; justify-content: right;'>
    <div   class="treeside"   style="flex: 1;padding:10px">

          
        <div class='text-right' > Tree Of Products :</div>

        

         <div style = "color:red;padding:5px">{{ $msg }}</div>

         @php  
            $msg = ""
         @endphp



        <div class="card p-3" style="height:550px;border:1px solid #eee;overflow:auto;">
        {!!  $Dtree !!}
        </div>


    </div>
    <div class="formside"   style="flex: 1;padding:10px 20px 20px 20px; " >
    
     <div class='text-right' > Form :</div>
     

    <form method='POST' enctype='multipart/form-data' >
        <div> 
          
            @if ($errors->any())
                   @foreach ($errors->all() as $error)
                       <div  class="errors" >validate : {{$error}}</div>

                   @endforeach
            @endif

         

    </div> 
    <div>
           @if (session()->has('message'))
               <div  class="errors">
                   {{ session('message') }}
               </div>               
           @endif
           @php
               session()->forget('message');
           @endphp
    </div>  
    <div class="card p-3 mt-2" style="height:550px" >
        <table class='table'  style='margin-top:10px'>
            <tr>
                <td   class='text-center' style='width: 155px;padding-top: 6px;height:42px'>
                اسم المنتج
                </td>
                <td  style='position : relative'>
                <input wire:model.defer='crudVal' class='crud_val' type='text' />
                <span class="resetAll"  wire:click="resetAll()">+</span>
                </td>
            </tr>           
            <tr>
                <td class='text-center' >
                السعر
                </td>
                <td>
                <input wire:model.defer='price' class='price' type='text' />
                </td>
            </tr>
            <tr>
                <td class='text-center' >
                باركود
                </td>
                <td>
                <input wire:model.defer='sku' class='sku' type='text' />
                </td>
            </tr>
            <tr>
                <td class='text-center' >
                صورة المنتج
                </td>
                <td>
                <input   wire:model.defer='image' class='image' type='file'  />
                <span    wire:loading wire:target='image'>Uploading...</span>
               
                </td>
            </tr>
            <tr>
                <td  style=''>
                        {{$gcode}} = Code 
                        <br>  {{$gparent}} = Parent
                        <br>  {{ $isParent ? "yes" : "No" }}= isParent
                        <br>  {{ $isProduct ? "yes" : "No" }}= isProduct
                        <br>{{$isgategory}} : iscatagory 
                </td>
                <td style='padding-top: 6px;height:42px'>
                    <span  wire:click ='update_node({{$gcode}})'  class='edit_node  crudIcon' >e</span>
                    <span  wire:click ='insert_node({{$gparent}} , {{$gcode}})' class='insert_node crudIcon' >i</span>
                    <span  wire:click ='del_node({{$gcode}})' class='del_child  crudIcon' >d</span>   
                    <span  wire:click ='insert_child({{$gcode}})' class='add_child crudIcon' >c</span>
                </td>
            </tr>
        </table>
    </div> 
        <div> 

                @if (is_object($image))
                    <img src="{{ $image->temporaryUrl() }}" style="width:150px">
                @endif   

                @if ($image !="")
                   <img src="http://localhost/easyPanel/storage/app/public/{{$image}}"  style="width:150px" >
                @endif
         </div>
    </form>
    </div>
</div>