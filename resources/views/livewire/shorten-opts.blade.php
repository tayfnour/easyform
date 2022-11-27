<div>        
        <div class="attrPartition" style="overflow-y:auto">  
            
            <div class="section section_class" style="padding:5px">
                <div style="background-color: rgb(255, 0, 119);padding:2px">Form Options</div>
                <div class="container container_class el">
                    <div class="row row_class el">

                        <div class="col-6">
                            <label>App Name</label>
                            <select class="form-select selected_column m-1 el " wire:model="appname">
                                @foreach ($AppNames as $app)
                                    <option val="{{ $app }}" class="el">{{ $app }}</option>
                                @endforeach
                            </select>
                        </div>
                         <div class="col-6">
                            <label>Page Name</label>
                            <select class="form-select selected_column m-1 el " wire:model="currentPage">
                                @foreach ($AppPages as $page)
                                    <option class="el">{{ $page }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-6">
                            <label>Form Name</label>
                            <select class="form-select selected_column m-1 el " wire:model="appform">
                                @foreach ($AppForms as $form)
                                    <option val="{{ $form }}" class="el">{{ $form }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Form isActive</label>
                            <input class="form-control m-1" type="text" wire:model="formData.isActive">                            
                        </div>
                        <div class="col-6">
                            <label>Form description</label>
                            <input class="form-control m-1" type="text" wire:model="formData.description">                            
                        </div>
                        <div class="col-6">
                            <label>Form ordering</label>
                            <input class="form-control m-1" type="text" wire:model="formData.ordering">                            
                        </div>
                        <div class="col-6">
                            <label>Form Width</label> 
                            <input class="form-control m-1" type="text" wire:model="formData.formBS">              
                        </div>
                        <div class="col-6">
                            <label>Form isFloat</label>        
                            <input class="form-control m-1" type="text" wire:model="formData.isFloat">       
                        </div>
                        <div class="col-6">
                            <label>Project id</label> 
                            <input class="form-control m-1" type="text" wire:model="formData.myproj_id">              
                        </div>
                        <div class="col-6">
                            <label>Form isOpen</label>        
                            <input class="form-control m-1" type="text" wire:model="formData.isOpen">       
                        </div>
                        <div class="col-12">
                            <label>Query Refrence</label>        
                            <input class="form-control m-1" type="text" wire:model="formData.queryRef">       
                        </div>
                        <div class="col-12">
                            <label>Form Query</label>        
                            <textarea class="form-control m-1" type="text" wire:model="formData.formQuery"></textarea>       
                        </div>

                       {{-- {{dd(json_decode($formData["formAttrs"]))}}  --}}

                        <div class="col-12">
                            
                            <label><span>Form Attributes</span></label>     
                            <textarea id="formAttrs_json" class="form-control m-1" type="text" wire:model="formData.formAttrs"></textarea>
                        @php
                           // dd($formData);
                        @endphp

                           @if(!empty(json_decode($formData["formAttrs"],true))>0)
                            @foreach (json_decode($formData["formAttrs"],true) as $key => $val)
                                <li class="jsonLi"><span wire:click="deleteJsonByKey('{{$key}}')" class="addFormAttr">-</span><span>{{$key}}</span>:<span>{{$val}}</span></li>
                             @endforeach
                            @endif 
                            <div class="row">   
                                <div  class="col-1">
                                    <span wire:click="addAttrToJson()" class="addFormAttr addKeyVal" style="text-align:center;width: 100%">+</span>
                                </div>
                                <div class="col-3">
                                    <input wire:model="formJsonKey" type="text"  class="form-control" value="">
                                </div>   
                                <div class="col-8" >
                                    <input wire:model="formJsonVal" type="text" class="form-control formVal" value="">
                                </div>   
                            </div>
                            {{-- @endforeach --}}
                        </div>

                        <div class="col-12">
                            <input class="btn btn-success btn-xs m-1" type="button" value="Add Project"  wire:click="AddProject()">  
                            <input class="btn btn-success btn-xs m-1" type="button" value="Add Page"  wire:click="addPageToProject()">
                            <input class="btn btn-success btn-xs m-1" type="button" value="Add Form"  wire:click="addCurrentForm()">
                            <input class="btn btn-success btn-xs m-1" type="button" value="update Form"  wire:click="updateWithCurrentForm()">

                            <input class="btn btn-primary btn-xs m-1" type="button" value="update Data"  wire:click="updateFormData()"> 
                            <input class="btn btn-primary btn-xs m-1" type="button" value="Delete Form"  wire:click="DeleteCurrentForm()">   

                            <label>Project Name Or Page Name</label>
                            <input class="form-control m-1" type="text" wire:model="projectOrPageName"> 
                            
                            @if(!empty($formMsg))
                            <div>
                                {{ $formMsg }}
                            </div> 
                            @php $this->$formMsg=null @endphp  
                            @endif                    
                        </div>                        
                    </div>
                </div>
            </div>
               
            <div class="section section_class" style="padding:5px">
                <div style="background-color: rgb(197, 238, 16);padding:2px">Columns Options</div>
                <div class="container container_class el">
                    <div class="row row_class el">                     
                        <div class="col-6">
                            <label>Table Name</label>
                            <select class="form-select selected_column m-1 el " wire:model="table">
                                @foreach ($tableNames as $tbName)
                                    <option class="el">{{ $tbName }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-6">
                            <label>Column Name({{$this->getTypeofColumnAndLangth($colName)}})</label>
                            <select class="form-select selected_column m-1 el " wire:model="colName">
                                @foreach ($columns as $col)
                                    <option val="{{ $col }}" class="el">{{ $col }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label>Form Name</label>
                            <select class="form-select selected_column m-1 el " wire:model="formName">
                                @foreach ($formNames as $form)
                                    <option val="{{ $form }}" class="el">{{ $form }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div style="height:600px">
                <table class="table my_table" id="col_option_table" style="width:100%">

                    @php   $ic = 1;   @endphp

                    <tbody>

                        @foreach ($options as $key => $col)
                            <tr class="el">
                                <td class="" id="option_key_{{ $ic }}"
                                    style="font-weight:600;padding:1px 0px 1px 5px;width:10%;background-color:rgb(238, 233, 223)">
                                    {{ $key }}
                                </td>

                                @if ($key == 'inputType')
                                    <td class="key_Val_opt el">
                                        <select wire:dirty.class="dertyclass" wire:model="options.{{ $key }}" class="form-select"
                                            id="option_value_{{ $ic }}">
                                            @foreach (explode('|', $optColOption['inputType']['lookup']) as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @elseif($key == 'formType')
                                    <td class="key_Val_opt el">
                                        <select wire:model="options.{{ $key }}" class="form-select"
                                            id="option_value_{{ $ic }}">
                                            @foreach (explode('|', $optColOption['formType']['lookup']) as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    
                                @elseif($key == 'onEventFn')

                                    <td class="key_Val_opt el">
                                        <textarea wire:model.debounce.1000ms="options.{{ $key }}"
                                            class="form-control" id="option_value_{{ $ic }}"></textarea>
                                    </td>

                                @elseif($key == 'lookup')

                                    <td class="key_Val_opt el">
                                        <textarea wire:model.debounce.1000ms="options.{{ $key }}"
                                            class="form-control" id="option_value_{{ $ic }}"></textarea>
                                        <select wire:model="lookTable" id="">
                                            <option value="1">picke Table</option>
                                            @foreach ($tableNames as $tbName)
                                            <option class="el">{{ $tbName }}</option>
                                            @endforeach
                                        </select>
                                        <select wire:model="lookCol" id="">
                                            <option value="1">picke Column</option>
                                            @foreach ($lookcolumns as $col)
                                            <option class="el">{{ $col }}</option>
                                            @endforeach
                                        </select>

                                    </td>

                                @elseif($key == 'logicalVal')
                                    <td class="key_Val_opt el">
                                        <textarea  wire:dirty.class="dertyclass" wire:model.debounce.1000ms="options.{{ $key }}"
                                            class="form-control" id="option_value_{{ $ic }}"></textarea>
                                    </td>    

                                @elseif($key == 'formAttrs') 

                            {{-- {{dd($options[$key])}}  --}}
                                
                                <td class="key_Val_opt el">
                                    
                                    <span wire:click="addField" class="addAttr" >+</span>
                                    <input wire:model.debounce.1000ms="options.{{ $key }}" type="text"
                                    class="form-control" id="option_value_{{ $ic }}" style="display: inline-block !important ;width: calc(100% - 30px);" readonly >
                                   
                                    @foreach ($formAttrKey as $key => $keyName)                                       
                                  
                                    <div  style='display:flex'>
                                        <div wire:click='removeAttr("{{$keyName}}")'  class='addAttr' >-</div>
                                        <div>
                                            <input wire:model.defer='formAttrKey.{{$key}}' class='form-control' style='display: inline-block !important ;width: calc(100% - 10px);' type='text' placeholder='Key'  value='{$key}'>
                                            <input wire:model.defer='formAttrVal.{{$key}}' class='form-control' style='display: inline-block !important ;width: calc(100% - 10px);' type='text' placeholder='Value' value='{$val}'>
                                        </div>
                                    </div>
                                    @endforeach
                                    <input wire:click="SaveFormOption" type="button" value="Save Form Option">
                                    {{-- {!!$this->calcFormAttrs($options[$key])!!}     --}}
                                </td>

                                @elseif($key == 'tableName' || $key == 'colOptions_id' || $key == 'formName' ||  $key == 'colName' ||  $key == 'autoIncreament' ) 
                                <td class="key_Val_opt el">
                                    <input wire:model.debounce.1000ms="options.{{ $key }}" type="text"
                                        class="form-control" id="option_value_{{ $ic }}"  readonly>  
                                </td>
                                @else
                                    <td class="key_Val_opt el">
                                        <input wire:dirty.class="dertyclass" wire:model.debounce.1000ms="options.{{ $key }}" type="text"
                                            class="form-control" id="option_value_{{ $ic }}">
                                    </td>
                                @endif
                            </tr>
                            @php   $ic++;   @endphp
                        @endforeach

                    </tbody>
                </table>
            
                <div>
                    <div style="background-color: rgb(87, 116, 245);padding:2px">Clone Current Form</div>
                    <input wire:click="saveOptions" type="button" class="btn btn-primary m-1" value="Save Option">
                    <input wire:click="CloneFormAsName" type="button" class="btn btn-primary m-1" value="Clone Current Form">
                    <input class="form-control m-1" type="text" wire:model="cloningName">         
                </div>

                <div>
                <div style="background-color: yellow;padding:2px">Order Form cols</div>
                <input type="button"  value="order Form cols" wire:click="orderFormCols" class="m-1 btn btn-primary mt-2 el">
                
                </div>

                <div>
                    <div style="background-color: rgb(235, 30, 183);padding:2px">SetBootstrap({{$form}})</div>
                    <input type="button"  value="Set Input Type" wire:click="setInputType" class="m-1 btn btn-primary mt-2 el">
                    <input type="button"  value="set bootstrap" wire:click="setFormBootstrap" class="m-1 btn btn-primary mt-2 el">
                    <input type="text"  wire:model="fieldsBootstrap" class="m-1 form-control">
                </div>

                <div>
                    <div style="background-color: yellowgreen;padding:2px">Create Table</div>
                    <textarea  wire:model="createTableQuery" class="m-1 form-control" style="height:160px"></textarea>
                    <input type="button"  wire:click="createLookupTable" class="m-1 btn btn-success" value="createLookupTable">
                </div>
                <div>
                    <div style="background-color: rgb(231, 60, 60);padding:2px">Add Column To table</div>

                    <select wire:model="selectTableToAddCol" class="m-1 form-select">
                        <option value="1">picke Table</option>
                        @foreach ($tableNames as $tbName)
                        <option>{{ $tbName }}</option>
                        @endforeach
                    </select>

                    <input type="text" placeholder="New Column Name" wire:model="colToAdd" class="m-1 form-control">   
                    <input type="text" placeholder="Column Type"  wire:model="colType" class="m-1 form-control">     
                    <input type="text"  placeholder="column Length"  wire:model="colLength" class="m-1 form-control">   

                    <input type="button"  wire:click="addColumnToTable" class="m-1 btn btn-success" value="Add New column">
                    <input type="button"  wire:click="alterColumnLengthAndType" class="m-1 btn btn-success" value="Alter Column">


                    <select class="form-select selected_column m-1 el " wire:model="oldColToAlter" style="display: inline-block">
                        <option value="1">picke Column</option>
                        @if ($columns2)
                        @foreach ($columns2 as $col)
                            <option val="{{ $col }}" class="el">{{ $col }}</option>
                        @endforeach
                        @endif
                    </select>
                    
                </div>
                <br><br><br><br><br><br><br><br>
                <br><br><br><br><br><br><br><br>
                <br><br><br><br><br><br><br><br>
                <br><br><br><br><br><br><br><br>
                <br><br><br><br><br><br><br>
                <br><br><br><br><br><br><br><br>
                <br><br><br><br><br><br><br><br>
                <hr>
            
            </div>
        
        </div>{{-- end of attrbutPartion --}}

        @if (isset($this->msgs))
        <div id="topMsg" class="topMsg" style="top:50px">
            {!! implode('<br>', $this->msgs) !!}
            @php $this->msgs=null @endphp
        </div>
        @endif
</div>
