<?php
namespace App\MyClass;

use DB;
trait salesPointsExt
{

    public function savePosInvoice($inv , $customer){

       // dd($inv);

        if(empty($inv["customer_acc_no"])){
            $inv["customer_acc_no"]=50;
        }
             
       $customerAr = [
            'customer_id' => null,
            'customer_name' => $customer["customer_name"],
            'customer_address' => $customer["customer_address"],
            'customer_phone' => $customer["customer_phone"],
            'customer_email' => $customer["customer_email"],
            'customer_notes' => $customer["customer_notes"],
            'customer_acc_no' =>  $customer["customer_acc_no"],
            'customer_points' => $customer["customer_points"],           
       ];

       $customer =  DB::table('pos_customer')->where("customer_phone" ,$customer["customer_phone"])->first();
     
       if(empty($customer)){
       $customer_id = DB::table('pos_customer')->insertGetId($customerAr);
       }else{
       $customer_id = $customer->customer_id;
       }  

       $invoiceHeader = [

            'invoice_id' => null,
            'ref' => $inv["ref"],
            'issue_date' => $inv["issue_date"],
            'due_date' => $inv["due_date"],
            'payment_state' => $inv["payment_state"],
            'subtotal' => $inv["subtotal"],
            'discount' => $inv["discount"],
            'tax' => $inv["tax"],
            'salesman' => $inv["salesman"],
            'customer_id' => $customer_id,
            
       ];

     

       DB::table('pos_invoices')->insert($invoiceHeader);
      
      
        $invoiceItems = $inv["items"];

         foreach ($invoiceItems as $val){
         unset($val["name"]);
         DB::table('pos_invoice_items')->insert($val);           
         $this->msgs[] = "تم حفظ الفاتورة بنجاح";
         $this->updateCustomers();
         $this->emit("updateCustomers");
         }
    }


    public  function excuteInialQueries(){
     $this->passToAlpine =DB::table('simpleproducts')->join("up_images" , "up_images.id" ,"=" ,"simpleproducts.gallary")->get() ; 
     $this->categories = DB::table('catagories')->where("parent_id",19)->orderBy("list_order","asc")->get();
     $this->customers = DB::table('pos_customer')->groupBy('customer_phone')->get();
     }

     public function updateCustomers(){
        $this->customers = DB::table('pos_customer')->groupBy('customer_phone')->get();
     }

     
    public function getWidgetBeforeContent($rid, $fn)
    {
        // excute first row in form only
       // $this->passToAlpine =json_encode( DB::table('simpleproducts')->get());
       

        if ($fn == 'myprojects') {
            $str = $this->colArr[$rid]["proName"] . "-" . $this->colArr[$rid]["myproj_id"];
            return "<div style='border:1px dotted blue'>{$str}</div>";
        }

        if ($fn == 'pos_products') {         
            return "<div style='border:1px dotted blue'>Before Widget</div>";
        }

        if ($fn == 'invoices') {         
            return "<div style='border:1px dotted blue'>Before Widget</div>";
        }


    }

    public function getRightSideContent($rid, $fn)
    {
        if ($fn == 'myprojects') {
            $str = "right Sid form";
            return "<div class='col-2 p-2' style='border:1px dotted blue'><label>{$str}</label></div>";
        }

        if ($fn == 'pos_products') {
            $str = $this->colArr[$rid]["name"];
            return "<div class='col-2 p-2' style='border:1px dotted blue'>{$str}</div>";
        }

        if ($fn == 'pos_products') {
            //$str = $this->colArr[$rid]["name"];

            $str = "left";
            return "<div class='col-2 p-2' style='border:1px dotted green'>{$str}</div>";
        }

    }

    public function getLeftSideContent($rid, $fn)
    {
        if ($fn == 'myprojects') {
            $str = "left Sid form";
            return "<div class='col-1' style='border:1px dotted blue'>{$str}</div>";
        }
    }

    public function getWidgetAfterContent($row_id, $fn)
    {
        if ($fn == 'myprojects') {
            $str = "left after form";
            return "<div class='col-12' style='border:1px dotted red'>{$str}</div>";
        }
    }

    public function getAfterEveryField($rid, $fn, $colName)
    {

        //ddd($rid, $fn , $colName);
        //if($fn == "pos_products")
        // return  $this->buildImageWidget($rid, $fn , $colName);

    }

      
    public function getProductOfClass ($rid , $fn){

        $id = $this->colArr[$rid]["code"];

       // dd($id ,  $this->dynamicQuery["pos_products"][2]);

        $this->dynamicQuery["pos_products"][2]= $id;

        $this->formsLoop();
       
            
    }


}
