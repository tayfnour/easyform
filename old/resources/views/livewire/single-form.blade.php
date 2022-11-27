<div>
    <div class="container form_header">
        <div class="row">
            <div class="col-12">
                <div class="m-2" style="color:crimson;display: flex;width:50%;margin: 0 auto"> 
                    <h3>  {{ str_replace('_', ' ', $formName) }}</h3>
                    <h3 class="px-2" style="color:blue">  {{ $formTranslate }}</h3>
                </div>
                <h4 class="m-2" style="color:rgb(20, 220, 53)"> {{ $opts[$autoKey]['tableName'] }} - {{session()->getId()}} - Validate State {{$validateState}}</h4>
              
                <div>
                    <select wire:model="formNameSwip"  class="form-select">
                                  
                            @foreach ($formNames as $k => $tb )    
                                <option style="font-weight:700" class="el">{{$k}}</option>
                            @foreach ($tb as $k1 => $fr )
                                 <option style="color:royalblue" class="el">&nbsp;&nbsp;&nbsp;&nbsp;{{$k1}}/formType({{$fr[0]->formType}})</option>
                            @endforeach   
                            @endforeach
                           
                    </select>
                </div>
                <hr>
            </div>
        </div>

    </div>

    <div class="container">
        <form>
            <div class="row m-3" style="direction: rtl">
                @foreach ($columns as $col)
                
                @php $en = "(".$opts[$col]['colName'].")";@endphp
                     

                    @if ($opts[$col]['inputType'] == 'single_image')
                    <div class='{{ $opts[$col]['bootstrap'] }}' x-data="">
                        <div style="position: relative">
                            <span @click="$refs.picOne.click()"
                                style="float: left;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:45px;  z-index: 100;">Upload</span>
                            <label class='p-2'
                                style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{{$en}}</label>
                            <input id="inp_1000" x-ref="picOne" type='file' wire:model='onePhoto.{{$col}}'
                                style="visibility:hidden">
                            <input  type='text' wire:model='colArr.{{$col}}' class="form-control">    
                            <div class="shadow"
                            style="border:2px solid #ddd;width:100%;z-index:10;background-color:white;padding:5px" >
                            
                            @if(isset($colArr[$col]))
                                  <img src="http://localhost/global_images/{{ $colArr[$col] }}"  style="max-height:150px"  alt=""> 
                                @endif

                                @if(isset($onePhoto[$col]))                               
                                 <img src="{{ $onePhoto[$col]->temporaryUrl() }}" style="max-height:150px"  alt=""> 
                                @endif
                            </div>    
                        </div>
                    </div>                                 
                    @endif
                        

                    @if ($opts[$col]['inputType'] == 'image')
                        <div class='{{ $opts[$col]['bootstrap'] }}' x-data="">
                            <div style="position: relative">
                                <span @click="$refs.picEle.click()"
                                    style="float: left;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:45px;  z-index: 100;">Upload</span>
                                <label class='p-2'
                                    style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{{$en}}</label>
                                <input id="inp_1000" x-ref="picEle" type='file' wire:model='photos.{{ count($photos) }}'
                                    style="visibility:hidden">

                                <input wire:model='colArr.{{ $opts[$col]['colName'] }}'
                                    {{ $this->buildEvents($opts[$col]['onEventFn']) }}
                                    class='form-control{{ $opts[$col]['widget'] }}'
                                    style='padding-left:25px;font-size:16px'>


                                @error('colArr.' . $col)
                                    <span class="error" style="text-align:left">
                                        {{ str_replace('arr.', '', $message) }}
                                    </span>
                                @enderror

                                @if (!empty($autoArr[$col]) || !empty($photos))
                                    <div class="shadow"
                                        style="border:2px solid #ddd;width:100%;z-index:200;position:absolute;background-color:white;padding:5px">

                                        @if (isset($autoArr[$col]) && strlen($autoArr[$col]) > 0)
                                            <div class="mb-2 p1" styl='width:100%;overflow-y:auto;'>
                                                {!! $autoArr[$col] !!}
                                            </div>
                                        @endif

                                        @if (!empty($photos))
                                            <div class="mb-2 border p1" style="display:flex;width:100%;overflow-y:auto;">
                                                @foreach ($photos as $k => $item)

                                                    <div class="m-1 p-1 text-center" style="border:1px solid #ddd">
                                                        <div>
                                                            @error("photos_desc.{$k}")
                                                                <span class="error">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <input wire:model="photos_desc.{{ $k }}"
                                                            class="form-control" type="text">
                                                        <img src="{{ $item->temporaryUrl() }}"
                                                            style="height:160px;width:160px " class="mb-1 el">
                                                    </div>

                                                @endforeach
                                            </div>
                                            <div style="text-align: center">
                                                <input
                                                    wire:click="storeImages('{{ $col }}' , '{{ $opts[$col]['lookup'] }}')"
                                                    type="button" class="btn btn-primary" value="حفظ الصور المرفوعة">
                                            </div>
                                        @endif

                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($opts[$col]['inputType'] == 'text')
                        <div class='{{ $opts[$col]['bootstrap'] }}'>
                            <label class='p-2'
                                style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{{$en}}</label>
                            <input wire:model='colArr.{{ $opts[$col]['colName'] }}' class='form-control'>
                            @error('colArr.' . $col)
                                <span class="error" style="text-align:left">
                                    {{ str_replace('arr.', '', $message) }}
                                </span>
                            @enderror

                        </div>
                    @endif

                    @if ($opts[$col]['inputType'] == 'select')
                    <div class='{{ $opts[$col]['bootstrap'] }}'>
                        <label class='p-2' style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{{$en}}</label>
                      
                       <select wire:model='colArr.{{ $opts[$col]['colName'] }}' class='form-select'>                               
                           {!! $this->getOptionsofCol($opts[$col]['logicalVal'] , $col ,$opts[$col]['colType']) !!}
                       </select>

                        @error('colArr.' . $col)
                            <span class="error" style="text-align:left">
                                {{ str_replace('arr.', '', $message) }}
                            </span>
                        @enderror

                    </div>
                @endif

                    @if ($opts[$col]['inputType'] == 'readonly')
                        <div class='{{ $opts[$col]['bootstrap'] }}'>
                            <label class='p-2'
                                style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{{$en}}</label>
                            <input wire:model='colArr.{{ $opts[$col]['colName'] }}' class='form-control' readonly>
                            @error('colArr.' . $col)
                                <span class="error" style="text-align:left">
                                    {{ str_replace('arr.', '', $message) }}
                                </span>
                            @enderror

                        </div>
                    @endif

                    @if ($opts[$col]['inputType'] == 'tb_auto')
                    <div class='{{ $opts[$col]['bootstrap'] }}'>
                        <div style="position: relative">
                            <span wire:click="cleanAuto('{{ $opts[$col]['colName'] }}')"
                                style="float: left;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:45px;  z-index: 100;">X</span>
                            <label class='p-2'
                                style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{{$en}}</label>
                            {{-- <input type='hidden' wire:model='colArr.{{ $opts[$col]['colName'] }}'> --}}
                            <input wire:model='colArr.{{ $opts[$col]['colName'] }}'
                            wire:focus="getRowIdFromtable('{{$col}}')" wire:input="getRowIdFromtable('{{$col}}')" wire:blur="closeAuto('{{$col}}')"
                                {{-- {{ $this->buildEvents($opts[$col]['onEventFn']) }} --}}
                                class='form-control{{ $opts[$col]['widget'] }}'
                                style='padding-left:25px;font-size:16px'>


                            @error('colArr.' . $col)
                                <span class="error" style="text-align:left">
                                    {{ str_replace('arr.', '', $message) }}
                                </span>
                            @enderror

                            @if (isset($autoArr[$opts[$col]['colName']]) && strlen($autoArr[$opts[$col]['colName']]) > 0)
                                <div class="shadow"
                                    style="border:2px solid #ddd;width:100%;z-index:1000;position:absolute;background-color:white;padding:5px">
                                    {!! $autoArr[$opts[$col]['colName']] !!}
                                </div>
                            @endif
                        </div>

                    </div>
                @endif


                    @if ($opts[$col]['inputType'] == 'auto')
                        <div class='{{ $opts[$col]['bootstrap'] }}'>
                            <div style="position: relative">
                                <span wire:click="cleanAuto('{{ $opts[$col]['colName'] }}')"
                                    style="float: left;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:45px;  z-index: 100;">X</span>
                                <label class='p-2'
                                    style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{{$en}}</label>
                                <input type='hidden' wire:model='colArr.{{ $opts[$col]['colName'] }}'>
                                <input wire:model='colArrPar.{{ $opts[$col]['colName'] }}'
                                wire:focus="autoComplete('{{$col}}')" wire:input="autoComplete('{{$col}}')" wire:blur="closeAuto('{{$col}}')"
                                    {{-- {{ $this->buildEvents($opts[$col]['onEventFn']) }} --}}
                                    class='form-control{{ $opts[$col]['widget'] }}'
                                    style='padding-left:25px;font-size:16px'>


                                @error('colArr.' . $col)
                                    <span class="error" style="text-align:left">
                                        {{ str_replace('arr.', '', $message) }}
                                    </span>
                                @enderror

                                @if (isset($autoArr[$opts[$col]['colName']]) && strlen($autoArr[$opts[$col]['colName']]) > 0)
                                    <div class="shadow"
                                        style="border:2px solid #ddd;width:100%;z-index:1000;position:absolute;background-color:white;padding:5px">
                                        {!! $autoArr[$opts[$col]['colName']] !!}
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
                                <label class='p-2'
                                style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{{$en}}</label>
                            <input wire:model='colArr.{{ $opts[$col]['colName'] }}'
                                {{ $this->buildEvents($opts[$col]['onEventFn']) }}
                                class='form-control{{ $opts[$col]['widget'] }}'
                                style='padding-left:25px;font-size:16px'>


                            @error('colArr.' . $col)
                                <span class="error" style="text-align:left">
                                    {{ str_replace('arr.', '', $message) }}
                                </span>
                            @enderror

                           
                            @if (isset($autoArr[$col]) && strlen($autoArr[$col]) > 0)
                        
                                <div class="shadow"
                                style="z-index:1000;border:2px solid #ddd;width:100%;position:absolute;background-color:white;padding:5px">
                                 
                                    @livewire("single-form" , ["formName" => "New Product Attribute" , "ref" => $ref ])
                                </div>
                            @endif
                        </div>

                    </div>
                @endif

                @if ($opts[$col]['inputType'] == 'relatedRow')
                <div class='{{ $opts[$col]['bootstrap'] }}'>
                    {{-- <div style="position: relative"> --}}
                    <div>
                        <span wire:click="closeForm('{{ $opts[$col]['colName'] }}')"
                            style="float: left;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position:relative ; top:45px; z-index :100">X</span>
                            <label class='p-2'
                            style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{{$en}}</label>
                        <input wire:model='colArr.{{ $opts[$col]['colName'] }}'
                            {{ $this->buildEvents($opts[$col]['onEventFn']) }}
                            class='form-control{{ $opts[$col]['widget'] }}'
                            style='padding-left:25px;font-size:16px'>


                        @error('colArr.' . $col)
                            <span class="error" style="text-align:left">
                                {{ str_replace('arr.', '', $message) }}
                            </span>
                        @enderror

                       
                        @if (isset($autoArr[$col]) && strlen($autoArr[$col]) > 0)
                    
                            <div class="shadow"
                            style="z-index:1000;border:2px solid #ddd;width:80%;position:absolute;background-color:white;padding:5px">
                                @livewire("db-manage-component" ,["parentForm"=>$formName ,"SearchWord" => $colArr[$col] ]  )
                            </div>
                        @endif
                    </div>

                </div>
            @endif


                    @if ($opts[$col]['inputType'] == 'classify')
                        <div class='{{ $opts[$col]['bootstrap'] }}'>
                            <div style="position: relative">
                                <span wire:click="cleanAuto('{{ $opts[$col]['colName'] }}')"
                                    style="float: left;display:inline-block;padding-right:10px;border-right:1px solid #ccc;cursor:pointer;padding-left:10px;position: relative ; top:45px;  z-index: 100;">X</span>
                                <label class='p-2'
                                    style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{{$en}}</label>
                                <input type='hidden' wire:model='colArr.{{ $opts[$col]['colName'] }}'>
                                <input wire:model='colArrPar.{{ $opts[$col]['colName'] }}'
                                    {{ $this->buildEvents($opts[$col]['onEventFn']) }}
                                    class='form-control{{ $opts[$col]['widget'] }}'
                                    style='padding-left:25px;font-size:16px'>


                                @error('colArr.' . $col)
                                    <span class="error" style="text-align:left">
                                        {{ str_replace('arr.', '', $message) }}
                                    </span>
                                @enderror

                                @if (isset($classify[$col]))
                                    <div class="shadow"
                                        style="height:250px ; overflow-y:auto ;border:2px solid #ddd;width:100%;z-index:1000;position:absolute;background-color:white;padding:5px">
                                        {!! $classify[$opts[$col]['colName']] !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($opts[$col]['inputType'] == 'date')
                        <div class='{{ $opts[$col]['bootstrap'] }}'>
                            <label class='p-2'
                                style="font-size: 15px">{{ $opts[$col]['arabic_name'] }}{{$en}}</label>
                            <input wire:model='colArr.{{ $opts[$col]['colName'] }}' id=""
                                data-col='{{ $col }}' class='form-control {{ $opts[$col]['widget'] }}'
                                title=''>

                            @error('colArr.' . $col)
                                <span class="error" style="text-align:left">
                                    {{ str_replace('arr.', '', $message) }}
                                </span>
                            @enderror
                        </div>
                    @endif


                @endforeach
            </div>
            {!! $this->getActions() !!}
        </form>
    </div>

    @if (isset($this->msgs))
    <div id="topMsg" class="topMsg" >      
       {!! implode('<br>', $this->msgs) !!}
       @php $this->msgs=null @endphp       
    </div>
    @endif

</div>
