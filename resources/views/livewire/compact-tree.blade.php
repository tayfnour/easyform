<div class="el el_border_1 r">
        <div class="card el el_border_1">
            <div class="card-header el el_border_1 r r"><span style="font-size: 22px" class="el el_border_1"><span>Tree Managment-{{$ic}}</span>
                
                <div class="container">
                    <div class="row">
                        <div class="col-md-5">
                            <span class="text-end el" style="float:right;position:relative">
                                <input wire:model.bebounce.500ms="SearchIn" type="text" class="search_in el" placeholder="Search" style="width:90%;border-color:#ddd;  border-radius:5px">                       
                                <img src="{{asset('assets/images/se.png')}}" style="display:inline-block;width: 22px;height:15px;position:relative;left: -5px;top: -38px;" class="el">                   
                                @if(!empty($autoTreeSearch))
                                  <div style="font-size:12px;background-color:white;width:150%;position:absolute;border:1px solid #ddd;text-align:left;padding:5px;height:100px;z-index:100;overflow-y:auto" class="el">{!! $autoTreeSearch !!}</div>
                                @endif
                            </span>
                        </div>
                        <div class="col-md-7">
                            <button class="btn btn-primary el el_border_1  p-2  btnh" id="createTreeTable" style="float:right">New Tree</button>

                            <select class="form-select mr-1 el el_border_1" wire:model="tbName" style="float:right;display:inline-block;width:200px">
                                @foreach($tableNames as $item)
                                    <option value="{{$item}}" class="el el_border_1">{{$item}}</option>
                                @endforeach           
                            </select>
                        </div>
                    </div>
                </div>        
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary el el_border_1  p-2  m-1  printTree r  btnh" id="createTreeTable" style="float:right" value="Print">Print</button>
                        <button class="btn btn-secondary el el_border_1  p-2  m-1  printTree  btnh" id="openAllNode" style="float:right" value="">Open All Node</button>
                     </div>
                </div>     
            </div>


            <div class="card-body el el_border_1" style="background-color: #fcfcfc;">

                <div id="treeWrap" style="height:600px;overflow:auto;direction:rtl;padding:5px; align-items: top; justify-content:right;" class="el el_border_1">
 
                        <div id="treeCard" class="el el_border_1">
                         
                                {!!  $Dtree !!}
                          
                        </div>                 
                      

                        @if(session()->has('message'))

                        <div id="successMessage" class="alert alert-success el el_border_1">

                            <button type="button" class="close el el_border_1" data-dismiss="alert">×</button>

                            <i class="fa fa-times mr-1 el el_border_1"></i>
                            {{ session('message') }}

                        </div>
                        @endif
                 </div>            
            </div>

        </div>   
</div>