<?php    

      namespace App\Http\Livewire;
      
      use Livewire\Component;
      
      class UsingSlider extends Component
      {

        public $title = "Form Builder";

        public $slider1 = "http://localhost/global_images/events/plDjYzrpGLyUgn8YasninMkhJnChtsMalf7yxsiA.png";
        public $slider2 = "http://localhost/global_images/events/mJ8vrzWYmhC49Ybean3c41GRuwRoPzjJkR4LJYbM.png";
        public $slider3 = "http://localhost/global_images/events/XwFtg56B3G0sn33FazrvxqSBE8UXfU6MZ0REhyWv.png";

        public $path ;
             
          public function render()
          {

            // $this->path  = asset('storage/app/images');
           //  dd( $this->path );

              return view('livewire.using-slider');
          }

      }