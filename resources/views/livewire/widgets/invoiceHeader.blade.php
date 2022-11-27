<div>
    <div>Invoice Header</div>
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
                        <template x-for="(val , key) in currentCustomer">
                           <template x-if="hiddenFields.includes(key) == false" > 
                            <div @click.outside="hideFloat()" class="col-6 divExamp"> 
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
                            <template x-if="hiddenFields.includes(key)==false" > 
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