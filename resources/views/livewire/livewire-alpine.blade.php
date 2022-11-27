<div>  

    <div x-data="initApp()">

        <div>
            <input type="button" @click="setcategory(28)" value="مشروبات" />
            <input type="button" @click="setcategory(20)" value="وجبات" />
        </div>
        

        <div class="comp">
            <template x-for="pro in filterPro()">
                <div class="divExamp">
                    <div x-text="pro.name"></div>
                    <div x-text="pro.category"></div>
                    <div x-text="pro.selprice"></div>
                </div>
            </template>
        </div>
        
    </div>

    <script>
        function initApp() {

            const app = { 
                
                    filterNo:null,
            
                    filterPro() {
                        return @js($products).filter( (pro) => pro.category == this.filterNo);
                    },
                    setcategory(cat) {
                        console.log(this.filterNo);
                        this.filterNo = cat;
                    }
                }
            
                return app;
         }
     </script>

</div>
