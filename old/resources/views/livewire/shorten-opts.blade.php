<div>
    <div class="section section_class" style="padding:5px">
        <div class="container container_class el">
            <div class="row row_class el">

                <div class="col-6">
                    <label>Column Name</label>
                    <select class="form-select selected_column m-1 el " wire:model="colName">
                        @foreach ($columns as $col)
                            <option val="{{ $col }}" class="el">{{ $col }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label>Table Name</label>
                    <select class="form-select selected_column m-1 el " wire:model="table">
                        @foreach ($tableNames as $tbName)
                            <option class="el">{{ $tbName }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-12">
                    <label>Form Name</label>
                    <select class="form-select selected_column m-1 el " wire:model="formName">
                        @foreach ($formNames as $form)
                            <option val="{{ $form }}" class="el">{{ $form }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div style="height:600px;overflow-y:auto">
        <table class="table my_table" id="col_option_table" style="width:100%">

            @php   $ic = 1;   @endphp

            <tbody>

                @foreach ($options as $key => $col)
                    <tr class="el">
                        <td class="" id="option_key_{{ $ic }}"
                            style="font-weight:600;padding:1px 0px 1px 5px;width:10%;background-color:rgb(238, 233, 223)">
                            {{ $key }}
                        </td>

                        @if ($key == 'inputType')
                            <td class="key_Val_opt el">
                                <select wire:model="options.{{ $key }}" class="form-select"
                                    id="option_value_{{ $ic }}">
                                    @foreach (explode('|', $optColOption['inputType']['lookup']) as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </td>
                        @elseif($key == 'formType')
                            <td class="key_Val_opt el">
                                <select wire:model="options.{{ $key }}" class="form-select"
                                    id="option_value_{{ $ic }}">
                                    @foreach (explode('|', $optColOption['formType']['lookup']) as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </td>
                            
                        @elseif($key == 'onEventFn')

                            <td class="key_Val_opt el">
                                <textarea wire:model.debounce.1000ms="options.{{ $key }}"
                                    class="form-control" id="option_value_{{ $ic }}"></textarea>


                            </td>

                        @elseif($key == 'lookup')

                            <td class="key_Val_opt el">
                                <textarea wire:model.debounce.1000ms="options.{{ $key }}"
                                    class="form-control" id="option_value_{{ $ic }}"></textarea>
                                <select wire:model="lookTable" id="">
                                    <option value="1">picke Table</option>
                                    @foreach ($tableNames as $tbName)
                                       <option class="el">{{ $tbName }}</option>
                                    @endforeach
                                </select>
                                <select wire:model="lookCol" id="">
                                    <option value="1">picke Column</option>
                                    @foreach ($lookcolumns as $col)
                                       <option class="el">{{ $col }}</option>
                                    @endforeach
                                </select>

                            </td>

                        @elseif($key == 'formAttrs') 

                       {{-- {{dd($options[$key])}}  --}}
                        
                        <td class="key_Val_opt el">
                            
                            <span wire:click="addField" class="addAttr" >+</span>
                            <input wire:model.debounce.1000ms="options.{{ $key }}" type="text"
                             class="form-control" id="option_value_{{ $ic }}" style="display: inline-block !important ;width: calc(100% - 30px);" readonly >
                            {!!$this->calcFormAttrs($options[$key])!!}    
                        </td>

                        @else
                            <td class="key_Val_opt el">
                                <input wire:model.debounce.1000ms="options.{{ $key }}" type="text"
                                    class="form-control" id="option_value_{{ $ic }}">
                            </td>
                        @endif
                    </tr>
                    @php   $ic++;   @endphp
                @endforeach

            </tbody>
        </table>
        <input wire:click="saveOptions" type="button" class="btn btn-primary mt-2 el" value="Save Option">
    </div>

    @if (isset($this->msgs))
        <div id="topMsg" class="topMsg">
            {!! implode('<br>', $this->msgs) !!}
            @php $this->msgs=null @endphp
        </div>
    @endif
</div>
