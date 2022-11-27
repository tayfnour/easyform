<div>
    <div class="container-fluid  ">
        <div class="card">
            <div class="card-header"><span style="font-size: 22px" class=" ">Tree Managment-{{$ic}}</span>
                <button class="btn btn-primary    p-2  btnh" id="createTreeTable" style="float:right">New Tree</button>

                <select class="form-select mr-1  " wire:model="tbName" style="float:right;display:inline-block;width:200px">
                    @foreach($tableNames as $item)
                        <option value="{{$item}}" >{{$item}}</option>
                    @endforeach           
                </select>



                <span class="text-end el" style="float:right;position:relative">
                    <input wire:model.bebounce.500ms="SearchIn" type="text" class="search_in el" placeholder="Search" style="border-color:#ddd; padding:5px ; border-radius:5px">                       
                    <img src="{{asset('assets/images/se.png')}}" style="display:inline-block;width: 22px ;height:15px;position:relative;left:-30px" class="el">                   
                    @if(!empty($autoTreeSearch))
                      <div style="font-size:12px;background-color:white;width:150%;position:absolute;border:1px solid #ddd;text-align:left;padding:5px;height:100px;z-index:100;overflow-y:auto" class="el">{!! $autoTreeSearch !!}</div>
                    @endif
                </span>
                <button class="btn btn-primary     p-2  m-1  printTree r  btnh" id="createTreeTable" style="float:right" value="Print">Print</button><button class="btn btn-secondary     p-2  m-1  printTree  btnh" id="openAllNode"
                    style="float:right" value="">Open All Node</button>
            </div>


            <div class="card-body cardFloatBody" style="background-color: #fcfcfc;">

                <div id="treeWrap" style="display:flex;direction:rtl;padding:5px; align-items: top; justify-content:right;"   >

                    <div id="treePart" style="flex:1.5;padding:10px" class="treeside   ">

                        <div id="treeCard" class="card p-3   ">
                            <div id="insidCard" class="insidCard el">
                                {!!  $Dtree !!}
                            </div>
                        </div>

                    </div>


                    <div class="formside   " style="flex: 1;padding:10px">



                        <table class="table   ">
                            <tbody   >
                                <tr   >
                                    <td   >parent</td>
                                    <td   >Code</td>
                                    <td   >Is End</td>
                                </tr>
                                <tr   >
                                    <td   >
                                        {{$parent}}
                                    </td>


                                    <td>
                                        {{$code}}
                                    </td>


                                    <td>
                                        <input wire:model="is_end" class="form-checkbox checkbox-2x   " type="checkbox" wire:change="setEndChildState( {{$parent}} , {{$code}} , $event.currentTarget.checked )" checked="{{ $is_end>0 ? '':'false'}}">
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <input type="button" class="btn btn-dark btn-medium     btnc" value="Update Node" wire:click="setNodeName( {{$parent}} , {{$code}} , '{{$node}}' )">

                                    </td>
                                    <td    colspan="5">
                                        <textarea wire:model="node" class="form-control   " style="width:75%;display:inline-block;position: relative;top: 3px;"></textarea>

                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <input type="button" class="btn btn-warning     btnc" value="Add Sibiling Node" wire:click="addNode( {{$parent}} , '{{$newNode}}' )">
                                    </td>
                                    <td    colspan="5">
                                        <textarea wire:model="newNode" class="form-control   " style="width:75%;display:inline-block;position: relative;top: 3px;"></textarea>

                                    </td>
                                </tr>

                                @if($is_end==0)
                                <tr>
                                    <td>
                                        <input type="button" class="btn btn-info     btnc" value="Add Child" wire:click="addChild( {{$code}} , '{{$newChild}}' ,  {{$is_end}})">
                                    </td>
                                    <td    colspan="5">
                                        <textarea wire:model="newChild" class="form-control   " style="width:75%;display:inline-block;position: relative;top: 3px;"></textarea>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>
                                        <input type="button" class="btn btn-secondary  btnc" value="Update Order" wire:click="updateOrder(  {{$code}} , '{{$list_order}}' )">
                                    </td>
                                    <td    colspan="5">
                                        <input wire:model="list_order" class="form-control   " type="text" style="width:75%;display:inline-block;position: relative;top: 3px;">
                                        @if($confirming==="ok")
                                        <button wire:click="kill( {{$parent}} ,{{ $code }} )" class="btn btn-danger   ">? Sure </button>
                                        @else<button wire:click="confirmDelete()" class="btn btn-dark   ">Delete</button>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        @if(session()->has('message'))

                        <div id="successMessage" class="alert alert-success">

                            <button type="button" class="close" data-dismiss="alert">×</button>

                            <i class="fa fa-times mr-1   "></i>
                            {{ session('message') }}

                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>