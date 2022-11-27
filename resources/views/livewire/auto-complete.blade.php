<div>
    <div class="" style="border:2px solid #ddd">
        <div class="section section_class">
            <div class="container container_class">

                <div class="row row_class">
                    <div class="col-8 col-8_class  cont1  input-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">{{$swordPar}}</span>
                            </div>
                            <input id="sword" type="text" class="form-control lookup" wire:model="sword" data-lookup="getCatagories">
                        </div>
                        <div class="col-4 col-4_class rtitle selectedBefore hdiv selectedBefore"><input type="button" class="btn btn-info selectedBefore" value="btn"></div>
                    </div>
                </div>

                <div class="row row_class" >
                    <div class="col-8 col-8_class  cont1  input-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">{{$sworddPar}}</span>
                            </div>
                            <input id="swordd" data-lookup="getinventories" type="text" class="form-control lookup" wire:model="swordd">
                        </div>
                        <div class="col-4 col-4_class rtitle selectedBefore"></div>
                    </div>
                </div>

            </div>

            <div class="autoComplete " style="{{$autoStyle}}">
                <span id="closeAuto" wire:click="closeFloat"  style="z-index: 100;">x</span>
                <ul>
                    {!! $autoComplete !!}
                </ul>
            </div>
        </div>
    </div>
</div>