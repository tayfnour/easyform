<div>
    <div class="container">
        <div class="row pt-1" style="margin:5px 18px;border-radius:10px;background-color:rgb(255, 252, 80)">        
            <div  class="col-4">
                <h4>
                    Purchases Management (v1.0)
                </h4>              
            </div>

            <div  class="col-2">
                <h4 style="color:rgb(20, 220, 53)">
                    Validate State :  {{ $validateState }}                   
                </h4>
            </div> 

            <div  class="col-3">
                <input wire:click="formsLoop" class="btn btn-warning btn-xs" type="button" value="Refresh">
                <input wire:click="showGlobalVar" class="btn btn-warning btn-xs" type="button" value="global Var">
                <input wire:click="showFloatComponent('tree-manager-component')" class="btn btn-warning btn-xs" type="button" value="Tree Management">
            </div>

            <div class="col-3">
                <h4 class="text-end">
                    إدارة المشتريات
                </h4>
            </div>            
        </div>       
    </div>

    <div class="container">
        {{-- <div class="masonry"> --}}
      <div class="row" style="direction:rtl ; margin-left:5px ;  margin-right:5px">
            {{-- mht: k is row and evry row is form --}}
        @foreach ($colArr as $k => $val)
                @php
                    $opts = $formOpts[$rowOpts[$k][0]]; // get option of $row id  =$k
                    $type = $rowOpts[$k][1]; // type of form
                    $pk = $opts['pk'];
                    $fn = $opts['fn'];
                    $vis = $opts['vis'];
                    $tb = $opts['tbName'];                          
                    $event = $opts[$pk]['onEventFn'];
                    $btname = $opts[$pk]['lookup'];        
                  
                @endphp

                @if ($type == 'start' || $type == 'oneRow')
                    {{-- <div> --}}
                    <div class="{{'col-md-'.$opts['bf'][0]}}">

                        {{-- form class  md-6 or md12   first record of form to multiple row --}}
                        {{-- <div --}}
                        <div class="row"
                            style="margin:1px 1px 5px 1px;direction:rtl; border-radius:10px;background-color:rgba(238, 237, 237,.75);border:2px solid rgb(161, 161, 161);direction: rtl">

                            <div class="col-12 text-end py-2"
                                style="border-radius:10px 10px 0 0 ;color:#fff;padding:5px;height:40px;background-color:#01317b">
                                {{ $opts[$pk]['form_translate'] }}
                                <span
                                    style="font-size:16px;float:left">{{ $fn }}-<b>{{ $tb }}</b></span>
                            </div>
                            <div style="background-color: rgb(186, 218, 250)">
                                @isset($msgs[$fn])
                                    {{ $msgs[$fn] }}
                                @endisset
                            </div>
                @endif
                
                @if(isset($opts['bf'][1]) && !empty($opts['bf'][1]))  
                    <div class="col-md-{{$opts['bf'][1]}}">
                    <div class="row">   
                @endif

                    <div style="{{isset($opts["wrapForm"])?$opts["wrapForm"]:""}}">
                    {{-- Row of form --}}
                    <div class="row p-1"> 
                        @foreach ($opts['cols'] as $col)
                        
                            {{-- @php $en= "(".$opts[$col]['colName'].")" .  "(".$opts[$col]['ordering'].")";@endphp --}}
                            @php $en= "<span style='color:green'>(".$opts[$col]['colName'].")</span>" @endphp

                            {{-- @php $en="" @endphp --}}

                            @if ($opts[$col]['inputType'] == 'button')
                                <div class='text-end {{ $opts[$col]['bootstrap'] }}' >
                                    @if ($type == 'start' || $type == 'oneRow' || $vis!==null)
                                    <label class='p-2' style="font-size: 15px">Actions&nbsp;</label>
                                    @endif
                                    <input wire:click='{{$opts[$col]['onEventFn']}}({{ $k }})' type='button' value='{{ $opts[$col]['arabic_name'] }}' class='btn btn-twitter' >                          
                                </div>
                            @endif
                        
                            @if ($opts[$col]['inputType'] == 'text')
                                <div class='{{ $opts[$col]['bootstrap'] }}'>
                                    @if ($type == 'start' || $type == 'oneRow' || $vis!==null)
                                    <label class='p-2' style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                    @endif
                                    <input
                                        wire:model.debounce.2000ms='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'
                                        class='form-control'>
                                    @error('colArr.' . $k . '.' . $col)
                                        <span class="error" style="text-align:left">
                                            {{ preg_replace('/[col ar\.]+\d+\./', ' ', $message) }}

                                        </span>
                                    @enderror

                                </div>
                            @endif

                            @if ($opts[$col]['inputType'] == 'select')
                                <div class='{{ $opts[$col]['bootstrap'] }}'>
                                    @if ($type == 'start' || $type == 'oneRow' || $vis!==null)
                                    <label class='p-2' style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
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
                                    @if ($type == 'start' || $type == 'oneRow' || $vis!==null)
                                    <label class='p-2' style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
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
                                        @if ($type == 'start' || $type == 'oneRow' || $vis!==null)
                                        <label class='p-2' style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                        @endif
                                        {{-- <input type='hidden' wire:model='colArr.{{ $opts[$col]['colName'] }}'> --}}
                                        <input
                                            wire:model.debounce.2000ms='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'
                                            wire:focus="getRowIdFromtable('{{ $col }}')"
                                            wire:input="getRowIdFromtable('{{ $col }}')"
                                            wire:blur="closeAuto('{{ $col }}')" {{-- {{ $this->buildEvents($opts[$col]['onEventFn']) }} --}}
                                            class='form-control{{ $opts[$col]['widget'] }}'
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
                                        @if ($type == 'start' || $type == 'oneRow' || $vis!==null)
                                        <label class='p-2' style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                        @endif
                                        <input type='hidden'
                                            wire:model='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'>
                                        <input
                                            wire:model.debounce.2000ms='colArrPar.{{ $k }}.{{ $col }}'
                                            wire:focus="autoComplete({{ $k }},'{{ $col }}')"
                                            wire:input="autoComplete({{ $k }},'{{ $col }}')"
                                            wire:blur="closeAuto({{ $k }},'{{ $col }}')"
                                            {{-- {{ $this->buildEvents($opts[$col]['onEventFn']) }} --}} 
                                            class='form-control{{ $opts[$col]['widget'] }}'
                                            title="{{ $colArr[$k][$col] }}-{{isset($colArrTitle[$k][$col])?$colArrTitle[$k][$col]:null}}"
                                            style='padding-left:25px;font-size:16px'>

                                        <span wire:click="cleanAuto({{ $k }} , '{{ $opts[$col]['colName'] }}')"
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
                                    @if ($type == 'start' || $type == 'oneRow' || $vis!==null)
                                    <label class='p-2' style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                    @endif
                                    <input type='hidden'
                                        wire:model.debounce.2000ms='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}'>
                                    <input wire:model.debounce.2000ms='colArrPar.{{ $k }}.{{ $col }}'
                                    wire:focus="getClassify({{ $k }} , '{{$col }}' )" wire:input="getClassify({{ $k }} , '{{$col }}' )" wire:blur="closeAuto({{ $k }} , '{{$col}}')"
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
                                        @if ($type == 'start' || $type == 'oneRow' || $vis!==null)
                                        <label class='p-2' style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
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
                                    @if ($type == 'start' || $type == 'oneRow' || $vis!==null)
                                    <label class='p-2' style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                    @endif
                                    <input id="inp_1000" x-ref="picOne" type='file'
                                        wire:model='photos.{{$k}}.{{ $col }}' style="display:none">
                                    <input type='text' wire:model='colArr.{{ $k }}.{{ $col }}' class="form-control"  style="display:none" >
                                    <input type='text' wire:model.debounce.2000ms='colArrPar.{{ $k }}.{{ $col }}' class="form-control" >
                                    <span @click="$refs.picOne.click()"
                                    style="left: 2px;margin-bottom:-28px;background-color:white;float:left ;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:-33px;  z-index: 100;">Upload</span>
                                </div>
                            </div>
                            @endif

                        

                            @if ($opts[$col]['inputType'] == 'date')
                                <div class='{{ $opts[$col]['bootstrap'] }}'>
                                    @if ($type == 'start' || $type == 'oneRow' || $vis!==null)
                                    <label class='p-2' style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{!! $en !!}</label>
                                    @endif
                                    <input wire:model='colArr.{{ $k }}.{{ $opts[$col]['colName'] }}' id=""
                                        data-row='{{ $k }}' data-col='{{ $col }}'
                                        class='form-control {{ $opts[$col]['widget'] }}' title=''>

                                    @error('colArr.' . $k . '.' . $col)
                                        <span class="error" style="text-align:left">
                                            {{ str_replace('arr.', '', $message) }}
                                        </span>
                                    @enderror
                                </div>
                            @endif
                        @endforeach

                        {{--delete button after every form  its complete the form bootstrap--}}

                        @if(isset($opts["delBtVisile"]))  
                            <div class="col-1 text-center">
                                @if ($type == 'start' || $type == 'oneRow' || $vis!==null)
                                <label class='p-2' style="font-size: 15px">Delete</label>
                                @endif
                                <input wire:click="delRow({{$k}})"  class="btn btn-secondary" type="button" value="X" style="width:90%;margin-top:1px">
                            </div>
                        @endif 
                        </div>
                    </div>
                

                  


                @if(isset($opts['bf'][2]) && !empty($opts['bf'][2])) 
                        </div>
                        </div>

                        <div class="col-md-{{$opts['bf'][2]}}">                            
                            <div class="row">
                                <div class="col-12">                            
                                  @if(isset($opts["action2"]))
                                   {!!  $this->excuteFuncAfterEveryRow($k , $opts["action2"]) !!}  
                                  @endif
                                </div>
                            </div>                    
                        </div>            
                @endif

                @if($type !==  'oneRow')
                <hr  style="border:1px solid rgb(104, 104, 104) ; margin-top:2px ; margin-bottom:2px">
                <hr  style="border:1px solid rgb(78, 78, 78); margin-bottom:3px">
                @endif

                @if ($type == 'end' || $type == 'oneRow')
                
                                {{-- excute function at end of every form or row (Create Areaa) --}}
                        @if(isset($opts["action"]))                 
                            {!! $this->excuteFunctionEndOfForm($k , $opts["action"]) !!}           
                        @endif 

                        @if(isset($opts["action1"]))                 
                            {!! $this->excuteFunctionEndOfForm($k , $opts["action1"]) !!}           
                        @endif 

                        {{-- @if(isset($opts["directAion"]))                 
                        <div class="p-2">
                            <input class="btn btn-success" type="button" wire:click="{{str_replace("\\" , "" ,$opts["directAion"]) }}"
                             value="{{ $opts['dActionBtName'] }}">
                        </div>      
                        @endif  --}}

                       

                             {{-- excute function at end of every multiple forms or rows --}}
                
                   
                        @isset($event)
                            <div class="p-2">
                                <input class="btn btn-success" type="button" wire:click="{{ $event }}"
                                    value="{{ $btname }}">
                            </div>
                        @endisset
                   
                        @php $page=str_replace(" ","_",$fn) @endphp
                        @if (isset($bs_arrows[$page]))
                         <div class="paginate pt-2 pb-2" style="margin-bottom:5px">
                            {{ $bs_arrows[$page]->links() }}                        
                         </div>
                        @endif 
                        
                   
                        </div>
                    </div>
                @endif                  

        @endforeach



        </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <input wire:click="acceptAndSave" type="button" class="btn btn-danger" value="حفظ و موافقة">
                    <input wire:click="saveAsDraft" type="button" class="btn btn-primary" value="حفظ كمسودة">
                </div>
            </div>
        </div>

        @if(isset($this->floatDiv))

            <div class="floatDiv">
                <div  class="floaTopBar">
                    <span wire:click="hideFloatComponent" class="closeFloatDiv">X</span>
                </div>
                <div class="floatDivBody">
                      @livewire($this->floatDiv)
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
