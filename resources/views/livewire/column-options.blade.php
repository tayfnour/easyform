<div id="tab03" class="tab-contents el r r el_border_5">

    @if (session()->has('message'))
    <div class="alert alert-success el el_border_5">
        {{ session('message') }}
    </div>


    @endif
    <div class="section el el_border_5">
        <div class="row el el_border_5">
            <div class="col-2 el el_border_5">
                <span class="feildTitle el el_border_5"> العمود 
                    <input type="button" wire:click="copyColOptionOtherForm" value="Copy Col To" class="btn btn-secondary el">
                    <select class="form-control selected_column m-1 el r el_border_5" wire:model="toFormName">
                        @foreach($formNames as $col)
                          <option val="{{$col->formName}}" class="el el_border_5">{{$col->formName}}</option>
                        @endforeach
                    </select>  
                </span> 
            </div>
            <div class="col-2 el el_border_5">
                <select class="form-control selected_column m-1 el r el_border_5" wire:model="colName">
                            @foreach($columns as $col)
                              <option val="{{$col}}" class="el el_border_5">{{$col}}</option>
                            @endforeach
                </select>
            </div>
            <div class="col-2 el el_border_5">
                <span class="feildTitle el el_border_5"> الجدول </span>
            </div>
            <div class="col-2 el el_border_5">
                <select wire:change="changeTable()" class="form-control selected_column m-1 el r el_border_5" wire:model="table">
                            @foreach ($tableNames as $key => $tb)
                                <option class="el el_border_5">{{$tb->Tables_in_easypanel}}</option>
                            @endforeach
                </select>
            </div>
            <div class="col-2 el el_border_5">
                <span class="feildTitle el el_border_5"> الفورم 
                    <input type="button" wire:click="updateFormName" value="Up Form Name" class="btn btn-secondary el">
                    <input type="text" wire:model="newFormName1" class="form-control h-50 el">                    
                </span>
            </div>
            <div class="col-2 el el_border_5">
                      <select class="form-control selected_column m-1 el r el_border_5" wire:model="formName">
                        @foreach($formNames as $col)
                          <option val="{{$col->formName}}" class="el el_border_5">{{$col->formName}}</option>
                        @endforeach
                         </select>
            </div>
        </div>
    </div>


    <div style="padding: 10px" class="el el_border_5">
        <table class="table el el_border_5" id="col_option_table" style="width:100%">

            @php   $ic = 1;   @endphp

{{-- {{dd( $colOptions)}} --}}
          
            @foreach( $options as $key => $col)

            @if (fmod($ic , 3) == 1 )
            <tbody class="el el_border_5">
                <tr class="el el_border_5">
                    @endif
                        <td class="el el_border_5" id="option_key_{{$ic}}" style="font-weight:700;padding:5px;width:10%;background-color:rgb(238, 233, 223)">
                            {{$key}}
                        </td>
                        <td class="key_Val_opt el el_border_5">
                            <input wire:model="options.{{$key}}" type="text" class="form-control" id="option_value_{{$ic}}"  >
                        </td>

                    @if (fmod($ic , 3) == 0 )
                </tr>
                @endif

                @php   $ic++;   @endphp
                @endforeach      

            </tbody>
        </table>

        <input id="saveOption" type="button" class="btn btn-primary mt-2 el el_border_5" value="Save Option">
    </div>

    <hr class="el">

    <div class="section el el_border_5">

        <div class="row el el_border_5">
            <div class="col-12 el el_border_5">
                <input value="خيارات فورم جديد" type="button" class="btn btn-dark selected_column m-1 el r el_border_5" wire:click="createNewFormOptions">
            </div>
        </div>


        <div class="row el el_border_5">
            <div class="col-2 el el_border_5">
                <span class="feildTitle el el_border_5"> نوع الفورم </span>
            </div>
            <div class="col-2 el el_border_5">
                <select class="form-control selected_column m-1 el r el_border_5" wire:model="formType">
                    <option val="0" class="el">_select_</option>
                    <option val="0" class="el">Insert 0</option>
                    <option val="1" class="el">Update 1</option>
                    <option val="2" class="el">Select 2</option>
                </select>
                @error("formType")
                <span class="error el">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-2 el el_border_5">
                <span class="feildTitle el el_border_5"> الجدول </span>
            </div>
            <div class="col-2 el el_border_5">
                <select class="form-control selected_column m-1 el r el_border_5" wire:model="newtable">
                    <option class="el el_border_5">_select_</option>               
                            @foreach ($tableNames as $key => $tb)
                                <option class="el el_border_5">{{$tb->Tables_in_easypanel}}</option>
                            @endforeach
                </select>
                <div class="el">
                    @error("newtable")
                    <span class="error el">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-2 el el_border_5">
                <span class="feildTitle el el_border_5"> الفورم </span>
            </div>


            <div class="col-2 el el_border_5">
                <input type="text" class="form-control selected_column m-1 el r el_border_5" wire:model="newformName">
                <div class="el">
                    @error("newformName")
                    <span class="error el">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="el">
                <select class="form-select">             
                @foreach ($formsforTable as $k => $tb )    
                    <option style="font-weight:700" class="el">{{$k}}</option>
                @foreach ($tb as $k1 => $fr )
                     <option style="color:royalblue" class="el">&nbsp;&nbsp;&nbsp;&nbsp;{{$k1}}/formType({{$fr[0]->formType}})</option>
                @endforeach   
                @endforeach
                </select>

            </div>
            <div class="el">
                <ul class="el">
                    <li class="el">formType 0 = insert</li>
                    <li class="el">formType 1 = update</li>
                    <li class="el">formType 2 = select</li>
                    <li class="el"></li>
                    <li class="el"></li>
                </ul>
            </div>
        </div>
    </div>
</div>