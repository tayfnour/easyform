<div class="el el_border_6">
    <div class="el el_border_6">New_Component_test-auto
        <div class="section section_class el el_border_6">
            <div class="container container_class el el_border_6">
                <div class="row row_class el el_border_6">
                    <div class="col-8 col-8_class el el_border_6">
                        <div class="input-group el el_border_6">
                            <div class="input-group-prepend el el_border_6">
                                <span class="input-group-text el el_border_6">{{$swordPar}}</span>
                            </div>
                            <input id="sword" wire:model="sword" type="text" class="form-control lookup el el_border_6" data-lookup="getCatagories">
                        </div>
                    </div>
                    <div class="col-4 col-4_class el el_border_6"></div>
                </div>

            </div>



        </div>

        @livewire("auto-pass" , ["post"=>$post] )

    </div>
</div>