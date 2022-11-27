<div>
    @inject('bladee' , '\App\MyClass\formBuilder')
    <div x-data="initApp()" x-init="initFunc()">
   <div class="container-fluid fluid_1">
        <div class="row pt-1 px-1 mx-1" style="border-radius:10px;background-color:rgb(255, 252, 80)">
            <div class="col-4">
                <h4>
                   Point Of Sales (v1.0)
                </h4>
            </div>

            <div class="col-2">
                <h4 style="color:rgb(20, 220, 53)">
                    {{-- Validate State : "" --}}
                </h4>
            </div>

            <div class="col-3">
                <input wire:click="init" class="btn btn-warning btn-xs" type="button" value="Refresh">
                <input wire:click="showGlobalVar" class="btn btn-warning btn-xs" type="button" value="global Var">
                <input wire:click="showFloatComponent('tree-manager-component')" class="btn btn-warning btn-xs"
                    type="button" value="Tree Management">
            </div>

            <div class="col-3">
                <h4 class="text-end">
                    برنامج نقاط البيع
                </h4>
            </div>
        </div>
    </div>{{-- end first container --}}

    <div class="container-fluid my-3 fluid_2">
        <div class="row pt-1" style="direction: rtl;background-color:#eee"> 
            <div class="col-12">

              <template x-for="fop in formOptions">
                  <template  x-if="showingForm[fop.proForm]=='none'">
                    <div class="invisible-form" @click="showForm(fop.proForm)" ><span>&#11200;</span><span style="margin-left:5px" x-text="fop.description"></span></div>    
                  </template>
              </template>  

            </div>

           
        </div>
    </div>   

    <div class="container-fluid my-3 fluid_3">
        <div class="row pt-1" style="direction: rtl;background-color:#eee"> 
            <div class="col-12">
                <div class="float-form newInvoice" @click="newInvoice()" ><span>&#11200;</span><span style="margin-left:5px">فاتورة جديدة</span></div>
                    <template x-for="inv in invoices">
                        <template x-if="inv.state == 'hold'">
                            <div class="float-form newInvoice" @click="openInvoice(inv.ref)" ><span>&#11200;</span><span style="margin-left:5px" x-text="inv.ref"></span></div>
                        </template>
                    </template>
            </div>
        </div>
    </div>   

    <div class="container-fluid fluid_4"  >{{-- start second container --}}

        <div class="row row_main" style="direction: rtl;border-radius:10px ;background-color:wheat">      
            

            @foreach ($colArr as $k => $val) {{--$k is Row_id --}}
                @php
                    $opts = $formOpts[$rowOpts[$k][0]]; // get option of $row id  =$k
                    $type = $rowOpts[$k][1]; // type of form
                    $pk = $opts['pk'];
                    $fn = $opts['fn'];
                    $vis = $opts['vis'];
                    $tb = $opts['tbName'];
                    $event = $opts[$pk]['onEventFn'];
                    $btname = $opts[$pk]['lookup'];   
                    $fopts= $fOpts[$fn]; //Form Options                
                @endphp            
                        {{-- @if ($fopts["isOpen"] == 1) --}}

                                @if ($type == 'start' || $type == 'oneRow')

                                    <div  x-show="showingForm['{{$fn}}'] == undefined" class="col-{{isset($opts["formColsCount"])?$opts["formColsCount"]:"12"}} form-container" wire:ignore>{{--column : start container of Form --}} 
                                    
                                        <div class="row p-1 row_internal"> {{-- row : start Row of Form --}}
                                        
                                        <div class="col-12 form-bg col_internal" style="border-radius:8px">{{-- column : start container of Form --}}
                                        
                                        @if(!isset($opts["hideHeader"]))  

                                            <div class="row"> {{-- row : start Row of Header --}}

                                                <div class="col-12 form-header p-1"  style="border-radius:8px 8px 0px 0px" >

                                                        {{-- @if($fopts["isOpen"])                                                             --}}
                                                        <span @click="hideForm('{{$fn}}')" class="resize-form" wire:click="closeForm('{{$fn}}')">&#9650;</span> 
                                                        {{-- @endif --}}

                                                        <span wire:click="$emit('setFormAndTableName','{{$tb}}' , '{{$fn}}')" style="cursor:pointer;line-height:30px">
                                                            {{ $fopts['description'] }}
                                                        </span>

                                                        @if(!isset($opts["hideFormEngTitle"]))
                                                            <span  style="cursor:pointer;line-height:30px;float:left">{{ $fn }}-<b>{{ $tb }}</b></span>  
                                                        @endif                     
                                                </div>
                                            
                                            </div>
                                        @endif

                                    @if(isset($opts["widgetBefore"]))                                   
                                    {!! $this->getWidgetBeforeContent($k , $fn) !!}
                                    @endif 

                                    @if(isset($opts["includeBlade"]))                                                                                                 
                                        @include("livewire.widgets.".$opts["includeBlade"])
                                    @endif    
                                    
                            
                                    <div class="row  body_row">    <!-- body row one time-->
                                @endif
                                
                                    <div  class="col-{{isset($opts["bodyColsCount"])?$opts["bodyColsCount"]:"12"}} p-1 fbody form-body pb-2">
                                            <div class="row reapeted-row">
                                                {{--widgetBefore every row--}}  
                                                @if(isset($opts["rightSide"]))
                                                    {!! $this->getRightSideContent($k,$fn) !!}
                                                @endif     
                                                {{--Row Area--}}  
                                                @if(!isset($opts["hideFormBody"]))   
                                                
                                                    {{-- @foreach ($opts['cols'] as $col)                        
                                                         @php 
                                                                                                                                           
                                                                    $en = "<span class='label-en'>(".$opts[$col]['colName'].")</span>" ; 
                                                                      
                                                                     if($opts[$col]["inputType"] !==  "none")                                                                 
                                                                     echo Blade::render($bladee::switchType($opts[$col]["inputType"]), [
                                                                                "k" => $k,
                                                                                "col" => $col,
                                                                                "type" => $type,
                                                                                "message" => isset($message) ? $message :"",
                                                                                "opts" => $opts,            
                                                                            ]);
                                                                   
                                                                   //  echo  new \App\MyClass\formBuilder($opts , $k , $col , $type , "" );

                                                               @endphp
                                               
                                                    @endforeach   
                                                 --}}
                                                @endif
                                                {{--leftSide every row--}}  
                                                @if(isset($opts['leftSide']))                                                         
                                                {!!  $this->getLeftSideContent($k , $fn) !!} 
                                                @endif                        
                                            </div>  

                                        </div>

                                                            
                                    

                                {{-- @if ($type !== 'oneRow')
                                <hr style="border:1px dashed rgb(247, 247, 247) ; margin-top:-5px ; margin-bottom:0px">
                                @endif --}}
                                
                            @if ($type == 'end' || $type == 'oneRow')
                            </div> {{--body row end--}}

                            @if(isset($opts["widgetAfter"]))
                            {!! $this->getWidgetAfterContent($k , $fn) !!}
                            @endif   
                                
                                <div class="row"> <!---row to button area--->
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
                                @if(!isset($opts["hidePagination"]))
                                    @php $page=str_replace(" ","_",$fn) @endphp
                                    @if (isset($bs_arrows[$page]))
                                    <div class="paginate pt-2 pb-2" style="margin-bottom:5px">
                                        {{ $bs_arrows[$page]->links() }}
                                    </div>
                                @endif 
                            @endif
                               
                                

                        @if(isset($opts["footerForm"]))
                        <div class="row"> {{--form-footer--}}
                            <div class="col-12 form-Footer" style="border-radius:0px 0px 5px 5px;background-color: #ccc">
                                {!! $this->getFooterFormContent($k, $fn) !!}                           
                            </div>
                        </div>
                        @endif
                        

                        </div>{{--white backgrpund--}}
                        </div> {{-- End Row of Form --}}           
                        </div>{{-- End container of Form --}}   
                    @endif  
                    @endif 
                   {{--  @endif--end of if isopen--}}
            @endforeach
        </div>{{-- end second Row --}}

        
      {{-- {{dd($this->passToAlpine)}} --}}
    
  

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

   
    @include("livewire.js_script.sales-points-js") 
  

    </div>  
</div>



