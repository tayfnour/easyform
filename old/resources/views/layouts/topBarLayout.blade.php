<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Tree Manager</title>  
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/topBarLayout.css')}}">       
        
        @livewireStyles
    </head>
    
        <body>             
            
                <div class="topBar">
                    <ul class="listTop">
                        <li><a class="top_link" href="#">Home |</a></li>
                        <li><a class="top_link" href="/tm">Tree Mnager |</a></li>
                        <li><a class="top_link" href="/db">DB Manager |</a></li>
                        <li><a class="top_link" href="/he">HTML Editor |</a></li>
                    </ul>
                </div>
             
                {{$slot}}

                <script src="{{ asset('assets/js/jquery-1.12.4.minb8ff.js?ver=1.12.4')}}"></script>
                <script src="{{ asset('assets/js/helper.js')}}"></script>
                <script src="{{ asset('assets/js/topBarLayout.js')}}"></script>
        
        @livewireScripts
                
        </body>

</html>