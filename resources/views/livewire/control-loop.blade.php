





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
                        style='padding-left:25px;font-size:16px'
                        title='class_id({{$colArr[$k][$col]}})'>

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
            <div>
                {!! $this->excuteAfterWidget($k , $fn , $col) !!} 
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

      
@endforeach
