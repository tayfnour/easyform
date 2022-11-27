<?php

      namespace App\Http\Livewire;
      use DB;
      
      use Livewire\Component;
      
      class UseForms extends Component
      {

     //   public $forms ="up_images/Default";
        public $ic=0;
        public $inputtest;
        public $blockTables = ["failed_jobs" , "migrations"  , "password_resets" , "personal_access_tokens" , "sessions"];
        
       // public $form_names;

           public function updatedforms($forms){

            //dd($forms);

             $this->emit("resetMount" , $forms );
         

            //    $this->customCollection = $this->customCollection->map(function($item) {
            //     return is_array($item) ? (object) $item : $item;
            // });
           }

           public function mount(){

            
            $this->forms = session()->get('forms');
            
          //  $this->form_names = json_decode(json_encode( DB::table("coloptions")->select([ "tableName" , "formName" ])->distinct("formName")->get(), FALSE));
         // $this->form_names = DB::table("coloptions")->select([ "tableName" , "formName" ])->distinct("formName")->get();
                
           }
            
          public function render()
          {

           $this->ic++;

            session()->put('forms', $this->forms);

              return view('livewire.use-forms' ,
              [
                  "form_names" => DB::table("coloptions")->select([ "tableName" , "formName" ])->distinct("formName")->get() 
              ]
              );    
                  
          }
      }