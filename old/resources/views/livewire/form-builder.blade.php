<div class="el">
    <div class="section section_class el">
        <div class="container container_class el">
            <div class="row row_class el">
                <div class="col-12 col-12_class el r">
                    <h4 class="border-bottom p-1 el" >
                        {{str_replace("_"," ",$formName)}}
                       <span class="d-inline-block mt-2" style="color:red;font-size:18px;float:right" > {{$table}}/{{$formType ==1 ? "(Update)":"(Insert)"}}-{{$ci}} </span>
                    </h4>                 
                </div>
            </div>
        </div>
    </div>
    <form class="el">
        {{--dd($datas)--}}
        @foreach ($datas as $data)

        @if ($data->inputType == 'text')
        <div class="section section_class el  betweenFeild mt-3">
            <div class="container container_class el">
                <div class="row row_class el">
                    <div class="col-8 col-12_class el">
                        <input wire:model="rows_insert.{{$data->colName}}" type="text" class="form-control el">
                    </div>
                    <div class="col-4 col-12_class text-center el"><span class="feildTitle el">{{ $data->arabic_name?$data->arabic_name: $data->eng_name }}</span></div>
                    <div class="el">
                        @error("rows_insert.".$data->colName)
                        <span class="error el">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @elseif ($data->inputType == 'textarea')
        <div class="section section_class el  betweenFeild">
            <div class="container container_class el">
                <div class="row row_class el">
                    <div class="col-8 col-12_class el">
                        <textarea wire:model="rows_insert.{{$data->colName}}" class="form-control el"></textarea>
                    </div>
                    <div class="col-4 col-12_class el"><span class="feildTitle el">{{ $data->arabic_name?$data->arabic_name: $data->eng_name }}</span></div>
                    <div class="el">
                        @error("rows_insert.".$data->colName)
                        <span class="error el">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @elseif ($data->inputType  == 'treeleaf')
        <div class="section section_class el  betweenFeild">
            <div class="container container_class el">
                <div class="row row_class el">
                    <div class="col-8 col-12_class el"> 
                        <input wire:focus="setfocustree('{{$data->colName}}' , '{{$data->lookup}}')" wire:keydown.debounce.500ms="setfocustree('{{$data->colName}}' , '{{$data->lookup}}')" wire:model="rows_insert.{{$data->colName}}" type="text" class="form-control {{isset($bgleafInput[$data->colName])?'treeleaf':''}} el" >
                        <div>123</div>
                    </div>
                    <div class="col-4 col-12_class el"><span class="feildTitle el">{{ $data->arabic_name?$data->arabic_name: $data->eng_name }}</span></div>
                    <div class="el">
                        @error("rows_insert.".$data->colName)
                          <span class="error el">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
        </div>
        @elseif ($data->inputType == 'image')
        <div class="section section_class el  betweenFeild">
            <div class="container container_class el">
                <div class="row row_class el">
                    <div class="col-8 col-12_class el">
                        <input id="inp_1000" wire:model="rows_insert.{{$data->colName}}" type="file" class="form-control">
                    </div>                   
                    <div class="col-4 col-12_class el">
                        <span class="feildTitle">{{ $data->arabic_name?$data->arabic_name: $data->eng_name }}</span>
                    </div>
                    <div>
                        @php $myimage=$rows_insert[$data->colName] @endphp                       
                        {!! is_object($myimage)?"<img src='{$myimage->temporaryUrl()}' style='height: 200px'>":"" !!}
                    </div>
                    <div>
                        @php $src=config("livewire.storge_img").$myimage  @endphp
                        {!! !empty($myimage)&&!is_object($myimage)?"<img src='{$src}' style='height: 200px'>":"" !!}
                    </div>
                    <div class="el">
                        @error("rows_insert.".$data->colName)
                        <span class="error el">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @elseif ($data->inputType == 'autoComplete')
        <div class="section section_class el  betweenFeild">
            <div class="container container_class el">
                <div class="row row_class el">
                    <div class="col-8 col-12_class el">
                        <div class="input-group mb-3 el">
                            <div class="input-group-prepend el">
                                <span class="input-group-text el" id="basic-addon3">{{  $rows_insert[$data->colName."_par"] ?? "-"  }}</span>
                            </div>
                            <input id="autoInput" wire:keydown.escape="resetAuto('{{$data->colName}}')" wire:blur="resetAuto('{{$data->colName}}')" wire:keypress.debounce.1s="setFloat({{$data->colOptions_id}} , '{{$data->colName}}')" wire:model="rows_insert.{{$data->colName}}"
                                type="text" class="form-control relative auto el">
                            @if(!empty($autoComplete[$data->colName]))
                            <div style="width:100%;background-color:#eee; top:50px;z-index:100;position: absolute !important" class="rounded-t-none shadow-lg list-group el">
                                <div class="el">
                                    {!! $autoComplete[$data->colName] !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-4 col-4_class el"><span class="feildTitle el">{{ $data->arabic_name?$data->arabic_name: $data->eng_name }}</span></div>
                    <div class="el">
                        @error("rows_insert.".$data->colName)
                        <span class="error el">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
        </div>
        @elseif ($data->inputType == 'autoUpdate')
        <div class="section section_class el  betweenFeild">
            <div class="container container_class el">
                <div class="row row_class el">

                    <div class="col-8 col-12_class el">
                        <div class="input-group mb-3 el">
                            <div class="input-group-prepend el">
                                <span class="input-group-text el" id="basic-addon3">{{$rows_insert[$data->colName."_par"] ?? "-"}}</span>
                            </div>
                            <input id="autoInput" wire:keydown.escape="resetAuto('{{$data->colName}}'')" wire:blur="resetAuto('{{$data->colName}}')" wire:keypress.debounce.1s="setFloatInc({{$data->colOptions_id}} , '{{$data->colName}}')" wire:model="rows_insert.{{$data->colName}}"
                                type="text" class="form-control relative auto el">
                            @if(!empty($autoIncUpdate[$data->colName]))
                            <div style="width:100%;background-color:#eee; top:50px;z-index:100;position: absolute !important" class="rounded-t-none shadow-lg list-group el">
                                <div class="el">
                                    {!! $autoIncUpdate[$data->colName] !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-4 col-4_class el"><span class="feildTitle el">{{ $data->arabic_name?$data->arabic_name: $data->eng_name }}</span></div>
                    <div class="el">
                        @error("rows_insert.".$data->colName)
                        <span class="error el">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
        </div>
        @elseif ($data->inputType == 'galary')
        <div class="section section_class el  betweenFeild">
            <div class="container container_class el">
                <div class="row row_class el">
                    <div class="col-8 col-12_class el">
                        <div class="input-group mb-3 el">
                            <div class="input-group-prepend el">
                                <span class="input-group-text el" id="basic-addon3">{{  $rows_insert[$data->colName."_par"] ?? "-"  }}</span>
                            </div>
                            <input wire:keydown.debounce.1s="getDescrp('{{$data->lookup}}','{{$data->colName}}')" wire:model="rows_insert.{{$data->colName}}" wire:keydown.escape="resetPhoto('{{$data->colName}}'')" type="text" class="form-control relative auto el">
                            @if(!empty($ph_Descp) || !empty($photos_Str))
                            <div style="border:1px solid #999; width:100%;background-color:#eee" class="mt-2 p-2 rounded-t-none  el">
                                @if(!empty($ph_Descp))
                                <div style="width:100%;height:150px;overflow-y:auto" class="el">
                                    {!! $ph_Descp  !!}
                                </div>
                                @endif
                                @if(!empty($photos_Str))
                                <div style="display:flex; flex-direction: row;width:100%;overflow:auto;" class="el">
                                    {!! $photos_Str !!}
                                </div>
                                @endif
                            </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-4  col-4_class el"><span class="feildTitle rounded el">{{ $data->arabic_name?$data->arabic_name: $data->eng_name }}</span></div>

                </div>
                <div class="row row_class el">
                    <div class="col-8 el">
                        <input wire:model="photos.{{count($photos)}}" id="inp_10" type="file" class="form-control el">
                        <div class="forUploadImages el">
                            @if(count($photos)>0)
                            <div style="display:flex;border:1px solid #999; width:100%;height:250px;overflow-y:auto;background-color:#eee" class="mt-2 p-2 rounded-t-none  el">
                                @foreach ($photos as $k => $item)
                                @if($item)
                                <div class="card p-1 el" style="width:220px;margin:5px">
                                    
                                    <span class="btn btn-default btn-file el" >
                                             تعديل الصورة<input class="el" wire:model="photos.{{$k}}" type="file" value="">                                        
                                    </span>
                                    <span class="btn btn-default btn-file el" >
                                            <span wire:click="deleteImage({{$k}} , '{{$data->colName}}')" class="el">حذف الصورة</span>
                                    </span>

                                    <img src="{{$item->temporaryUrl()}}" style="width:100%;height:175px " class="mb-1 el">
                                    <textarea wire:model="photos_desc.{{$k}}" class="Form-control w-100 el"></textarea>
                                    <div>
                                        @error("photos_desc.{$k}")
                                           <span class="error el">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div wire:loading="" wire:target="photos.{{$k}}" class="el">Uploading...</div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            <div class="el">
                                <input wire:click="storeImages('{{$data->colName}}' , '{{$data->lookup}}')" class="btn btn-secondary el" type="button" value="حفظ الصور المرفوعة">
                                <input wire:click="pddd()" class="btn btn-secondary el" type="button" value="ddd">
                            </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-4 el">
                    </div>
                </div>

                <div class="el">
                    @error("rows_insert.".$data->colName)
                    <span class="error el">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        @endif
        @endforeach
        <div class="section section_class el">
            <div class="container container_class el">
                <div class="row row_class el">
                    <div class="col-12 col-12_class el footerArea">

                     @if ($this->submit_visible !== 'hidden')
                        @if($formType==0)

                        <input wire:click="insertRow" type="button" class="btn el btn-lg btn-secondary" value="{{ $button_new  }}">
                        @else

                        <input wire:click="updateRow" type="button" class="btn el btn-lg btn-dark" value="{{ $button_update }}">
                        @endif
                     @endif    

                        <div class="el mt-2">
                            <ul class="el">
                                @foreach ($messeges as $msg )
                                <li class="el  mt-2">
                                    {{$msg}}
                                </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
  
</div>
{{--dd($photos)--}}