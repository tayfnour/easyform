<div class="el">
    <div class="card section_class el">
        <div class="card-header container_class el">
                <div class="container" >
                        <div class="row row_class el">                            
                            <div class="col-12 col-4_class el p-2">
                                <select wire:model="forms" class="form-select el">
                                    @foreach ($form_names as $fo)
                                        @if(!in_array($fo->tableName , $blockTables))
                                            <option class="el">{{$fo->tableName."/".$fo->formName}}</option> 
                                        @endif                           
                                    @endforeach                        
                                </select>
                            </div>                           
                        </div>
                </div>        
        </div>
    

        <div class="card-body el">
            {{-- {{dd($forms)}} --}}
            @livewire("form-builder" , ["forms" => $forms])
        </div>

    </div>  
</div>