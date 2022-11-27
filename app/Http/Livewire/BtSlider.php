<?php
   //componant
      namespace App\Http\Livewire;
      
      use Livewire\Component;
      
      class BtSlider extends Component
      {
        public $slider_id;
        public $slide1 = "http://localhost/global_images/events/plDjYzrpGLyUgn8YasninMkhJnChtsMalf7yxsiA.png";
        public $slide2 = "http://localhost/global_images/events/mJ8vrzWYmhC49Ybean3c41GRuwRoPzjJkR4LJYbM.png";
        public $slide3 = "http://localhost/global_images/events/XwFtg56B3G0sn33FazrvxqSBE8UXfU6MZ0REhyWv.png";
      
          function mount($sliderId = "slider_1"){
            $this->slider_id = $sliderId;
          }
          
          public function render()
          {
              return view('livewire.bt-slider');
          }
      }