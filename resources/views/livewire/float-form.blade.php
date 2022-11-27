<div>  

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
              
            @endphp
                

                        @if ($type == 'start' || $type == 'oneRow')

                        <div class="col-12 form-container">{{--start container of Form --}} 
                            <div class="row p-1">
                                <div class="col-12 form-bg" > {{--height:45px;width:30%--}}
                                    
                                    <div class="row">
                                        <div class="col-12 form-header p-1" style="color:white;background-color:#01317b">

                                                                                                    
                                               <span class="resize-form" wire:click="closeForm('{{$fn}}')">&#9650;</span> 
                                       

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
                                                    @include("livewire.control-loop")
                                                </div>
                                            </div> 
                                                                       
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
                             @php $page=str_replace(" ","_",$fn) @endphp
                             @if (isset($bs_arrows[$page]))
                                 <div class="paginate pt-2 pb-2" style="margin-bottom:5px">
                                     {{ $bs_arrows[$page]->links() }}
                                 </div>
                             @endif 
                             
                             

                            <div class="row"> {{--form-footer--}}
                                <div class="col-12 form-Footer" style="background-color: #ccc">
                                    form-Footer                              
                                </div>
                            </div>
                      

                        </div>{{--white backgrpund--}}
                    </div> {{-- End Row of Form --}}           
                </div>{{-- End container of Form --}}           
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


    @if (isset($this->msgs))
        <div id="topMsg" class="topMsg">
            {!! implode('<br>', $this->msgs) !!}
            @php $this->msgs=null @endphp
        </div>
    @endif

</div>
