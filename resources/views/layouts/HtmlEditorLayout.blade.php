<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="favicon.png">
        <title>c</title>  
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Aref+Ruqaa:wght@700&family=Tajawal:wght@500&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('code_mirror/codemirror.css')}}" >
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/jquery-ui.css')}}">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/HE_style.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/topBarLayout.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/autocomplete.css')}}">
        
        <style id="styleSheet"></style>

        @livewireStyles
    </head>
    
           <body>    
            
               @livewire("html-editor-component" , ['comp_name' => Route::current()->parameter('comp_name')])
               
               
                
                <!-- The core Firebase JS SDK is always required and must be listed first -->
                {{-- <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>
                <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-auth.js"></script>
                <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-storage.js"></script>
             
                <script src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.7.0-202153001032/firebase-firestore.min.js" integrity="sha512-w9dStqpUvNTSwa5SMBH4b25yn/ZWc+uU7jec94cmbt4alipscTKC4RHDm0YdSVzBKI2JTVQv2toMp/14KLIHJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                {{-- <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-database.js"></script> --}}
               {{-- <script src="{{ asset('assets/js/fireStroeFuncs.js') }}"></script> --}}

               <!--Corr Libs -->

                <link rel="stylesheet" type="text/css" href="{{ asset('code_mirror/abcdef.css')}}" >
                {{-- <link rel="stylesheet" type="text/css" href="{{ asset('code_mirror/jquery-ui.min.css')}}" > --}}
                <script src="{{ asset('assets/js/jquery-1.12.4.minb8ff.js?ver=1.12.4')}}"></script>
                <script defer src="https://unpkg.com/alpinejs@3.8.0/dist/cdn.min.js"></script>
                <script src="{{ asset('assets/js/popper.min.js')}}"></script>
                <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
                <script src="{{ asset('assets/js/helper.js')}}"></script>
                <script src="{{ asset('assets/js/beautify-html.js')}}"></script>              
                <script type="text/javascript" src="{{ asset('code_mirror/codemirror.js')}}"></script>
                <script  type="text/javascript" src="{{ asset('code_mirror/xml.js')}}"></script>
                <script  type="text/javascript" src="{{ asset('code_mirror/css.js')}}"></script>
                <script  type="text/javascript" src="{{ asset('code_mirror/javascript.js')}}"></script>
                <script  type="text/javascript" src="{{ asset('code_mirror/jquery-ui.min.js')}}"></script>
                <script  type="text/javascript" src="{{ asset('code_mirror/searching.js')}}"></script>
                <script  type="text/javascript" src="{{ asset('code_mirror/searchCursor.js')}}"></script>
                <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
               
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
                                Livewire.emit("setDate" , cdpicker.dataset.col, formattedDate);
                            }
                            });
                     })

                    

                    //    var picker = new Pikaday({
                    //         field: dpicker,
                    //         onSelect: date => {
                    //         const year = date.getFullYear()
                    //             ,month = date.getMonth() + 1
                    //             ,day = date.getDate()
                    //             ,formattedDate = [
                    //                 year
                    //             ,month < 10 ? '0' + month : month
                    //             ,day < 10 ? '0' + day : day
                    //             ].join('-')
                    //             dpicker.value = formattedDate;                              
                    //             Livewire.emit("setDate" , dpicker.dataset.col, formattedDate);
                    //         }
                    //    })
                </script>
             

                 {{-- @if(component_name = "") --}}
                <script src="{{ asset('assets/js/topBarLayout.js')}}"></script>
             
                <script src="{{ asset('assets/js/autoComplete.js')}}"></script>
  
                                            
                <script src="{{ asset('assets/js/HE_script.js')}}"></script>
              
               
                <script id="scriptSheet" type='text/javascript' ></script>              
                            
        @livewireScripts
   
        </body>

</html>