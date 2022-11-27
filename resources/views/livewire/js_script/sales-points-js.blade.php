
 <script>
      console.log("include Alpine........");
            function initApp() {
   
               const app = {
                           showingForm:[],                       
                           filterNo:null,
                           selectedPro:null,
                           currentInvoice :{},
                           currentCustomer:{},
                           invoiceitems:[],
                           invoices:[],
                           itemState :true,                        
                           invoiceState :0,
                           invoiceTotal:0,
                           currentFloat :[],
                           categories:[],
                           customers:[],
                           formOptions:@js($fOpts),
                           autoFeilds:["customer_phone" , "customer_name" ],
                           hiddenFields :["customer_id" , "invoice_id" , "due_date" , "ref" ,"items"],

                           initFunc(){
                            this.newCustomer(); 
                            this.newInvoice();
                            this.categories=@js($categories);
                            this.customers = @entangle('customers');
                            this.customers = this.customers.initialValue; 
                            this.showingForm['myprojects']="none";
                            this.showingForm['simpleproducts_in']="none";
                            this.filterNo=20;     
                            document.addEventListener('DOMContentLoaded', () => {        
                               Livewire.on("updateCustomers",()=>{
                                app.customers = @entangle('customers');
                                app.customers = app.customers.initialValue; 
                                console.log(app.customers);
                              });
                            })                           
                            console.log("init Function...");
                            console.log("customer :",this.customers.initialValue);
                           },

                           hideForm(fn){
                            this.showingForm[fn]="none";                             
                           },

                           showForm(fn){
                            this.showingForm[fn]= undefined;                           
                           },
                            

                           printObject(){

                            console.log("currentInvoice:" ,this.currentInvoice);
                            console.log("currentCustomer:" ,this.currentCustomer);
                            console.log("invoiceitems:" ,this.invoiceitems);


                           },

                           selectCustomer(val ,objName){
                           // this[objName][key]=val;
                           // console.log(val , objName);
                            this[objName] ={...val};
                           // console.log(val , objName , this.currentCustomer);
                            this.currentFloat[0]='';
                           },

                           hideFloat(){
                            this.currentFloat[0]='';

                           },

                           showAutoSelectList(key,objName){
                               
                            if(this.autoFeilds.includes(key)){
                                if( this[objName][key] == ""){
                                    this.currentFloat[0]='';
                                    return;
                                }   
                                this.currentFloat[0] = key;
                                var findword = this[objName][key];
                                this.currentFloat[1] = this.customers.filter((ob)=> {return ob[key].includes(findword)});
                              // console.log(this.currentFloat);
                             }
                            },
   
                           openInvoice(ref){
                             if(this.invoiceitems.length == 0){
                             this.currentInvoice = app.invoices.find(invoice => invoice.ref === ref);
                             this.invoiceitems = this.currentInvoice.items;
                             this.currentInvoice.state = "edit";
                             this.invoiceState = 1;  
   
                            }else{
                               alert("يجب حفظ الفاتورة الحالية");
                            }
   
   
                           },
   
                           removeProFromItems(index){
                               this.invoiceitems.splice(index,1);
                               this.getSumofInvoiceItems();
                           },                                               
                           getSumofInvoiceItems(index){
                               if( this.invoiceitems.length>0){
                                   if(typeof index!=="undefined")
                                   this.invoiceitems[index].subtotal = this.invoiceitems[index].selprice * this.invoiceitems[index].quantity;
                                   let sum = this.invoiceitems.map(o => o.subtotal).reduce((a, c) => { return a + c });                         
                                   this.currentInvoice.subtotal = sum;
                                   this.invoiceTotal = sum;
                                  // console.log(index);
                               }else{
                                   this.currentInvoice.subtotal = 0;
                                   this.invoiceTotal=0;
                               }
                           },
   
                           ifArrayNotEmpty(){
   
                               if(this.invoiceitems.length > 0){
                                   return false;
                               }else{
                                   return true;
                               }
                           },
         
                         
   
                           saveCurrentInvoice(saveType) {
   
                           if(this.invoiceitems.length > 0){
                         
                               let index = this.invoices.findIndex(invoice => invoice.ref === this.currentInvoice.ref);
                               this.currentInvoice.items = this.invoiceitems;
                               tmpObject = this.currentInvoice;
                               // if  this.currentInvoice in invoices then update else push  this.currentInvoice in invoices
                               if(index !== -1){
                                   this.currentInvoice.state = saveType; 
                                  
                                   this.invoices[index] = this.currentInvoice;                           
                               }else{
                                   this.currentInvoice.state = saveType;                               
                                   this.invoices.push(this.currentInvoice);                            
                               }                    
                                   this.currentInvoice={};                          
                                   this.invoiceState = 0;
                                   this.invoiceitems=[];
   
                                   this.newInvoice();
                                   if(saveType=="saved"){     

                                       Livewire.emit("savePosInvoice" , tmpObject ,this.currentCustomer);
                                     
                                   }
                           }else{
                               alert("يجب إضافة عنصر إلى الفاتورة");
                           }
                            //  console.log("totalinvoice" ,this.invoices ,  this.invoiceitems.length);
                           },
                           
                           holdInvoice(){
                            this.saveCurrentInvoice("hold")
                           },
                           changeInvoice(){
                            console.log(this.currentInvoice);
                           },
   
                           filterPro() {
                               return @js($passToAlpine).filter( (pro) => pro.category == this.filterNo);
                           },
                           filterByproId(){
                               return @js($passToAlpine).filter( (pro) => pro.id == this.selectedPro);  
   
                           },
                           addItemToInvoice(pro){
                          // this.printObject();
                               if(this.invoiceState == 1){
   
                                   var obj ={}; 
   
                                   obj.invoice_id  = null ;
                                   obj.ref = this.currentInvoice.ref;
                                   obj.pro_id = pro.id ;
                                   obj.name = pro.name;
                                   obj.selprice = pro.selprice;
                                   obj.img = pro.file_name;                                
                                   obj.quantity = 1;
                                   obj.subtotal = pro.selprice * obj.quantity;
   
                                   objIndex = this.invoiceitems.findIndex((obj => obj.pro_id == pro.id));
   
   
                                   if(objIndex !== -1){
                                   this.invoiceitems[objIndex].quantity++;
                                   this.invoiceitems[objIndex].subtotal = this.invoiceitems[objIndex].selprice * this.invoiceitems[objIndex].quantity;
                                   }else{
                                   this.invoiceitems.push(obj);
                                   }
                                  
                                   this.getSumofInvoiceItems();
                          
                                 //  console.log(this.invoiceitems);
                                
   
                               }else{
   
                                   alert("يجب إضافة عنصر إلى الفاتورة");
                               }
   
                           },
                           getSelectedItems(){
                               return this.invoiceitems;
                           },
                           setcategory(cat) {
                               console.log(cat);
                               this.filterNo = cat;
                           },
   
                           newCustomer(){

                               //alert("Customer Added");
   
                               let objc = {}; 
                                   
                                   objc.customer_id = 1;
                                   objc.customer_name =  "عميل ماشي";
                                   objc.customer_phone  = "000";
                                   objc.customer_address = null;                                  
                                   objc.customer_email  = null;
                                   objc.customer_notes  = null;                              
                                   objc.customer_acc_no = 50;
                                   objc.customer_points = null;
                               
   
                                   this.currentCustomer = objc;
   
                                   // console.log("currentCustomer:",this.currentCustomer);
                                  // alert(this.currentCustomer.customer_name);
                           },
   
                           newInvoice(){
                            // if invoive state =0  create a new invoice                             
                            if(this.invoiceState == 0){
                                   this.invoiceState = 1;
                                   var obj = {}; 
                                   obj.invoice_id = null;
                                   obj.ref= this.makeid(20);                         
                                   obj.issue_date =this.formatDate(new Date());
                                   obj.due_date =this.formatDate(new Date());                               
                                   obj.payment_state = 0;
                                   obj.subtotal = 0;
                                   obj.discount = 0;
                                   obj.tax = 15;
                                   obj.total = 0;
                                   obj.items = [];                        
                                   obj.salesman=1;
                                   obj.location = 1; // inventory
                                   obj.status = 'new';
                                   obj.salesman=1;
                                   obj.customer_id = null;

                                   this.currentInvoice = obj; 
   
                                  // console.log("newInvoice:",this.invoices);
                            }else{
                                alert("يجب حفظ الفاتورة الحالية");
                            }
                           },
                           makeid(length) {
                                   var result           = '';
                                   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                                   var charactersLength = characters.length;
                                   for ( var i = 0; i < length; i++ ) {
                                   result += characters.charAt(Math.floor(Math.random() *charactersLength));
                                   }
                                  return result;
                              } ,
                              formatDate(date) {
                                       var hours = date.getHours();
                                       var minutes = date.getMinutes();
                                       var ampm = hours >= 12 ? 'pm' : 'am';
                                       hours = hours % 12;
                                       hours = hours ? hours : 12; // the hour '0' should be '12'
                                       minutes = minutes < 10 ? '0'+minutes : minutes;
                                       var strTime = hours + ':' + minutes + ' ' + ampm;
                                       return date.getFullYear()+"-"+date.getMonth()+"-"+ date.getDate() + " " + strTime;
                             }                   
                       
                           
                       }
   
                    return app;
               }
               
         
                  
           
</script>
