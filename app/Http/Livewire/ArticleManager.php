<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\new_post;
use Livewire\WithFileUploads;

class ArticleManager extends Component
{
    use WithFileUploads;
    public $pastImage;
    public $postTitle;
    public $postbody;

    public function  hydrate() {

        $this->dispatchBrowserEvent('loadtabClasses', []);

    } 

    public function savePost(){


        $this->validate([
            'pastImage' => 'image|max:10000',
        ]);
        
        $filename = $this->pastImage->store('new_posts' , "global_images");

        $post = new new_post();

        $post->title = $this->postTitle;
        $post->body =  $this->postbody;
        $post->image = $filename;
        $post->options = json_encode (["image_url"=>"storage"]);
     
        $post->save();

        $this->reset();

    }


    public function render()
    {
        return view('livewire.article-manager')->layout("layouts.dash");
    }
}
