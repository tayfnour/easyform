<div class="el">
    <div class="section section_class el sec1">
        <div class="container container_class el">
            <div class="row row_class el">
                <div class="col-12 col-12_class el  header r">
                    <img src="http://localhost/easyPanel/storage/app/logos/settings.png" class="img-fluid el  logo" width="50">Features Manager</div>
            </div>

            <div class="row row_class el r">

                <div class="col-12  f_group el">

                    <input wire:model="col_width" placeholder="value" type="number" class="form-control colWidth el ml-1 r">
                    <select wire:model="fe_gr_name" class="form-control  f_keys el ml-1" wire:change="setGroup">
                       @foreach ($multiple_features as $mf)
                             <option value="{{$mf}}" class="el">{{$mf}}</option>
                       @endforeach             </select><input type="button" class="btn btn-secondary  el addgroup p-2" value="Add Group" id="addGroup">

                    <span class="el  FetureCopy">{{$fe_gr_name}}</span>
                </div>



                <div class="col-12 f_add el">

                    <input wire:model="editVal" placeholder="value" type="text" class="form-control f_edit el ml-1">
                    <input wire:model="editKey" placeholder="key" type="text" class="form-control  f_edit el mr-1 ml-1">
                    <input wire:click="addFeature" type="button" class="btn btn-secondary el p-2  addFeatur" value="Add Feature">
                </div>

            </div>

        </div>
    </div>
    <div class="section section_class el sec2 mb-1  p-2">
        <div class="container container_class el">

            @php ($i=-1)

            <div id="features-wrap" class="row row_class ar el r">
                @foreach ($features as $k => $v)
                <div class="col-{{$col_width}}  col-8_class el widget1">
                    <input value="{{$k}}" type="text" style="background-color: #efdaff" class="theKey form-control el mb-1">
                    <textarea class="theval form-control el mb-1">{{$v}}</textarea>
                    <input data-key="{{$k}}" data-val="{{$v}}" data-old="{{$v}}" wire:click="updFeature($event.target.getAttribute( 'data-key') , $event.target.getAttribute( 'data-val'), $event.target.getAttribute( 'data-old')) " type="button
                        " class="updFeature btn btn-outline-secondary el" value="update">
                    <input wire:click="DelFeature( '{{$k}}') " type="button " class="DelFeature btn btn-outline-secondary el" value="delete">
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="section section_class el sec3 sec3">
        <div class="container container_class el">

            <div class="row row_class ar el footer">
                <ul class="el">
                    @foreach ( $massege as $msg )
                    <li class="el">
                        {{$msg}}
                    </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
</div>