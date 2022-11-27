<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="./favicon.png">
        <title>Tools Box</title>  
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Aref+Ruqaa:wght@700&family=Tajawal:wght@500&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/all.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('code_mirror/codemirror.css')}}" >
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/jquery-ui.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/HE_style.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/topBarLayout.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/button.min.css')}}">
        
        <style id="styleSheet">{{$css}}</style>

        @livewireStyles
    </head>    
           <body>       
                      
                <div id="topBar" style="display:flex" >
                    <div class="topBar"  style="flex:6">
                        <ul class="listTop">                           
                            <li><img src="http://localhost/global_images/icons/menu_icon.png" class="icon_menu"></i></li>
                            <li><a class="top_link">TOOLS BOX</a></li>
                            <li><a class="top_link" href="#">Home |</a></li>
                            <li><a class="top_link" href="/tm">Tree Mnager |</a></li>
                            <li><a class="top_link" href="/db">DB Manager |</a></li>
                            <li><a class="top_link" href="/he">HTML Editor |</a></li>
                            <li><a class="top_link" href="#">Mode</a></li>
                        </ul>
                    </div>  
                    <div style="flex:1;padding-top:5px">                       
                           <select  id="fileNameNow"  class="form-select" style="font-size: 14px" >                   
                                @foreach ($compNames as $fn )
                                   @if($compName == $fn )
                                   <option value="{{$fn}}" selected>{{$fn}}</option> 
                                   @else
                                    <option value="{{$fn}}" >{{$fn}}</option>     
                                   @endif             
                                @endforeach           
                            </select>  
                    </div>

                    <div style="flex:1;padding-top:5px ">
                        <button class="btn btn-xs btn-info " id="editcss" >Edit Css</button>
                        <button class="btn btn-xs btn-info " id="editjs">Edit Js</button>   
                    </div>                                      
                     
                </div>    
            
              
            <div id="wrapper">        
                @livewire($compName)              
            </div>
        
            
            {{-- Side Bar --}}            
            <div id="sideBar">            
                  @livewire("shorten-opts")
                    <div class="side_elements">
                    <p x-text="message"></p>              
                </div>              
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
            

                <link rel="stylesheet" type="text/css" href="{{ asset('code_mirror/abcdef.css')}}" >
                <script src="{{ asset('assets/js/jquery-1.12.4.minb8ff.js?ver=1.12.4')}}"></script>
                <script defer src="https://unpkg.com/alpinejs@3.10.2/dist/cdn.min.js"></script>
                <script src="{{ asset('assets/js/popper.min.js')}}"></script>
                <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
                <script src="{{ asset('assets/js/helper.js')}}"></script>
                <script src="{{ asset('assets/js/beautify-html.js')}}"></script>              
                <script type="text/javascript" src="{{ asset('code_mirror/codemirror.js')}}"></script>
                <script  type="text/javascript" src="{{ asset('code_mirror/xml.js')}}"></script>
                <script  type="text/javascript" src="{{ asset('code_mirror/css.js')}}"></script>
                <script  type="text/javascript" src="{{ asset('code_mirror/javascript.js')}}"></script>
                <script  type="text/javascript" src="{{ asset('code_mirror/jquery-ui.min.js')}}"></script>
                <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
                <script  type="text/javascript" src="{{ asset('code_mirror/searching.js')}}"></script>
                <script  type="text/javascript" src="{{ asset('code_mirror/searchCursor.js')}}"></script>
                <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>

                 {{-- @if(component_name = "") --}}
                <script src="{{ asset('assets/js/topBarLayout.js')}}"></script>             
                {{-- <script src="{{ asset('assets/js/autoComplete.js')}}"></script>                                             --}}
                <script src="{{ asset('assets/js/HE_script.js')}}"></script>

                 <script>
                    var dpicker =  document.getElementById('datepicker');
                    var dpicker1 =  document.getElementsByClassName('datepicker');   

                        $('.datepicker').each(function(index, cdpicker) {
                            new Pikaday({
                            field: cdpicker,
                            onSelect: date => {
                            const year = date.getFullYear()
                                ,month = date.getMonth() + 1
                                ,day = date.getDate()
                                ,formattedDate = [
                                    year
                                ,month < 10 ? '0' + month : month
                                ,day < 10 ? '0' + day : day
                                ].join('-')
                                cdpicker.value = formattedDate;                              
                                Livewire.emit("setDate" , cdpicker.dataset.row , cdpicker.dataset.col, formattedDate);
                            }
                            });
                     })              
                </script>
                <script id="scriptSheet" type='text/javascript' >{!! $js !!}</script>     
                
                            
        @livewireScripts

       
   
        </body>

</html>

