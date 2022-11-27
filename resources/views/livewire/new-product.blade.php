<div class="el m-2 p-4 selectedBefore card selectedBefore">
    <div class="el">
        <div class="section p-3  section_class el htitle selectedBefore">
            <h2 class="el"> Product Managment Part (
                {{$part}}) </h2>
        </div>

        @if($part==1)
        <div class="card p-5 mt-3 section_class el fade-in-text" id="frameOne">

            <div class="card-body  container_class dialog el">

                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="product_id" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product id</span>
                    </div>
                </div>
                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="productName" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">product Name</span>
                    </div>
                </div>
                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <div class="input-group el">
                            <div class="input-group-prepend el">
                                <span class="input-group-text el">{{$catagoryPar}}</span>
                            </div>
                            <input id="catagory" wire:model="catagory" type="text" class="form-control lookup el" data-lookup="getCatagories">
                        </div>
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product Category</span>
                    </div>
                </div>
                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="sku" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product Sku</span>
                    </div>
                </div>
                <div class="row row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="price" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product Price</span>
                    </div>
                    <div class="col-12 mt-2 col-12_class el">
                        <input wire:click="increaselvl" type="button" class="btn btn-dark el" value="Next Section"></div>
                </div>
            </div>

        </div>
        @endif
        @if($part==2)
        <div class="card p-5 mt-3 section_class el  fade-in-text" id="frameTwo">

            <div class="card-body  container_class dialog el">

                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="product_id" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product id</span>
                    </div>
                </div>
                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="productName" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">product Name</span>
                    </div>
                </div>
                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <div class="input-group el">
                            <div class="input-group-prepend el">
                                <span class="input-group-text el">{{$catagoryPar}}</span>
                            </div>
                            <input id="catagory" wire:model="catagory" type="text" class="form-control lookup el" data-lookup="getCatagories">
                        </div>
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product Category</span>
                    </div>
                </div>
                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="sku" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product Sku</span>
                    </div>
                </div>
                <div class="row row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="price" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product Price</span>
                    </div>
                    <div class="col-12 mt-2 col-12_class el">
                        <input wire:click="increaselvl" type="button" class="btn btn-dark el" value="Next Section"><input wire:click="declvl" type="button" class="btn btn-dark el" value="Previous Section"></div>
                </div>
            </div>

        </div>
        @endif
        @if($part==3)
        <div class="card p-5 mt-3 section_class el  fade-in-text" id="frameThree">
            <div class="card-body  container_class dialog el">
                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="product_id" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product id</span>
                    </div>
                </div>
                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="productName" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">product Name</span>
                    </div>
                </div>
                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <div class="input-group el">
                            <div class="input-group-prepend el">
                                <span class="input-group-text el">{{$catagoryPar}}</span>
                            </div>
                            <input id="catagory" wire:model="catagory" type="text" class="form-control lookup el" data-lookup="getCatagories">
                        </div>
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product Category</span>
                    </div>
                </div>
                <div class="row mb-2 row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="sku" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product Sku</span>
                    </div>
                </div>                
                <div class="row row_class el">
                    <div class="col-9 col-9_class el">
                        <input wire:model="price" type="text" class="form-control el">
                    </div>
                    <div class="col-3 col-3_class el">
                        <span class="input_title el">Product Price</span>
                    </div>
                    <div class="col-12 mt-2 col-12_class el">
                        <input wire:click="declvl" type="button" class="btn btn-dark el" value="Previous Section">
                        <input wire:click="declvl" type="button" class="btn btn-success el selectedBefore HE_border_click" value="حفظ معلومات المنتج"></div>
                     </div>
               </div>

        </div>


    </div>
    @endif





    <div class="autoComplete  el" style="{{$autoStyle}}">
        <span id="closeAuto" wire:click="closeFloat" class="el">x</span>
        <ul class="el">
            {!! $autoComplete !!}
        </ul>
    </div>

</div>