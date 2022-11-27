<div class="el">
    <div>Cols Options - {{$ic}}</div>
    <div class="section section_class el">
        <div class="container container_class el">
            <div class="row row_class el">

                <div class="col-4 col-4_class el">
                <label>Column Name</label>
                    <select class="form-select selected_column m-1 el " wire:model="colName">
                        @foreach($columns as $col)
                          <option val="{{$col}}" class="el">{{$col}}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-4 col-4_class el">
                <label>Table Name</label>
                    <select class="form-select selected_column m-1 el " wire:model="table">
                        @foreach ($tableNames as  $tbName)
                            <option class="el">{{$tbName}}</option>
                        @endforeach
                     </select>

                </div>
                <div class="col-4 col-4_class el">
                <label>Form Name</label>
                    <select class="form-select selected_column m-1 el " wire:model="formName">
                        @foreach($formNames as $form)
                          <option val="{{$form}}" class="el">{{$form}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div style="padding: 10px" class="el">
            <table class="table el" id="col_option_table" style="width:100%">
    
                @php   $ic = 1;   @endphp
    
    {{-- {{dd( $colOptions)}} --}}
              
                @foreach($options as $key => $col)
    
                @if (fmod($ic , 3) == 1 )
                <tbody class="el">
                    <tr class="el">
                        @endif
                       
                            <td class="el" id="option_key_{{$ic}}" style="font-weight:700;padding:5px;width:10%;background-color:rgb(238, 233, 223)">
                                {{$key}}
                            </td>

                            @if($key=="inputType")
                            <td class="key_Val_opt el">
                                <select wire:model="options.{{$key}}" class="form-select" id="option_value_{{$ic}}"  >
                                   @foreach (explode("|",$optColOption["inputType"]["lookup"]) as $item )
                                          <option value="{{$item}}">{{$item}}</option>
                                   @endforeach 
                                </select>
                            </td>
                            @elseif($key=="formType")
                            <td class="key_Val_opt el">
                                <select wire:model="options.{{$key}}" class="form-select" id="option_value_{{$ic}}"  >
                                   @foreach (explode("|",$optColOption["formType"]["lookup"]) as $item )
                                          <option value="{{$item}}">{{$item}}</option>
                                   @endforeach 
                                </select>
                            </td>
                            @elseif($key=="onEventFn" || $key=="lookup" || $key=="colType" )
                            <td class="key_Val_opt el">
                                <textarea wire:model="options.{{$key}}" class="form-control" id="option_value_{{$ic}}" ></textarea>
                            </td>
                            @else
                                <td class="key_Val_opt el">
                                    <input wire:model="options.{{$key}}" type="text" class="form-control" id="option_value_{{$ic}}"  >
                                </td>
                            @endif
                        @if (fmod($ic , 3) == 0 )
                    </tr>
                    @endif
    
                    @php   $ic++;   @endphp
                    @endforeach      
    
                </tbody>
            </table>
    
            <input wire:click="saveOptions" type="button" class="btn btn-primary mt-2 el" value="Save Option">
        </div>
    </div>

    <div class="container-fluid">       
        <div class="operations row border p-2 m-2">
                    <div class="col-4">
                        <label class="p-2">New Form Name</label>
                        <input type="text" class="form-control"  wire:model="newFormName">
                    </div>
                    <div class="col-4">   
                        <label class="p-2">Select Table</label>
                        <select class="form-select selected_column m-1 el " wire:model="selTable">
                            @foreach ($tableNames as  $tbName)
                                <option class="el">{{$tbName}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="p-2">Select Form Type</label>
                        <select class="form-select selected_column m-1 el " wire:model="formType">
                        <option value="0">0 - insert</option>
                        <option value="1">1 - update</option>
                        <option value="2">2 - table</option>
                        </select>    
                    </div>
                    <div class="col-12 p-2 ">   
                        <input wire:click="createNewForm" type="button" value="Create Form" class="btn btn-success">
                    
                    </div>   
         </div>
   </div>
   <div class="container-fluid">       
    <div class="operations row border p-2 m-2">
                <div class="col-4 col-4_class el">
                    <label class="p-2">Copied Column Name Options</label>
                    <select class="form-select selected_column m-1 el " wire:model="copiedColName">
                        @foreach($options as $key => $col)
                        <option val="{{$key}}" class="el">{{$key}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">   
                    <label class="p-2">Copy From Column</label>
                    <select class="form-select selected_column m-1 el " wire:model="copyFromForm">
                        @foreach($formNames as $form)
                          <option val="{{$form}}" class="el">{{$form}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">   
                    <label class="p-2">Copy To Column</label>
                    <select class="form-select selected_column m-1 el " wire:model="copyToForm">
                        @foreach($formNames as $form)
                          <option val="{{$form}}" class="el">{{$form}}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-12 p-2 ">   
                    <input wire:click="copyOptionsToForm()" type="button" value="copyOptionsToForm" class="btn btn-warning">
                </div>   
     </div>
</div>

    <div  id="topMsg" class="topMsg">
        @if (isset($this->msgs))
            {!! implode('<br>', $this->msgs) !!}
            @php $this->msgs=null @endphp
        @endif
    </div>
</div>