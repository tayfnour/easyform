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
