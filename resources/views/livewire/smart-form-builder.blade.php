<div>
    <div x-data="{formData:@entangle('formData'),
                  temArr:['aa','cc','dd'],
                  getForms(){ return this.formData['_formsArr']}
                }" 
         x-init="console.log(getForms())">  
         
        <div class="container-fluid fluid_1" >
                <div class="px-1 pt-1 mx-1 row" style="border-radius:10px;background-color:rgb(255, 252, 80)">
                    <div class="col-4">
                        <h4>
                        Smart Form Builder (v1.0)
                        </h4>
                    </div>

                    <div class="col-2">
                        <h4 style="color:rgb(20, 220, 53)">
                            {{-- Validate State : "" --}}
                        </h4>
                    </div>

                    <div class="col-3">
                        <input wire:click="init" class="btn btn-warning btn-xs" type="button" value="Refresh">
                        <input wire:click="showGlobalVar" class="btn btn-warning btn-xs" type="button" value="global Var">
                        <input wire:click="showFloatComponent('tree-manager-component')" class="btn btn-warning btn-xs"
                            type="button" value="Tree Management">
                    </div>

                    <div class="col-3">
                        <h4 class="text-end">
                        باني لوحة التحكم الذكي
                        </h4>
                    </div>
                </div>
            </div>{{-- end first container --}}
          
                      <nav aria-label="breadcrumb"  style="margin: 2px 22px;">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">{{$proName}}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{$proPage}}</a></li>                 
                      </ol>
                    </nav>  
            
           

            <div class="my-3 container-fluid fluid_2"  wire:ignore>
                <div class="pt-1 row" style="direction: rtl;background-color:#eee"> 
                    <div class="col-12">
                        <template x-for="fxname in formData._formsArr"  >                            
                                <template  x-if="formData[fxname]['_fprops']['isFloat']=='1'">                         
                                <span  @click="$refs[fxname].style.display='block';setTimeout(()=>saveStateOfEles(),400)" x-text="fxname" class="btn btn-warning btn-xs"></span>
                                {{-- <div class="invisible-form" @click="showForm(fname)" ><span>&#11200;</span><span style="margin-left:5px" x-text="fname"></span></div>     --}}
                                </template>
                               
                        </template>
                    </div>           
                </div>
            </div>   

            {{-- <div class="my-3 container-fluid fluid_3">
                <div class="pt-1 row" style="direction: rtl;background-color:#eee"> 
                    <div class="col-12">
                        <div class="float-form newInvoice" @click="newInvoice()" ><span>&#11200;</span><span style="margin-left:5px">فاتورة جديدة</span></div>
                            <template x-for="inv in invoices">
                                <template x-if="inv.state == 'hold'">
                                    <div class="float-form newInvoice" @click="openInvoice(inv.ref)" ><span>&#11200;</span><span style="margin-left:5px" x-text="inv.ref"></span></div>
                                </template>
                            </template>
                    </div>
                </div>
            </div>   --}}



            <div class="container-fluid fluid_4"  >{{-- start main container --}}
                <div class="row row_main" style="direction: rtl;border-radius:10px ;background-color:wheat">      
                    
                    @php  

                   // dd($formlayouts);
                    foreach ($formlayouts as $fname => $form) { 

                     $colWidth = isset($formData[$fname]['formBS'])?$formData[$fname]['formBS']:'12'  ;
                     $isfloat= $formData[$fname]['isFloat'];


                    //  $colWidth = '12';
                    //  $isfloat= '0';

                     if($isfloat =='1'){
                       echo "<div  id='form_{$fname}' x-ref='{$fname}' class='floatDiv' ><div @click=\"\$el.parentElement.style.display='none';setTimeout(()=>saveStateOfEles(),400)\" style='font-size:20px;cursor: pointer;'>x</div>";
                     }

                     echo "<div class='col-{$colWidth}  form-wrap'>
                           <div class='row' style='flex-direction: column;min-height:610px;border-radius:8px;background-color:#f7f7f8;margin:10px 10px 0px 10px;border:2px solid #ddd' >";
                     echo   $form;

                     echo "<div class='text-center' wire:loading wire:target='formOpts.$fname'>Loading...</div>";
                     echo "</div>"; // close row
                     
                   

                     echo '<div class="row" style="background-color:#ddd;margin:0px 10px 10px 10px">';

                     echo '<div class="col-8" style="overflow-x:auto;">';
                    
                      
                     if (isset($paginate[$fname])) 
                     echo $paginate[$fname] ;//$bs_arrows[$fname]->links();

                     echo '</div>';

                     echo '<div class="p-2 group-control col-4"><div class="input-group">';
                     
                     echo  "<input type='button' class='btn btn-warning' value='سجل جديد' wire:click='createNewFormRow(\"{$fname}\")'>";
                     
                     echo  "<input type='text' class='form-control' placeholder='فلترة' wire:model.debounce.1000ms='filter.$fname'>";

                     echo '</div></div>';


                     echo '</div></div>';    
                     
                     if($isfloat=='1'){
                        echo '</div>';
                     } 

                    }
                    @endphp

                </div>{{-- end second Row --}}
            </div>{{-- end main container --}}


            @if (isset($this->msgs))
                <div id="topMsg" class="topMsg">
                    {!! implode('<br>', $this->msgs) !!}
                    @php $this->msgs=null @endphp
                </div>
            @endif 

        
            {{-- @include("livewire.js_script.sales-points-js")  --}}
    

    </div>  
</div>





