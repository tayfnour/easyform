<div>
    @if ($headerActive == 'active')
        @livewire("single-form" , ['formName' => $formHeader , "ref"=>$ref , "submit_visible" => "hidden"])
        <hr>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-12" style="color:red">
                <h2>
                    {{ $formName }} - Validation Status :
                    {{ $headerFormValReady }}
                </h2>
            </div>
            <div class="col-12" style="color:blue">
                <h3>
                    {{ $table }}
                </h3>
                <hr>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row pt-2 g-3 align-items-center" style="direction: rtl">
            <div class="col-12">

                <table class="table table-bordered">

                    <tr>
                        @foreach ($this->columns as $col)

                            <th>
                                {{ $colOptions[$col]['arabic_name'] }}
                            </th>

                        @endforeach
                        <th>
                            العمليات
                        </th>
                    </tr>

                    @foreach ($inlineForm as $k => $rw)

                        <tr>

                            @foreach ($rw as $kn => $col)

                                <td>

                                    @php
                                        $inputType = 'normal';
                                        $ev = !empty($colOptions[$kn]['onEventFn']) ? $colOptions[$kn]['onEventFn'] . '(' . $k . ')' : '';
                                        $pickVal = !empty($colOptions[$kn]['colType']) ? $colOptions[$kn]['colType'] : null;
                                        
                                        $ev1 = '';
                                        $widget = $colOptions[$kn]['widget'];
                                        
                                        if (isset($pickVal) && ($pickVal == 'pickValue' || $pickVal == 'pickeFromArr' || $pickVal == 'autoComplete')) {
                                            $inputType = 'pickId';
                                            // define  row ($k) and colname ($kn)
                                        
                                            // dd($inlineFormPar);
                                            $ev1 = $colOptions[$kn]['onEventFn'] . '(' . $k . ')';
                                        }
                                        
                                    @endphp


                                    @if ($inputType == 'normal')
                                        <input wire:model="inlineForm.{{ $k }}.{{ $kn }}"
                                            {{ $ev }} {{ $ev1 }}
                                            class="form-control {{ $widget }}"
                                            placeholder="{{ $colOptions[$kn]['arabic_name'] }}"
                                            title="{{ $inlineForm[$k][$kn] }}">
                                    @endif

                                    @if ($inputType == 'pickId')
                                        <input type="hidden"
                                            wire:model="inlineForm.{{ $k }}.{{ $kn }}">
                                        <input wire:model="inlineFormPar.{{ $k }}.{{ $kn }}"
                                            {{ $ev }} {{ $ev1 }}
                                            class="form-control {{ $widget }}"
                                            placeholder="{{ $colOptions[$kn]['arabic_name'] }}"
                                            title="{{ $inlineForm[$k][$kn] }}">
                                    @endif



                                    @if (isset($autoArr[$k][$kn]) && strlen($autoArr[$k][$kn]) > 0)
                                        <div style="position:absolute;background-color:rgb(255, 255, 255);height:100px;z-index:10">
                                            {!! $autoArr[$k][$kn] !!}
                                        </div>
                                    @endif

                                    @error('inlineForm.' . $k . '.' . $kn)
                                        <span class="error" style="text-align:left;dirction:rtl;display: inline-block">{{ $message }}</span>
                                    @enderror

                                </td>

                            @endforeach

                            <td>
                                @if ($formType == 0 || !isset($rw[$this->primaryKey]))
                                    <input type="button" wire:click="removeRow({{ $k }})"
                                        class="btn btn-warning" value="remove{{ $k }}">
                                @else
                                    @if (isset($rw[$this->primaryKey]))
                                        <input type="button"
                                            wire:click="deleteRow({{ $rw[$this->primaryKey] }} , {{ $k }})"
                                            class="btn btn-danger" value="delete({{ $k }})">
                                    @endif
                                @endif
                            </td>



                        </tr>
                    @endforeach

                </table>
                <input type="button" class="btn btn-success" wire:click="newRow" value="إضافة قلم جديد">

            </div>
        </div>


        @if ($formType == 0)
            <input type="button" class="btn btn-info" wire:click="saveRows" value="حفظ">
        @else
            <input type="button" class="btn btn-success" wire:click="upRows" value="تحديث">
        @endif

    </div>


    <div class="text-center">Pre Total :{{ $totalBefore }}</div>
    <div class="text-center">Vat Total :{{ $totalVat }}</div>
    <div class="text-center">The Total :{{ $total }}</div>


    <div x-data="{ show: false }" x-show="show"
        x-init="@this.on('alerty', ()=>{show=true;setTimeout(() =>{show = false}, 5000)})"
        class="bg-green-200 text-black mr-4 px-2" id="topMsg" class="topMsg">
        @if (isset($msgs))
            {!! implode('<br>', array_reverse($msgs)) !!}
        @endif
    </div>

    <div>
        <div>Massages :</div>
        @if (isset($msgs))
            {!! implode('<br>', $msgs) !!}
            @php $this->msgs=null @endphp
        @endif
    </div>

    <hr>
    <div>
        <ul>
            <li>ايجاد قيمة الكودات في الجفول الموازية</li>
            <li>complete validation </li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</div>