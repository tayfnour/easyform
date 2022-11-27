<?php

      namespace App\Http\Livewire;
      
      use Livewire\Component;
      
      class multipagetabs extends Component
      {
        public $test="1";
          public function render()
          {
              return view('livewire.multipage-tabs');
             
          }
      }