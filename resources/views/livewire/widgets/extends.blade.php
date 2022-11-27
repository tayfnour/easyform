@section("invoiceHeader")
<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <div class="row">             
                           
                        <div class="col-12">
                            <input type="button" @click="saveCurrentInvoice('saved')" value="حفظ" style="text-align:center;white-space: break-spaces;;width:100%;height:60px;margin-top:5px"/> 
                            <input type="button" @click="holdInvoice()" value="تعليق" style="text-align:center;white-space: break-spaces;;width:100%;height:60px;margin-top:5px"/> 
                        </div>
              
                </div>
            </div> 

            <div class="col-10">
                <div class="row">                
                    <template x-if="currentCustomer !== null">
                        <template x-for="(val,key) in currentCustomer">
                           <template x-if="!hiddenFields.includes(key)" > 
                            <div class="col-6 divExamp"> 
                                <label x-text="key"></label>                                                              
                                <input @keyup="showAutoSelectList(key,'currentCustomer')" class="form-control" type="text" :id="key"  x-model="currentCustomer[key]" />                                                                      
                                <template x-if="currentFloat[0] == key">
                                    <div class="autoSelectList">
                                        <template x-for="objval in currentFloat[1]">
                                          <div x-text="objval[key]" @click="selectCustomer(objval,'currentCustomer')"></div>
                                        </template> 
                                    </div>                              
                                </template>
                            </div>
                           </template> 
                        </template> 
                    </template>                            
                </div>
            
              <hr>
         
                <div class="row">
                    <template x-if="currentInvoice !== null">
                        <template x-for="(val,key) in currentInvoice">
                            <template x-if="!hiddenFields.includes(key)" > 
                                <div class="col-6 divExamp"> 
                                    <label x-text="key"></label>                                                              
                                    <input class="form-control" type="text" :id="key"  x-model="currentInvoice[key]" />                                                                      
                                </div>
                            </template>     
                        </template> 
                    </template>                            
                </div>
            </div>         
        </div>             
    </div>
</div>
@endsection

@section("dynamic-product")
<div> 
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <div class="row">              
                    <template x-for="cat in categories">            
                        <div class="col-12">
                            <input type="button" @click="setcategory(cat.code)" :value="cat.name" style="text-align:center;white-space: break-spaces;;width:100%;height:60px;margin-top:5px"/> 
                        </div>
                    </template>
                </div>
            </div> 
            <div class="col-10">
                <div class="row comp">
                        <template x-for="pro in filterPro()">
                            <div class="col-4 divExamp" @click="addItemToInvoice(pro)">
                                <div x-text="pro.name" style="background-color:orange"></div>
                                <div x-text="pro.id"></div>
                                <div x-text="pro.category"></div>
                                <div x-text="pro.selprice"></div>
                                <div>
                                    <img :src="`http://localhost/global_images/${pro.file_name}`"  width="100%" height="150px" />
                                </div>                                        
                            </div>
                        </template>                        
                </div>
            </div>         
        </div>             
    </div>
</div>
@endsection

@section("dy-invoice")
<div class="comp">
    <template x-if="itemState">
     <template x-for="(pro , index) in invoiceitems" :key="pro.pro_id">
               <div class="row mb-1">              
                   <div class="col-2">            
                       <img :src="`http://localhost/global_images/${pro.img}`"  style="width:100%;height:50px" />                             
                   </div>       
                   <div class="col-10">
                       <div class="row">                         
                           <div class="col-12"  style="background-color:rgb(234, 210, 250)">
                               <span  x-text="pro.name"></span>
                               <span @click="removeProFromItems(index)" style="float:left"><i class="fa fa-times tip pointer posdel"  title="Remove" style="cursor:pointer;"></i></span>
                           </div>                            
                           <div class="col-12">
                               <div class="row" >
                                    <div class="col-5">
                                      <label for="">الكمية</label><span x-text="index" style="color:red"></span>
                                       <input  @keyup="getSumofInvoiceItems(index)" class="form-control rounded-2 input-sm p-1" type="text" x-model="pro.quantity" />                         
                                     </div>
                                     <div class="col-5">
                                        <label for="">السعر</label>
                                        <input  @keyup="getSumofInvoiceItems(index)" class="form-control rounded-2 input-sm p-1"  type="text" x-model="pro.selprice" /> 
                                     </div>  
                                     <div  class="col-2">
                                        <label for="">المجموع</label>
                                           <input class="form-control rounded-2 input-sm p-1"  type="text" x-model="pro.subtotal" />
                                     </div>
                                  
                               </div>
                             
                           </div>                           
                       </div>                        
                   </div>          
               </div>          
      </template> 
   </template> 

   <div class="row" style="background-color: yellow">        
      <div class="col-3"></div>  
      <div class="col-3">المجموع</div>
      <div class="col-3"  x-text ="invoiceTotal"></div>
      <div class="col-3"></div>        
   </div>   
               
</div>         
@endsection