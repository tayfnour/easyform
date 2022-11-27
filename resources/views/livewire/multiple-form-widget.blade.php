<div>
    <div class="container-fluid">
        <div class="row pt-1 px-1 mx-1" style="border-radius:10px;background-color:rgb(255, 252, 80)">
            <div class="col-4">
                <h4>
                    vechicle File (v1.0)
                </h4>
            </div>

            <div class="col-2">
                <h4 style="color:rgb(20, 220, 53)">
                    Validate State : ""
                </h4>
            </div>

            <div class="col-3">
                <input wire:click="formsLoop" class="btn btn-warning btn-xs" type="button" value="Refresh">
                <input wire:click="showGlobalVar" class="btn btn-warning btn-xs" type="button" value="global Var">
                <input wire:click="showFloatComponent('tree-manager-component')" class="btn btn-warning btn-xs"
                    type="button" value="Tree Management">
            </div>

            <div class="col-3">
                <h4 class="text-end">
                    معلومات المركبة
                </h4>
            </div>
        </div>
    </div>{{-- end first container --}}

    <div class="container-fluid my-3">
        <div class="row pt-1" style="direction: rtl;border:2px solid orange;background-color:#eee"> 
            <div class="col-12">
                @foreach ($fOpts as $k => $v )
                    @if($v["isFloat"] == 1)
                        <div class="float-form" wire:click="openFloatForm('{{$k}}')" ><span>&#11200;</span><span style="margin-left:5px">{{$v["description"]}}</span></div>                 
                    @elseif ($v["isClose"] == 0)
                        <div class="invisible-form" wire:click="openForm('{{$k}}')" ><span>&#11200;</span><span style="margin-left:5px">{{$v["description"]}}</span></div>                 
                    @endif                      
                @endforeach
            </div>
        </div>
    </div>   

    <div class="container-fluid"  >{{-- start second container --}}
        <div class="row pt-1" style="direction: rtl;border-radius:10px">      
            

         @foreach ($colArr as $k => $val) {{--$k is Row_id --}}
            @php
                $arrView=[];
                $opts = $formOpts[$rowOpts[$k][0]]; // get option of $row id  =$k
                $type = $rowOpts[$k][1]; // type of form
                $pk = $opts['pk'];
                $fn = $opts['fn'];
                $vis = $opts['vis'];
                $tb = $opts['tbName'];
                $event = $opts[$pk]['onEventFn'];
                $btname = $opts[$pk]['lookup'];   
                $fopts= $fOpts[$fn];   

               
            @endphp
         
               @if ($fopts["isClose"] == 1)
                        @if ($type == 'start' || $type == 'oneRow')

                        <div class="col-{{$opts[$pk]["formBootstrap"]}} form-container">{{--start container of Form --}} 
                            <div class="row p-1">
                                <div class="col-12 form-bg" > {{-- style="{{!$fopts["isClose"]?"display:none":null}}" height:45px;width:30%--}}
                                    
                                    <div class="row">
                                        <div class="col-12 form-header p-1 {{isset($opts["formHeader"])?$opts["formHeader"]:null}}" >

                                            @if($fopts["isClose"])                                                            
                                               <span class="resize-form" wire:click="closeForm('{{$fn}}')">&#9650;</span> 
                                            @endif

                                          <span style="line-height:30px">
                                            {{ $opts[$pk]['form_translate'] }}
                                          </span>
                                          <span style="line-height:30px;float:left">{{ $fn }}-<b>{{ $tb }}</b></span>                      
                                         </div>
                                        {{--here to add column to header--}}
                                    </div>
                        @endif
                                <div class="row"> {{--body row--}}
                                    <div class="col-12 form-body pb-2">

                                        <div class="row reapeted-row">
                                            {{--widgetBefore every row--}}  
                                            @if(isset($opts["widgetBefore"]))
                                            <div class="col-2 p-1">widget</div> 
                                            @endif     
                                            {{--Row Area--}}                               
                                            <div class="col-12 p-1">
                                                <div class="row one-row-area">
                                                   @foreach ($opts['cols'] as $col)

                                                        @php 
                                                                if($opts[$col]['action']=="setParentRrefrence"){
                                                                    $refField="redBg";
                                                                }
                                                                else if($opts[$col]['action']=="getMainRef"){
                                                                    $refField="blueBg";
                                                                }else{
                                                                    $refField="";
                                                                }
                                                                    
                                                            $en= "<span style='color:green'>(".$opts[$col]['colName'].")</span>" 
                                                        @endphp
                                                            {{-- @php $en= "(".$opts[$col]['colName'].")" .  "(".$opts[$col]['ordering'].")";@endphp --}}
                                                            {{-- @php $en="" @endphp --}}

                                                        @if ($opts[$col]['inputType'] == 'button')
                                                            <div class='text-end {{ $opts[$col]['bootstrap'] }}'>
                                                                @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                                                    <label class='p-2' style="font-size: 15px">Actions&nbsp;</label>
                                                                @endif
                                                                <input wire:click='{{ $opts[$col]['onEventFn'] }}({{ $k }})'
                                                                    type='button' value='{{ $opts[$col]['arabic_name'] }}'
                                                                    class='btn btn-twitter'>
                                                            </div>
                                                        @endif

                                                        @if ($opts[$col]['inputType'] == 'text')
                                                            <div class='{{ $opts[$col]['bootstrap'] }}'>
                                                                @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                                                    <label class='p-2' wire:click="currrentColumnOptions('{{$fn}}','{{$col}}')"
                                                                        style="cursor:pointer;font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                                                @endif               
                                                                <input 
                                                                    wire:model.debounce.2000ms='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'
                                                                    class='form-control {{$refField}}'>
                                                                @error('colArr.' . $k . '.' . $col)
                                                                    <span class="error" style="text-align:left">
                                                                        {{ preg_replace('/[col ar\.]+\d+\./', ' ', $message) }}

                                                                    </span>
                                                                @enderror

                                                            </div>
                                                        @endif

                                                        @if ($opts[$col]['inputType'] == 'textarea')
                                                            <div class='{{ $opts[$col]['bootstrap'] }}'>
                                                                @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                                                    <label class='p-2'
                                                                        style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                                                @endif
                                                                <textarea
                                                                    wire:model.debounce.2000ms='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'
                                                                    class='form-control'></textarea>
                                                                @error('colArr.' . $k . '.' . $col)
                                                                    <span class="error" style="text-align:left">
                                                                        {{ preg_replace('/[col ar\.]+\d+\./', ' ', $message) }}

                                                                    </span>
                                                                @enderror

                                                            </div>
                                                    
                                                        @endif

                                                        @if ($opts[$col]['inputType'] == 'select')
                                                            <div class='{{ $opts[$col]['bootstrap'] }}'>
                                                                @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                                                    <label class='p-2'
                                                                        style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                                                @endif
                                                                <select wire:model='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'
                                                                    class='form-select'>
                                                                    {!! $this->getOptionsofCol($opts[$col]['logicalVal'], $col, $opts[$col]['colType']) !!}
                                                                </select>

                                                                @error('colArr.' . $k . '.' . $col)
                                                                    <span class="error" style="text-align:left">
                                                                        {{ str_replace('arr.', '', $message) }}
                                                                    </span>
                                                                @enderror

                                                            </div>
                                                        @endif

                                                        @if ($opts[$col]['inputType'] == 'readonly')
                                                            <div class='{{ $opts[$col]['bootstrap'] }}'>
                                                                @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                                                    <label class='p-2'
                                                                        style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                                                @endif
                                                                <input
                                                                    wire:model.debounce.2000ms='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'
                                                                    class='form-control' readonly>
                                                                @error('colArr.' . $k . '.' . $col)
                                                                    <span class="error" style="text-align:left">
                                                                        {{ str_replace('arr.', '', $message) }}
                                                                    </span>
                                                                @enderror

                                                            </div>
                                                        @endif

                                                        @if ($opts[$col]['inputType'] == 'tb_auto')
                                                            <div class='{{ $opts[$col]['bootstrap'] }}'>
                                                                <div style="position: relative">
                                                                    <span
                                                                        wire:click="cleanAuto({{ $k }} ,'{{ $opts[$col]['colName'] }}')"
                                                                        style="float: left;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:45px;  z-index: 100;">X</span>
                                                                    @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                                                        <label class='p-2'
                                                                            style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                                                    @endif
                                                                    {{-- <input type='hidden' wire:model='colArr.{{ $opts[$col]['colName'] }}'> --}}
                                                                    <input
                                                                        wire:model.debounce.2000ms='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'
                                                                        wire:focus="getRowIdFromtable('{{ $col }}')"
                                                                        wire:input="getRowIdFromtable('{{ $col }}')"
                                                                        wire:blur="closeAuto('{{ $col }}')" {{-- {{ $this->buildEvents($opts[$col]['onEventFn']) }} --}}
                                                                        class='form-control {{ $opts[$col]['widget'] }} {{$refField}}'
                                                                        style='padding-left:25px;font-size:16px'>


                                                                    @error('colArr.' . $k . '.' . $col)
                                                                        <span class="error" style="text-align:left">
                                                                            {{ str_replace('arr.', '', $message) }}
                                                                        </span>
                                                                    @enderror



                                                                    @if (isset($autoArr[$k][$col]))
                                                                        <div class="shadow"
                                                                            style="border:2px solid #ddd;width:100%;z-index:1000;position:absolute;background-color:white;padding:5px">
                                                                            {!! $autoArr[$k][$col] !!}
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        @endif


                                                        @if ($opts[$col]['inputType'] == 'auto')
                                                            <div class='{{ $opts[$col]['bootstrap'] }}'>
                                                                <div style="position: relative">
                                                                    @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                                                        <label class='p-2' wire:click="currrentColumnOptions('{{$fn}}','{{$col}}')"
                                                                            style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                                                    @endif
                                                                    <input type='hidden'
                                                                        wire:model='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'>
                                                                    <input
                                                                        wire:model.debounce.2000ms='colArrPar.{{ $k }}.{{ $col }}'
                                                                        wire:focus="autoComplete({{ $k }},'{{ $col }}')"
                                                                        wire:input="autoComplete({{ $k }},'{{ $col }}')"
                                                                        wire:blur="closeAuto({{ $k }},'{{ $col }}')"
                                                                        {{-- {{ $this->buildEvents($opts[$col]['onEventFn']) }} --}} class='form-control {{ $opts[$col]['widget']}} {{$refField}}'
                                                                        title="{{ $colArr[$k][$col] }}-{{ isset($colArrTitle[$k][$col]) ? $colArrTitle[$k][$col] : null }}"
                                                                        style='padding-left:25px;font-size:16px'>

                                                                    <span
                                                                        wire:click="cleanAuto({{ $k }} , '{{ $opts[$col]['colName'] }}')"
                                                                        style="left: 2px;margin-bottom:-28px;background-color:white;float:left ;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:-33px;  z-index: 100;">X</span>
                                                                    @error('colArr.' . $k . '.' . $col)
                                                                        <span class="error" style="text-align:left">
                                                                            {{ str_replace('arr.', '', $message) }}
                                                                        </span>
                                                                    @enderror

                                                                    @if (isset($autoArr[$k][$col]))
                                                                        <div class="shadow"
                                                                            style="max-height:300px;overflow-y:auto;border:2px solid #ddd;width:100%;z-index:100000;position:absolute;background-color:white;padding:5px">
                                                                            {!! $autoArr[$k][$col] !!}
                                                                        </div>
                                                                    @endif

                                                                </div>

                                                            </div>
                                                        @endif

                                                        @if ($opts[$col]['inputType'] == 'classify')
                                                            <div class='{{ $opts[$col]['bootstrap'] }}'>
                                                                <div style="position: relative">
                                                                    <span
                                                                        wire:click="cleanAuto({{ $k }} ,'{{ $opts[$col]['colName'] }}')"
                                                                        style="float: left;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:45px;  z-index: 100;">X</span>
                                                                    @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                                                        <label class='p-2'
                                                                            style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                                                    @endif
                                                                    <input type='hidden'
                                                                        wire:model.debounce.2000ms='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'>
                                                                    <input
                                                                        wire:model.debounce.2000ms='colArrPar.{{ $k }}.{{ $col }}'
                                                                        wire:focus="getClassify({{ $k }} , '{{ $col }}' )"
                                                                        wire:input="getClassify({{ $k }} , '{{ $col }}' )"
                                                                        wire:blur="closeAuto({{ $k }} , '{{ $col }}')"
                                                                        class='form-control{{ $opts[$col]['widget'] }}'
                                                                        style='padding-left:25px;font-size:16px'>


                                                                    @error('colArr.' . $k . '.' . $col)
                                                                        <span class="error" style="text-align:left">
                                                                            {{ str_replace('arr.', '', $message) }}
                                                                        </span>
                                                                    @enderror

                                                                    @if (isset($autoArr[$k][$col]))
                                                                        <div class="shadow"
                                                                            style="border:2px solid #ddd;width:150%;z-index:1000;position:absolute;background-color:white;padding:5px">
                                                                            <input type="text" class="form-control">
                                                                            <div style="overflow-y:auto;max-height:300px;">
                                                                                {!! $autoArr[$k][$col] !!}
                                                                            </div>

                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($opts[$col]['inputType'] == 'comp')
                                                            <div class='{{ $opts[$col]['bootstrap'] }}'>
                                                                <div style="position: relative">
                                                                    <span wire:click="closeForm('{{ $opts[$col]['colName'] }}')"
                                                                        style="float: left;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position:relative ; top:45px; z-index :100">X</span>
                                                                    @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                                                        <label class='p-2'
                                                                            style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                                                    @endif
                                                                    <input
                                                                        wire:model.debounce.2000ms='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'
                                                                        {{ $this->buildEvents($opts[$col]['onEventFn']) }}
                                                                        class='form-control{{ $opts[$col]['widget'] }}'
                                                                        style='padding-left:25px;font-size:16px'>


                                                                    @error('colArr.' . $k . '.' . $col)
                                                                        <span class="error" style="text-align:left">
                                                                            {{ str_replace('arr.', '', $message) }}
                                                                        </span>
                                                                    @enderror


                                                                    @if (isset($autoArr[$col]) && strlen($autoArr[$col]) > 0)
                                                                        <div class="shadow"
                                                                            style="z-index:1000;border:2px solid #ddd;width:100%;position:absolute;background-color:white;padding:5px">

                                                                            @livewire("single-form" , ["formName" => "supplier" , "ref" =>
                                                                            $baseRef ])
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        @endif

                                                        @if ($opts[$col]['inputType'] == 'single_image')
                                                            <div class='{{ $opts[$col]['bootstrap'] }}' x-data="">
                                                                <div style="position: relative">
                                                                    @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                                                        <label class='p-2'
                                                                            style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                                                    @endif
                                                                    <input id="inp_1000" x-ref="picOne" type='file'
                                                                        wire:model='photos.{{ $k }}.{{ $col }}'
                                                                        style="display:none">
                                                                    <input type='text' wire:model='colArr.{{ $k }}.{{ $col }}'
                                                                        class="form-control" style="display:none">
                                                                    <input type='text'
                                                                        wire:model.debounce.2000ms='colArrPar.{{ $k }}.{{ $col }}'
                                                                        class="form-control">
                                                                    <span @click="$refs.picOne.click()"
                                                                        style="left: 2px;margin-bottom:-28px;background-color:white;float:left ;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:-33px;  z-index: 100;">Upload</span>
                                                                </div>
                                                            </div>
                                                        @endif



                                                        @if ($opts[$col]['inputType'] == 'date')
                                                            <div class='{{ $opts[$col]['bootstrap'] }}'>
                                                                @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                                                    <label class='p-2'
                                                                        style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                                                @endif
                                                                <input wire:model='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'
                                                                    id="" data-row='{{ $k }}' data-col='{{ $col }}'
                                                                    class='form-control {{ $opts[$col]['widget'] }}' title=''>

                                                                @error('colArr.' . $k . '.' . $col)
                                                                    <span class="error" style="text-align:left">
                                                                        {{ str_replace('arr.', '', $message) }}
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        @endif

                                                        @if(isset($opts['colWidget'])) 
                                                            <div class="{{$opts['colWidget']}}">                                                                      
                                                                    {{-- {!!  $this->excuteFuncAfterEveryCol($k , $opts["action2"]) !!}                                                                                     --}}
                                                            </div>            
                                                         @endif

                                                    @endforeach 
                                                    @if(isset($opts['rowWidget'])) 
                                                    <div class="{{$opts['rowWidget']}}">      
                                                            {!!  $this->excuteFuncAfterEveryRow($k) !!}                                                                                    
                                                    </div>            
                                                 @endif
                                                </div>
                                            </div> 
                                            {{--widgetAfter every row--}}  
                                            @if(isset($opts["widgetAfter"]))                           
                                            <div class="col-2 p-1">widget</div>  
                                            @endIf                             
                                        </div>  

                                    </div>

                                                           
                                </div>  

                            @if ($type !== 'oneRow')
                            <hr style="border:1px solid rgb(132, 6, 250) ; margin-top:2px ; margin-bottom:-14px">
                            <hr style="border:1px solid rgb(44, 44, 44); margin-bottom:3px">
                            @endif
                            
                        @if ($type == 'end' || $type == 'oneRow')
                             
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row p-1" style="background: #eee">
                                        @if (isset($opts['newButton']))
                                            @php $this->msgs[] = $opts['newButton']."|bt"; @endphp
                                            {!! $this->addDefaultRowButton($k, $opts['newButton']) !!}
                                        @endif
                        
                                        @if (isset($opts['action']))
                                            {!! $this->excuteFunctionEndOfForm($k, $opts['action']) !!}
                                        @endif
                        
                                        @if (isset($opts['action1']))
                                            {!! $this->excuteFunctionEndOfForm($k, $opts['action1']) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        {{--pagination controller--}}
                  
                      @if(!isset($opts["viewType"])) 
                        
                             @php $page=str_replace(" ","_",$fn) @endphp
                             @if (isset($bs_arrows[$page]))
                                 <div class="paginate pt-2 pb-2" style="margin-bottom:5px">
                                     {{ $bs_arrows[$page]->links() }}
                                 </div>
                             @endif 
                     @endif          
                             

                            <div class="row"> {{--form-footer--}}
                                <div class="col-12 form-Footer p-1" style="background-color: #ccc">
                                                                 
                                </div>
                            </div>
                      

                        </div>{{--white backgrpund--}}
                    </div> {{-- End Row of Form --}}           
                </div>{{-- End container of Form --}}   
                @endif  

                @endif
        @endforeach
        </div>{{-- end second Row --}}

    </div>{{-- end second container --}}

    @if (isset($this->floatDiv))
        <div class="floatDiv">
            <div class="floaTopBar">
                <span wire:click="hideFloatComponent" class="closeFloatDiv">X</span>
            </div>
            <div class="floatDivBody">
            
                @livewire($this->floatDiv , ['fn' => $this->compParam1 , 'col' => $this->compParam2])
            </div>
        </div>
    @endif 

    @if (isset($this->floatForm))
        <div class="floatDiv" >
            <div class="floaTopBar">
                <span wire:click="hideFloatComponent" class="closeFloatDiv">X</span>
            </div>
            <div class="floatDivBody">            
                @livewire("float-form" ,["fn"=> $this->floatForm])
            </div>
        </div>
   @endif




    @if (isset($this->msgs))
        <div id="topMsg" class="topMsg">
            {!! implode('<br>', $this->msgs) !!}
            @php $this->msgs=null @endphp
        </div>
    @endif

</div>

@php   
    Log::channel('mht')->info("End of View : " .(microtime(true) - $this->startTime));
@endphp