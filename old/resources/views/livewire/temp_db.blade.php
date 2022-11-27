
<div>
    <div class="container-fluid">

       <div class="row">

          <div class="col-md-12 p-3">

              <div class = "card">

                  <div class = "card-header">
                              <div class="row" >

                                      <div class="col-md-6" >   
                                          <div  class="p-2">                          
                                              <input class="form-control rounded p-1" placeholder="Search Word" type="text"   wire:model="SearchWord" />
                                          </div>    
                                      </div>    
                                      <div class="col-md-3" >
                                          <div class="p-2">
                                              <select class="form-control" wire:model="table"  wire:change="tableChanged()">
                                  
                                                  @foreach ($table_names as $key => $tb)
                                                      <option>{{$tb->Tables_in_easypanel}}</option>
                                                  @endforeach

                                              </select>
                                          </div>
                                      </div>   
                                      <div class="col-md-3" >   
                                          <div  class="p-2">                          
                                              <input class="form-control rounded p-1" placeholder="Search Word" type="text"   wire:model="primaryKey" />
                                          </div>    
                                      </div>                      
                              </div>                   
                              </div>

                          <div class = "panel-body"  style="overflow-x:auto">
                          
                              @if($rows->count()>0)
                                  
                              <table class = "table table-bordered table-striped table-responsive-md p-3" style=" width:100%">
                                      {{--dd($cols_Options)--}}
                                      <tr>                    
                                          @foreach($rows[0] as $key => $row) 
                                          
                                          @if($cols_Options[$key]["arabic_name"]!=="")
                                              <th style="color:blue">{{ $cols_Options[$key]["arabic_name"] }}</th>
                                              @else
                                              <th style="color:blue">{{ $key }}</th>
                                              @endif 
                                          @endforeach    
                                              <th style="color:blue">Actions</th>                    
                                      </tr>   

                                      {{-- Starting fill table --}}

                                      @foreach($rows as $row)    
                                          <tr>
                                          @foreach($rows[0] as $key => $row1) 
                                          
                                          @if ($cols_Options[$key]["inputType"]=="image")
                                          <td><img src="http://localhost/global_images/{{ $row->$key }}"  style="height: 150px" /></td>
                                      
                                          @elseif ($cols_Options[$key]["inputType"]=="textarea")                                               
                                              <td>{{ substr($row->$key,0,50)}}  <br> ... </td>
                                          @else
                                              <td>{{ $row->$key }}</td>
                                          @endif
                                          @endforeach   
                                          
                                              <td>
                                              <a wire:click.prevent = "EditRow({{$row->$primaryKey}})" class="btn btn-xs btn-warning" href="#">edit</a>
                                              </td>  
                                          </tr>

                                      @endforeach           
                      
                                  </table>
                          
                          @else

                              <div  class="alert alert-warning text-center m-2">لا يوجد أي سجلات في  الجدول  المنتقى{{$table}}</div>

                          @endif  
                  </div>

                  <div class="paginate pt-2 pb-2">{{$rows->links()}}</div>
              </div>

          </div>
      </div>

      
      <div id="tabs"  class="tabs">
              <div class="tab-button-outer">
                  <ul id="tab-button">
                  <li  id="htab_1" class="htab" ><a href="#tab01">-Update</a></li>
                  <li id="htab_2"  class="htab" ><a href="#tab02">-Insert</a></li>
                  <li id="htab_3"  class="htab"><a href="#tab03">-Cols Options</a></li>
                  <li id="htab_4"  class="htab" ><a href="#tab04">-Tab 4</a></li>
                  <li id="htab_5"  class="htab"><a href="#tab05">-Tab 5</a></li>
                  </ul>
              </div>
              <div class="tab-select-outer">
                  <select id="tab-select">
                  <option value="#tab01">Update</option>
                  <option value="#tab02">Insert</option>
                  <option value="#tab03">Cols Options</option>
                  <option value="#tab04">Tab 4</option>
                  <option value="#tab05">Tab 5</option>
                  </select>
              </div>
               {{-- Tab1 Edit Form --}}
               <div id="tab01" class="tab-contents">      
                   
                  @if ($errors->any())
                      @foreach ($errors->all() as $error)
                          <div  class="errors"  style="background-color: #ddd">{{$error}}</div> 
                      @endforeach
                  @endif

                  @if (session()->has('message'))
                      <div class="alert alert-success">
                          {{ session('message') }}
                      </div>
                  @endif

            
                  <table  class="table  style="width: 100%" >      
                      
                      @foreach($columns as $key )       
                          <tr>                                 
                          {{-- TYPE=IMAGE     --}}
                          <td style="color:blue;width: 25%!important;">{{ $key }}</td>
                         
                          @if ($cols_Options[$key]["inputType"]=="image")                           
                          <td><input style="padding: 4px;" class="form-control rounded {{$cols_Options[$key]["widget"]}}" data-lookup="{{$cols_Options[$key]["lookup"]}}" type="file" wire:model.defer="row_update.{{$key}}" >
                          <span    wire:loading wire:target='image'>Uploading...</span>


                          @if (isset($row_update['image'])) 
                              @if (is_object($row_update['image']))
                                  <span>
                                      <img src="{{ $row_update['image']->temporaryUrl() }}" style="width:150px">
                                  </span>
                               @else
                                 <span>
                                  <img src="http://localhost/global_images/{{ $row_update['image'] }}" style="width:150px">
                                </span>
                              @endif 
                          @endif 
                          </td>

                          @elseif ($cols_Options[$key]["inputType"]=="textarea")
                          <td><textarea class="form-control rounded {{$cols_Options[$key]["widget"]}}"                                
                              wire:input.debounce.500ms="getCat( '{{$key}}','{{$cols_Options[$key]["lookup"]}}')"
                              wire:model.dbounce.500ms="row_update.{{$key}}" ></textarea>
                              <input class="nameOfParInput" wire:model="row_update_par.{{$key}}" readonly>
                          </td>    
                          @else
                          <td><input class="form-control rounded {{$cols_Options[$key]["widget"]}}" type="text"
                              wire:input.debounce.500ms="getCat( '{{$key}}','{{$cols_Options[$key]["lookup"]}}')"
                              wire:model="row_update.{{$key}}" >
                              <input class="nameOfParInput" wire:model="row_update_par.{{$key}}" readonly>
                          </td>
                          @endif
                          </tr>
                      @endforeach  
                  </table>      
                  <input class="btn btn-success bg-blue-500 rounded p-1" type="button"  wire:click="updateRow"  value="update"      />         

              </div>
               {{-- Update Tab --}}
              <div id="tab02" class="tab-contents" style="height: 550px; overflow-y:auto">
                   
                  @if ($errors->any())
                  @foreach ($errors->all() as $error)
                      <div  class="errors"  style="background-color: rgb(255, 206, 218)">{{$error}}</div> 
                  @endforeach
                  @endif

                
                  @if (session()->has('message'))
                      <div class="alert alert-success">
                          {{ session('message') }}
                      </div>
                  @endif
                  

                        
                  <table  class="table" style="width: 100% ;margin-top:5px">      
                      
                      @foreach($columns as $key)  
                          @if($key !=  $primaryKey)     
                              <tr>                 
                              <td  style="color:blue;width: 25%!important;">{{ $key }}</td>
                              @if ($cols_Options[$key]["inputType"]=="image")

                          
                              <td><input style="padding: 4px;" class="form-control rounded" type="file" wire:model.defer="row_insert.{{$key}}" >
                              <span    wire:loading wire:target='image'>Uploading...</span>
                              @if (isset($row_insert['image'])) 
                                  @if (is_object($row_insert['image']))
                                      <span>
                                          <img src="{{ $row_insert['image']->temporaryUrl() }}" style="width:150px">
                                      </span>
                                  @endif 
                              @endif 
                              </td>                                
                              @elseif ($cols_Options[$key]["inputType"]=="textarea")
                              <td><textarea class="form-control rounded"  wire:model="row_insert.{{$key}}" style="height: 150px" ></textarea></td>
                              @else
                              <td><input class="form-control rounded" type="text" wire:model="row_insert.{{$key}}" ></td>
                              @endif
                              </tr>
                          @endif    
                      @endforeach  
                  </table>      
                  <input class="btn btn-danger p-1" type="button"  wire:click="insertRow"  value="insert"      />         

            
              </div>     

              <div  id="tab03" class="tab-contents">

                  @if (session()->has('message'))
                  <div  class="alert alert-success">
                      {{ session('message') }}
                  </div>      
                  
                  
              @endif

                   {{-- choose column to edit it --}}

                    {{-- {{dd($columns)}} --}}

                      <div> 
                          <select class="form-control selected_column m-1" wire:model="selected_col"  >
                              @foreach($columns as $col)
                                <option val="{{$col}}">{{$col}}</option>
                              @endforeach
                          </select>
                      </div>    


                      <div style="padding: 10px">
                      <table class="table" id="col_option_table"  style="width:100%" >    


                      {{-- {{ dd($selected_col) }} --}}
                          
                              @php   $ic = 1;   @endphp

                                                                   
                                      @foreach($cols_Options[$selected_col] as $key => $col) 
                                          
                                          @if (fmod($ic , 3) == 1 )  <tr>  @endif

                                              <td class="key_Val_opt" id="option_key_{{$ic}}"  style="padding:5px;width:10%;background-color:rgb(238, 233, 223)" >{{$key}}</td>
                                              <td class="key_Val_opt" id="option_value_{{$ic}}" style="padding:5px;;background-color:rgb(255, 255, 255)" contenteditable>{{$col}}</td>
                                          
                                          @if (fmod($ic , 3) == 0 )  </tr>  @endif

                                      @php   $ic++;   @endphp  
                                      
                                          
                                      @endforeach 

                          </table>  
                          
                          <input  id="saveOption"  type="button" class="btn btn-primary mt-2"  value="Save Option">
                      </div>
              </div>    
          
      
              <div id="tab04" class="tab-contents">
                  <h2>Tab 4</h2>tab4
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius quos aliquam consequuntur, esse provident impedit minima porro! Laudantium laboriosam culpa quis fugiat ea, architecto velit ab, deserunt rem quibusdam voluptatum.</p>
              </div>
              <div id="tab05" class="tab-contents">
                  <h2>Tab 5</h2>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius quos aliquam consequuntur, esse provident impedit minima porro! Laudantium laboriosam culpa quis fugiat ea, architecto velit ab, deserunt rem quibusdam voluptatum.</p>
              </div>
      </div>
      

      <div  id="autoComplete" style="{{$autoStyle}}" >
          <span id="closeAuto" wire:click="closeFloat">x</span>
          <ul>
              {!! $autoComplete !!}
          </ul>
      </div>    
</div>
