<div>
    <div class="container">
        <div class="row pt-1" style="margin:5px 18px;border-radius:10px;background-color:rgb(255, 252, 80)">
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
    </div>

    <div class="container">
        {{-- <div class="masonry"> --}}
        <div class="row" style="direction:rtl ; margin-left:5px ;  margin-right:5px">
            {{-- mht: k is row and evry row is form --}}
            @foreach ($colArr as $k => $val)
                @php
                    $arrView = [];
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
                    <div class="{{ 'col-md-' . $opts['bf'][0] }}">

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

                @if (isset($opts['bf'][1]) && !empty($opts['bf'][1]))
                    <div class="col-md-{{ $opts['bf'][1] }}">
                        <div class="row">
                @endif

                <div style="{{ isset($opts['wrapForm']) ? $opts['wrapForm'] : '' }}">
                    {{-- Row of form --}}
                    <div class="row p-1">

                        @include('livewire.control-loop')

                        {{-- delete button after every form  its complete the form bootstrap --}}

                        @if (isset($opts['delBtVisile']))
                            <div class="col-1 text-center">
                                @if ($type == 'start' || $type == 'oneRow' || $vis !== null)
                                    <label class='p-2' style="font-size: 15px">Delete</label>
                                @endif
                                <input wire:click="delRow({{ $k }})" class="btn btn-secondary" type="button"
                                    value="X" style="width:90%;margin-top:1px">
                            </div>
                        @endif

                    </div>{{-- end of row of form --}}
                </div>{{-- end of col-md- --}}



                @if (isset($opts['bf'][2]) && !empty($opts['bf'][2]))
        </div>{{-- end of row --}}
    </div>{{-- end of Container --}}

    <div class="col-md-{{ $opts['bf'][2] }}">

        <div class="row">
            <div class="col-12">
                @if (isset($opts['action2']))
                    {!! $this->excuteFuncAfterEveryRow($k, $opts['action2']) !!}
                @endif
            </div>
        </div>

    </div>
    @endif

    @if ($type !== 'oneRow')
        <hr style="border:1px solid rgb(104, 104, 104) ; margin-top:2px ; margin-bottom:2px">
        <hr style="border:1px solid rgb(78, 78, 78); margin-bottom:3px">
    @endif



    @if ($type == 'end' || $type == 'oneRow')
        {{-- here you can Excute function before close form --}}
        {{-- excute function at end of every form or row (Create Areaa) --}}

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

        {{-- @if (isset($opts['directAion']))                 
                        <div class="p-2">
                            <input class="btn btn-success" type="button" wire:click="{{str_replace("\\" , "" ,$opts["directAion"]) }}"
                             value="{{ $opts['dActionBtName'] }}">
                        </div>      
                        @endif --}}



        {{-- excute function at end of every multiple forms or rows --}}

        {{-- @isset($event)
            <div class="p-2">
                <input class="btn btn-success" type="button" wire:click="{{ $event }}" value="{{ $btname }}">
            </div>
        @endisset --}}




        {{-- pagination --}}
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


@if (isset($this->msgs))
    <div id="topMsg" class="topMsg">
        {!! implode('<br>', $this->msgs) !!}
        @php $this->msgs=null @endphp
    </div>
@endif

</div>
