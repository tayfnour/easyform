<div class="el el_border_6">New_Component_drop-image
    <div class="section section_class el el_border_6">
        <div class="container container_class el el_border_6">
            <div class="row row_class el el_border_6">
                <div class="input-group el el_border_6">
                    <div class="input-group-prepend el el_border_6">
                        <span class="input-group-text el el_border_6">{{$event_idPar}}</span>
                    </div>
                    <input id="event_id" wire:model="event_id" type="text" class="form-control lookup el pro_id selectedBefore el_border_6" data-lookup="getCatagories">
                </div>
            </div>
        </div>
        <div class="container container_class el el_border_6">

            @foreach($photos as $i =>  $photo)
            <div id="row_{{$i}}" class="row area row_class el el_border_6">
                <div class="col-6 col-6_class el el_border_6">
                    <div class="form-group el el_border_6">
                        <label for="uploads_{{$i}}" class="el el_border_6"> upload Image </label>
                        <input id="inp_{{$i}}" wire:model="photos.{{$i}}" type="file" class="form-control pb-2 el el_border_6" style="padding: 1px">
                    </div>
                </div>
                <div class="col-6 col-6_class el el_border_6">
                    @if(isset($photos[$i]))
                    <img src="{{$photos[$i]->temporaryUrl()}}" width="200px" class="el el_border_6">
                    <input wire:click="removePhotoFromArr({{$i}})" type="button" class="btn btn-primary field el el_border_6" value="Delete">
                    @endif
                </div>
            </div>
            <hr class="el el_border_6">
            @endforeach

            <div id="row_1000" class="row area row_class el el_border_6">
                <div class="col-6 col-6_class el el_border_6">
                    <div class="form-group el el_border_6">
                        <label for="uploads" class="el el_border_6"> upload Image </label>
                        <input id="inp_1000" wire:model="photos.{{count($photos)}}" type="file" class="form-control pb-2 el el_border_6" style="padding: 1px">
                    </div>
                </div>
                <div class="col-6 col-6_class el el_border_6"></div>
            </div>
        </div>




        <div class="container container_class el el_border_6">

            <div class="row row_class el el_border_6">
                <div class="col-12 col-12_class el el_border_6" value="Store Images">

                    <input type="button" class="btn btn-primary el el_border_6" value="Store Imges" wire:click="storeImages">
                    <input wire:click="pdd" type="button" class="btn btn-warning el el_border_6" value="Pdd">
                </div>
            </div>

        </div>
        <div class="container container_class el el_border_6">

            <div class="row row_class el el_border_6">
                <div class="col-12 col-12_class el el_border_6  pro_title">الصور الخاصة بالمنتج</div>
                <div class="col-12 col-12_class el el_border_6 r" value="Store Images">

                    {!! $edit_images !!}

                </div>
            </div>

        </div>
    </div>
    @livewire("auto-pass")
</div>