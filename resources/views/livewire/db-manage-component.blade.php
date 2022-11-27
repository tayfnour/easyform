<div class="el">
   {{-- <div>{{$form}}</div>
   <div>{{$table}}</div>
    {{ session("db_form")}} --}}
    <div class="container-fluid el">

        <div class="row el">

            <div class="col-md-12 p-3 el">

                <div class="card el">

                    <div class="card-header el">
                        <div class="row el">
                             
                            <div class="col-md-3 el">
                                <div class="p-2 el">
                                    <label for="">search Word</label> 
                                    <input class="form-control rounded p-1 el" placeholder="Search Word" type="text"
                                        wire:model="SearchWord">
                                </div>
                            </div>

                            <div class="col-md-3 el">
                                <div class="p-2 el">
                                <label for="">Form Name</label>     
                                <select class="form-select selected_column m-1 el " wire:model="form">
                                    @foreach($formNames as $form)
                                      <option val="{{$form}}" class="el">{{$form}}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>

                            <div class="col-md-3 el">
                                <div class="p-2 el">
                                    <label for="">Table Name</label>  
                                    <select class="form-select el" wire:model="table" wire:change="tableChanged()">
                                        @foreach ($table_names as $key => $tb)
                                            <option class="el">{{ $tb->Tables_in_easypanel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 el">
                                <div class="p-2 el">
                                    <label for="">search col</label> <span wire:click="saveRelation()" style="float:left;cursor:pointer;color:red">حفظ العلاقة</span>
                                    <select class="form-select el" wire:model="targetRef" >
                                        @foreach ($columns as $col)
                                            <option class="form-select el">{{ $col }}</option>
                                        @endforeach                                       
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="el mt-2 ">
                        <ul class="list-group px-5">      
                          {{-- {{ dd($errors->all()) }} --}}
                            @if ($errors->any())                            
                                @foreach ($errors->all() as $error)
                                    <li  class="errors" style="color:red" >{{$error}}</li>
                                @endforeach
                            @endif           
                        </ul>
                    </div>
                    <div style="font-size: 12px">
                        {!! $relations !!}
                    </div>

                    <div class="panel-body el" style="overflow-x:auto">

                        @if ($rows->count() > 0)

                            <table class="table table-bordered table-striped table-responsive-md p-3 el"
                                style=" width:100%">

                                <tbody class="el">
                                    <tr class="el">
                                        @foreach ($rows[0] as $key => $row)

                                            @if (isset($opts->$key) && $opts->$key->arabic_name !== '')
                                                <th style="color:blue" class="el">
                                                    {{ $opts->$key->arabic_name }}
                                                </th>
                                            @else
                                                <th style="color:blue" class="el">
                                                    {{ $key }}
                                                </th>
                                            @endif
                                        @endforeach
                                        <th style="color:blue" class="el">Actions</th>
                                    </tr>

                                   {{-- {{dd($opts)}} --}}

                                    @foreach ($rows as $k => $row)                                    
                                        <tr class="parent">
                                            @foreach ($row as $col => $val)                                            
                                         
                                                @if (isset($opts->$col) && $opts->$col->inputType == 'single_image')

                                                    {{--                                                        
                                                        <textarea class="form-control" data-col="{{$colName}}" cols="20">{{ $val }}</textarea>
                                                        <img src="http://localhost/global_images/{{ $val }}"  style="width:175px">
                                                    </td> --}}
                                                    <td class=""> 
                                                    <div  x-data="">
                                                        <div style="position: relative">
                                                         <input id="inp_1000" x-ref="picOne" type='file' wire:model='onePhoto.{{$k}}.{{$col}}'
                                                                style="display:none">
                                                        <span @click="$refs.picOne.click()"
                                                        style="background-color:rgb(243, 241, 241);float:left;display:inline-block;padding:2px 10px;border-right:1px solid #ccc;cursor:pointer;;position: absolute ; top:4px; left:5px;  z-index: 100;">Upload</span>

                                                            @php
                                                                $this->colArr[$k][$col] =  $val;                                                               
                                                            @endphp
                                                            <input  type='text'  data-col="{{$col}}" wire:model='colArr.{{$k}}.{{$col}}' class="form-control">   
                                                            <div class="shadow"
                                                            style="box-sizing: border-box;border:2px solid #ddd;width:100%;z-index:10;background-color:white;padding:5px" >
                                                         
                                                                @if(!empty($val))
                                                                  <img src="http://localhost/global_images/{{ $val }}"  style="max-width:100%;max-height:150px"  alt=""> 
                                                                @endif
                                
                                                                @if(isset($onePhoto[$k][$col]))                               
                                                                 <div>صورةالمؤقتة</div>
                                                                 <img src="{{ $onePhoto[$k][$col]->temporaryUrl() }}" style="max-width:100%;max-height:150px"  alt=""> 
                                                                @endif
                                                            </div>    
                                                        </div>
                                                    </div>     
                                                </td>

                                                @elseif (isset($opts->$col) &&  $opts->$col->inputType=="textarea")
                                                    <td>
                                                        <textarea class="form-control" data-col="{{$col}}" cols="20">{{ substr($val , 0, 100) }}</textarea>
                                                    </td>

                                                @elseif (isset($opts->$col) &&  $opts->$col->inputType =="auto")
                                                    <td>  
                                                        @php 
                                                        // if has value leave it
                                                        if(empty($this->findRefPar[$col][$k])){
                                                            $this->findRefPar[$col][$k]= $val;
                                                           }
                                                         
                                                           $autoVal =  $this->getAutoValue($col , $this->findRefPar[$col][$k]);
                                                        if(!empty($autoVal))                                                                 
                                                            $this->findRef[$col][$k]= $autoVal;                                                     
                                                        @endphp  

                                                        <div class="input-wrapper">                                                   
                                                            <input wire:model="findRefPar.{{$col}}.{{$k}}"   data-col="{{$col}}" type="hidden" > 
                                                            <input id="stuff" wire:model="findRef.{{$col}}.{{$k}}" title="{{isset($findRefPar[$col][$k])?$findRefPar[$col][$k]:null}}"  wire:input="autoComplete({{$k}} , '{{$col}}')" wire:blur="closeAuto({{$k}} , '{{$col}}')"  wire:focus="autoComplete({{$k}} , '{{$col}}')" class="w-300 form-control {{ $opts->$col->widget}}"   />
                                                            <label wire:click="cleanFeild({{$k}} ,{{$row->$primaryKey}} , '{{$col}}')" for="stuff"  class="fas fa-window-close input-icon"></label>
                                                        </div>

                                                        @if (isset($autoData[$k][$col]) && strlen($autoData[$k][$col]) > 0)
                                                            <div class="shadow" 
                                                                style="border:2px solid #ddd;width:300px;z-index:10;position:absolute;background-color:white;padding:5px">
                                                            
                                                                <div>
                                                                    {!! $autoData[$k][$col] !!}
                                                                </div>
                                                            
                                                            </div>
                                                        @endif  

                                                        

                                                     </td>                 
                                              
                                                    @else

                                                {{-- {{dd($opts[$colName])}} --}}
                                                    <td class="el">
                                                       <input class="w-100 form-control"  data-col="{{$col}}" type="text"  value="{{ $val }}"  >                                                     
                                                    </td>
                                                @endif
                                            @endforeach

                                            <td class="el">
                                                <input data-row="{{$k}}" data-id="{{$row->$primaryKey}}"  type="button" class="EditRow btn btn-xs btn-warning el" href="#" value="edit">
                                                <input data-id="{{$row->$primaryKey}}" type="button" class="DelRow btn btn-xs btn-dark el " href="#" value="delete">

                                            </td>
                                        </tr>

                                    @endforeach

                                </tbody>
                            </table>

                        @else
                            <div class="alert alert-warning text-center m-2 el">لا يوجد أي سجلات في الجدول المنتقى
                                {{ $table }}
                            </div>
                        @endif
                    </div>

                    <div class="paginate pt-2 pb-2 el">
                        {{ $rows->links() }} <input class="btn btn-success" type="button" wire:click ="insertRowInTable" value="صف جديد">
                    </div>
                    
                </div>

            </div>
        </div>
        <div class="el mt-2">
            <ul class="list-group el">
                @foreach (array_reverse($messages) as $msg )
                    <li class="list-group-item el">
                        {{$msg}}
                    </li>
                @endforeach                       
            </ul>
        </div>
    </div>
</div>
