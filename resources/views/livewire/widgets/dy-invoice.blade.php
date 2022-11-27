<div  class="comp">
     <template x-if="itemState">
      <template  x-for="(pro , index) in invoiceitems" :key="pro.pro_id">
                <div  class="row mb-1 proDetailes" x-init="console.log(index,invoiceitems)" wire:ignore>              
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