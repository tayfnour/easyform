<div>
    <div class="el" style="border:2px solid #ddd">

        <div class="section section_class el">
            <div class="container container_class el">
                <div class="row row_class el">
                    <div class="col-8 col-8_class el  cont1  input-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">{{$swordPar}}</span>
                            </div>
                            <input id="sword" type="text" class="form-control el lookup HE_border_click" wire:model="sword">


                        </div>
                        <div class="col-4 col-4_class el rtitle selectedBefore"></div>
                    </div>
                </div>
            </div>

            <div id="autoComplete " style="{{$autoStyle}}">
                <span id="closeAuto" wire:click="closeFloat">x</span>
                <ul>
                    {!! $autoComplete !!}
                </ul>
            </div>
        </div>
    </div>
</div>