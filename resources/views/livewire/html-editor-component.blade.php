{{-- HtmlEditor Component  Route::get('/he/{comp_name}', HtmlEditorComponent::class); --}}
<div >
    <div id="topBar" style="display:flex" >
        <div class="topBar"  style="flex:1">
            <ul class="listTop">
                <li><img src="http://localhost/global_images/icons/menu_icon.png" class="icon_menu"></i></li>
                <li><a class="top_link" href="#">Home |</a></li>
                <li><a class="top_link" href="/tm">Tree Mnager |</a></li>
                <li><a class="top_link" href="/db">DB Manager |</a></li>
                <li><a class="top_link" href="/he">HTML Editor |</a></li>
                <li><a class="top_link" href="#">Mode (<span id="mode">{{$pageMode==1?"Html":"Livewire"}}</span>) </a></li>
            </ul>
        </div>
    <div  style="flex:1"> 
        <textarea id = "eleInfo" ></textarea>
        <textarea id = "selctedele" ></textarea>   
    </div>   
    </div>    
    
        <div id="wrapper">

            @if($pageMode == 1)
            
            @php
            // we replace  wrap area html every time  according to  $pagemode
            //comment blade instruction and stop inter action to edit html
            // in future hide additional elemnt 

        $re = '/wire:id="[\w]+"/m';
        $html = preg_replace("/(@ph.+endphp)/" ,"<!--\$1-->", $bladeFile );
            
            $html = preg_replace("/(@(elseif|if)\s*[\,\w\(\)\$\[\]\s\&\'\"\!\=\-\>]+\))/" ,"<!--\$1-->",$html );          
           
            $html = preg_replace("/(@foreach\s*\([\]\[\s\$\w=>]+\))/" ,"<!--\$1-->", $html );

            $html = preg_replace("/(@error\s*\([\.\-\"\]\[\s\$\w=>]+\))/" ,"<!--\$1-->", $html );
        
            $html = preg_replace("/({{[\/\.\]\[\'\"\)\(\-\+\s\$\w=>\,\?\:]+}})/", "<!--\$1-->" , $html);
         
           
            
            //dd($html);

            $html = preg_replace("/(@php\s*\([\-=\s\$\w\+]+\))/" ,"<!--\$1-->", $html );
            $html = preg_replace("/(@endif)\b/" ,"<!--\$1-->", $html );
            $html = preg_replace("/(@else)\b/" ,"<!--\$1-->", $html ); 
            $html = preg_replace("/(@enderror)\b/" ,"<!--\$1-->", $html );
            $html = preg_replace("/(@endforeach)\b/" ,"<!--\$1-->", $html );
            $html = preg_replace("/({!!\s*[$\w()\[\]\-\?\>]+\s*!!})/", "<!--\$1-->" , $html);
            $html = preg_replace("/(@livewire\s*\([\"\-\]\[,\s\$=>\w]+\))/", "<!--\$1-->" , $html);       
            $html = preg_replace( $re ,"", $html );

            

            @endphp

                {!! $html !!}  
        
            @else

                @livewire($fileName)

            @endif

        </div>


    
     {{-- Side Bar --}}    

    <div id="sideBar">
    
        <div>
            <select  id="fileNameNow"  class="form-control" style="font-size: 14px" >                   
                @foreach ($fileNames as $fn )
                   @if($fileName == $fn )
                   <option value="{{$fn}}" selected>{{$fn}}</option> 
                   @else
                    <option value="{{$fn}}" >{{$fn}}</option>     
                   @endif             
                @endforeach           
            </select>  
        </div>
        
        <div class="side_elements">
        <div id="addElement">Add Element</div>
        </div>

     

        
            <div id="file_manage" style="overflow-y:auto">   
                
            <button class="btn btn-sm btn-warning edit" id="viewLive">view Live</button>
            <button class="btn btn-sm btn-danger edit" id="viewHtml">view Html</button>
            <button class="btn btn-sm btn-info edit" id="savechange">Save Change</button>
            <button class="btn btn-sm btn-info edit" id="removeTempBorder">rm Borders</button>
            <br>
             <button class="btn btn-sm btn-secondary saveState edit" id="editHtml ">Edit Html</button>             
            <button class="btn btn-sm btn-dark saveState edit" id="editcss">Edit Css</button>
            <br>
            <button class="btn btn-sm btn-dark saveState edit" id="editjs">Edit Js</button>
            <button class="btn btn-sm btn-secondary saveState edit" id="editComp">Edit Comp</button>
             <br>
             <button wire:click="undo" class="btn btn-sm btn-primary edit" id="undo">Undo({{$his_Pointer}})</button> 
             <button wire:click="redo"  class="btn btn-sm btn-primary edit" id="undo">Redo({{$his_Pointer}})</button> 
             <button wire:click="dd_his"  class="btn btn-sm btn-info edit" id="undo">DD His</button> 
            <hr>           
            <button class="btn btn-sm btn-success" id="newComponent">New Comp</button>
            <br>

            <button class="btn btn-sm btn-warning" id="copyele">Copy</button>
            <button class="btn btn-sm btn-danger" id="changeValue">Value</button>
            <button class="btn btn-sm btn-success" id="changeText">Text</button> 
            <br>           
            <button class="btn btn-sm btn-info" id="pastAfter">Past AF</button>
            <button class="btn btn-sm btn-info" id="insertBefore">Past BF</button>
            <br>
            <button class="btn btn-sm btn-secondary" id="Append">Append</button>
            <button class="btn btn-sm btn-secondary" id="prepend">PrePend</button>   
            <br>

                  
            <button class="btn btn-sm btn-warning" id="addClass">add Class</button>
            <button class="btn btn-sm btn-info" id="addId">add Id</button>
            <br>
            <button class="btn btn-sm btn-success" id="imageSrc">ImgSrc</button>           
            <button class="btn btn-sm btn-info" id="view_var" >txt</button>
            <br>
        
          
        

            @if (session()->has('message'))
                    <div id="alert pt-1" > 
                        {{ session('message') }}
                    </div>
            @endif
        </div>
    </div>
        

    <div wire:ignore id="htmlviewer" class="htmlviewer wind"  >
        <div id="headerHtml" >
            <button class="btn btn-primary btn-xs" id="setHtml"  >SET HTML</button>
            <button class="btn btn-danger btn-xs saveState" id="closeEditor" >CLOSE</button>
            {{-- <button class="btn btn-warning btn-xs" id="WrapDir" >Wrap</button> --}}
        </div>
        <textarea  class="" id="TaViewer" name="TaViewer" ></textarea>
    </div>

    <div wire:ignore id="cssviewer" class="htmlviewer wind"  >
        <div id="headerCss" class="pb-1">
            <button class="btn btn-primary btn-xs" id="setCss"  >SET CSS</button>
            <button class="btn btn-danger btn-xs saveState" id="closeCssEditor" >CLOSE</button>
            {{-- <button class="btn btn-warning btn-xs" id="WrapDir" >Wrap</button> --}}
        </div>
        <textarea class="" id="cssViewers" name="TaViewer" >{{$css}}</textarea>
    </div>

    <div wire:ignore id="jsviewer" class="Jsviewers wind"  >
        <div id="headerJs" class="pb-1">
            <button class="btn btn-primary btn-xs" id="setJs"  >SET JS</button>
            <button class="btn btn-danger btn-xs saveState" id="closeJsEditor" >CLOSE</button>
            {{-- <button class="btn btn-warning btn-xs" id="WrapDir" >Wrap</button> --}}
        </div>
        <textarea class="" id="JsViewers" name="TaViewer" >{{$js}}</textarea>
    </div>

    <div wire:ignore id="compoWindow" class="compoWindow wind"  >
        <div id="headerComp" class="pb-1" >
            <button class="btn btn-primary btn-xs"  id="saveComponent" >Save File</button>
            <button class="btn btn-danger btn-xs saveState" id="closeComp" >CLOSE</button>
            {{-- <button class="btn btn-warning btn-xs" id="WrapDir" >Wrap</button> --}}
        </div>
        <textarea wire:model.defer="compFile" id="compViewersArea" name="compViewers" ></textarea>
    </div>

    <div class="" id="elemsWrap">

        <button class="sideBtn addElement border-red">section</button>
        <button class="sideBtn addElement border-red">fullsec</button>
        <button class="sideBtn addElement border-red">card</button>
        <button class="sideBtn addElement border-red">carsoul</button>
        <button class="sideBtn addElement border-blue">container</button>
        <button class="sideBtn addElement border-blue">c-fluid</button>
        <button class="sideBtn addElement border-grey">row</button>
        <button class="sideBtn addElement border-orange">col-1</button>
        <button class="sideBtn addElement border-orange">col-2</button>
        <button class="sideBtn addElement border-orange">col-3</button>
        <button class="sideBtn addElement border-orange">col-4</button>
        <button class="sideBtn addElement border-orange">col-5</button>
        <button class="sideBtn addElement border-orange">col-6</button>
        <button class="sideBtn addElement border-orange">col-7</button>
        <button class="sideBtn addElement border-orange">col-8</button>
        <button class="sideBtn addElement border-orange">col-9</button>
        <button class="sideBtn addElement border-orange">col-10</button>
        <button class="sideBtn addElement border-orange">col-11</button>
        <button class="sideBtn addElement border-orange">col-12</button>
        <button class="sideBtn addElement border-orange">div</button>
        <button class="sideBtn addElement border-orange">image</button>
        <button class="sideBtn addElement border-orange">span</button>
        <button class="sideBtn addElement border-orange">button</button>
        <button class="sideBtn addElement border-orange">text</button>
        <button class="sideBtn border-orange saveState" id="hide_ele" >Exit</button>
        <div class="checkParent">prep
        <input  type="checkbox" class="checkbox"  id="preFlag"  />
        </div>
        <div class="checkParent">before
        <input  type="checkbox" class="checkbox"  id="beforeFlag"  />
        </div>
        <div class="checkParent">After
            <input  type="checkbox" class="checkbox"  id="afterFlag"  />
        </div>

    </div>

</div>


