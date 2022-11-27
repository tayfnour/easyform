


         <livewire:cat-tree  :forcefresh="$second_cat" />
 


<div style="display: flex;justify-content: center;align-items: top;padding:10px">

    <div  style="width :33% ; border:1px solid #ccc;padding:10px;margin-right:5px">
         <input  wire:model="main_cat" type="text" class="form-control" style="width:55px"  />
         <input  wire:model="main_cat_input" type="text"  style="width:120px" />
         <input  wire:click="add_main_cat()" type="button"  style="width:100px"   value="add Main" />
            <ul>
                @foreach ($cats1 as $cat )
                
                    <li  class = "@if ($second_cat == $cat->code ) {{'deactive'}} @endif" wire:click="getChildofmain({{$cat->code}})" >{{ $cat->name }} - 
                        <span style="cursor:pointer;color:red"> {{ $cat->code }} </span> 
                                - {{ $cat->parent_id }}
                    </li>
                                
                @endforeach
            </ul>
   </div>


    <div  style="width :33% ; border:1px solid #ccc;padding:10px;margin-right:5px">
                  <input id="$second_cat == $cat->code" wire:model="second_cat" type="text" style="width:55px" />
                  <input  wire:model="sec_cat_input" type="text"  style="width:120px" />{{ $sec_cat_input }}
                  <input  wire:click="add_second_cat()" type="button"  style="width:100px"   value="add Sec" />

                <ul>
                @if ($second_cat > 0)  
                    @foreach ($cats2 as $cat )
                    <li  class = "@if ($three_cat == $cat->code ) {{'deactive'}} @endif" wire:click="getChildofsecond({{$cat->code}})">
                    {{ $cat->name }} - 
                    <span style="cursor:pointer; color:red"> {{ $cat->code }} </span> 
                    - {{ $cat->parent_id }}
                   </li>
                    @endforeach
                @endif  
              </ul>
           
    </div>

    <div  style="width :33% ; border:1px solid #ccc;padding:10px;margin-right:5px">
                <input  wire:model="three_cat" type="text"  style="width:55px"  />
                <input  wire:model="thr_cat_input" type="text"   style="width:120px" />
                <input  wire:click="add_three_cat()" type="button"  style="width:100px"   value="add THREE" />
            <ul>
            @if ($three_cat > 0)    
                @foreach ($cats3 as $cat )
                <li>{{ $cat->name }} - 
                   <span style="cursor:pointer; color:red" > {{ $cat->code }} </span> 
                        - {{ $cat->parent_id }}
                   </li>
                @endforeach
            @endif  
            </ul>
    </div>
   
 </div> 

