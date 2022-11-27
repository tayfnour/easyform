<?php
namespace App\MyClass;

use DB;

trait purchasesManageExtend
{

    public $msgs = [];
    public $submit_visible;
    public $baseRef;
    public $targetRefCol;
    public $autoArr;
    public $billResult=[];

    public function calculateProductCount($product_id)
    {
        $res_purch = DB::table("bills")->where("product_id", $product_id)->sum("quantity");
        $res_sales = DB::table("invoices")->where("product_id", $product_id)->sum("quantity");
        return $res_purch - $res_sales;
    }

    public function getRandomStr($len)
    {
        return bin2hex(random_bytes($len));
    }

    public function geOptionsOfForm($formName)
    {
        //  form MustBe Unique
        $arrOfOpts = [];
        $rows = DB::table("coloptions")->where("formName", $formName)->get();
        foreach ($rows as $k => $row) {
            foreach ($row as $kn => $col) {
                $arrOfOpts[$row->colName] = (array) $row;
            }
        }

        return $arrOfOpts;
    }

    public function getAutoIncreamentName($tb_name)
    {
        $result = DB::select(DB::raw("SHOW KEYS FROM {$tb_name} WHERE Key_name = 'PRIMARY'"));
        return $result[0]->Column_name;
    }

    public function sortColumns($opts)
    {
        // dd($opts);

        $newArr = [];

        foreach ($opts as $k => $ops) {
            if ($k != "cols") {
                $newArr[$ops["ordering"]] = $k;
            }

        }
        ksort($newArr);
        return array_values($newArr);
    }

    public function buildEvents($eventStr)
    {

        if (empty($eventStr)) {
            return '';
        }

        $parsingString = "";
        $eventsArr = explode(" ", $eventStr);
        foreach ($eventsArr as $evArr) {
            $parStr = "";
            $eAr = [];
            $eAr = explode("|", $evArr);
            $parAr = explode(",", $eAr[2]);

            foreach ($parAr as $pr) {
                // $par = $this->{$pr};
                $par = $pr;
                $parStr .= is_numeric($par) ? "{$par}," : "'{$par}',";
            }

            $parsingString .= " wire:" . $eAr[0] . "=" . $eAr[1] . "(" . rtrim($parStr, ",") . ")";
        }
        return $parsingString;
    }

    public function createNewAccountUnderParent()
    {

    }

    public function createNewSupplier($row_id)
    {

        $id = $this->createNewFormRow(null, "suppliers", "getId");

        $this->colArr[$row_id]["supplier"] = $id;

        $this->saveRowOnUpdate($row_id);

    }

    public function moveBillItemToEntries($row_id, $col)
    {

        $this->calculateOtherField($row_id, $col);

        $total = $this->colArr[$row_id]["total"];
        $preTotal = $this->colArr[$row_id]["preTotal"];
        $vatVal = $this->colArr[$row_id]["vatVal"];
        $id = $this->colArr[$row_id]["bill_id"];
        $ref = $this->colArr[$row_id]["ref"];

        //   dd("entry");

        DB::table('entries')
            ->updateOrInsert(

                ["entry_no" => $ref, "bill_num" => "Bill_" . $id, "entry_type" => "sales"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "Bill_" . $id,
                    "debtor" => $preTotal,
                    "creditor" => 0,
                    "acc_name" => 48,
                    "discription" => "Automatic Entry",
                    "entry_type" => "sales",
                ]
            );

        DB::table('entries')
            ->updateOrInsert(

                ["entry_no" => $ref, "bill_num" => "Vat_" . $id, "entry_type" => "vat"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "Vat_" . $id,
                    "debtor" => $vatVal,
                    "creditor" => 0,
                    "acc_name" => 24,
                    "discription" => "Automatic Entry",
                    "entry_type" => "vat",
                ]
            );

        DB::table('entries')
            ->updateOrInsert(

                ["entry_no" => $ref, "bill_num" => "Bill_" . $id, "entry_type" => "supplier"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "Bill_" . $id,
                    "debtor" => 0,
                    "creditor" => $total,
                    "acc_name" => $this->refs["suppliers"]["account_num"],
                    "discription" => "Automatic Entry",
                    "entry_type" => "supplier",
                ]
            );

    }

    public function payForBill($row_id)
    {

        $supplier_acc_id = $this->refs["suppliers"]["account_num"];

        if (empty($supplier_acc_id)) {
            // يقطع الريكويست ويرجع برسالة
            $this->msgs[] = "يجب تحديد مورد لكي تم عملية السداد.";
            return;
        }

        $opts = $this->formOpts[$this->rowOpts[$row_id][0]];
        $pk = $opts["pk"];
        $id = $this->colArr[$row_id][$pk];
        $ref = $this->colArr[$row_id]["bill_ref"];
        $payval = $this->colArr[$row_id]["payValue"];

        $pay_for_acc_id = $this->colArr[$row_id]["account_id"];

        // تسجيل دفعة للمورد في حسابه

        DB::table('entries')
            ->updateOrInsert(

                ["entry_no" => $ref, "bill_num" => "PYT_" . $id, "entry_type" => "payments"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "PYT_" . $id,
                    "debtor" => $payval,
                    "creditor" => 0,
                    "acc_name" => $supplier_acc_id,
                    "discription" => "Payments Automatic Entry",
                    "entry_type" => "payments",
                ]
            );

        // تسجيل من اي حساب عملنا الدفع
        DB::table('entries')
            ->updateOrInsert(

                ["entry_no" => $ref, "bill_num" => "OUC_" . $id, "entry_type" => "outcome"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "OUC_" . $id,
                    "debtor" => 0,
                    "creditor" => $payval,
                    "acc_name" => $pay_for_acc_id,
                    "discription" => "Outcome Automatic Entry",
                    "entry_type" => "outcome",
                ]
            );

        $this->msgs[] = "تمت عملية السداد بنجاح";

        $this->formsLoop();
    }

    public function calculateOtherField($row_id, $col)
    {

        // $opts = $this->formOpts[$this->rowOpts[$row_id][0]];

        // $action = $opts[$col]['dyInitVal'];

        // define any col will do change not any col

        //   if ($action == "setTotal") {

        $price = intval($this->colArr[$row_id]["price"]);
        $quantity = intval($this->colArr[$row_id]["quantity"]);
        $discountType = intval($this->colArr[$row_id]["discountType"]);
        $discount = intval($this->colArr[$row_id]["discount"]);
        $vat = intval($this->colArr[$row_id]["vat"]);

        $totalBefore = $price * $quantity;

        $discountType = $discountType == null ? 0 : $discountType;

        if ($discount > 0 && $discountType == 2) {
            $totalBefore = $totalBefore - $discount;
        } else {
            $totalBefore = $totalBefore - ($totalBefore * $discount / 100);
        }
        $vatValue = ($totalBefore * 15) / 100;
        $total = $totalBefore + $vatValue;
        $this->colArr[$row_id]["discountType"] = $discountType;
        $this->colArr[$row_id]["preTotal"] = $totalBefore;
        $this->colArr[$row_id]["vatVal"] = $vatValue;
        $this->colArr[$row_id]["total"] = $total;
        //    }

    }

    // hook end of get and filling data from database in variable and arrays
    // and befor dispaling view

    public function endOfFillingWithData(){

        $ref = $this->refs["Create New Invoice Header"]["ref"];
        if(isset($ref))
        $this->billResult= $this->calculateBillResults($ref);

      //  dd($this->billResult);
    }

    public function calculateBillResults($ref){

        $preTotal = DB::table("bills")->where("ref", $ref)->sum("preTotal");
        $preTotal = round($preTotal, 2);
        $vatVal = DB::table("bills")->where("ref", $ref)->sum("vatVal");
        $vatVal = round($vatVal, 2);
        $total = round($preTotal + $vatVal, 2);
        $pays = DB::table("operations")->where("bill_ref",$ref)->sum("payValue");
        //$pays = DB::table("entries")->where("entry_no", $this->colArr[$row_id]["ref"])->sum("creditor");
        $amountDue = $total - $pays;

        if ($amountDue == 0) {
            $stateOfBill = 3;
        } elseif ($amountDue > 0 && $amountDue < $total) {
            $stateOfBill = 2;
        } elseif ($pays == 0) {
            $stateOfBill = 1;
        }

        DB::table("bill_headers")->where("ref",$ref)->update(
            ["payment_state" => $stateOfBill]
        );

        return [ "total" =>  $total ,  "amountDue" => $amountDue , "pays" => $pays ,  "vatVal" =>  $vatVal ,"preTotal"=> $preTotal ];
    }

   public function  savePhoto ($row_id , $colName){

    $filename = $this->photos[$row_id][$colName]->store( "simpleproducts", "global_images");
     
   // dd($filename);
      
    $id =  DB::table("up_images")->insertGetId([           
            "path" => "simpleproducts",
            "file_name" => $filename,
            "description" => $this->photosDesc[$row_id]["pic"],
            "timestamps" => date("Y-m-d H:i:s"),
        ]);

     $this->colArr[$row_id][$colName]= $id ;
    // $this->colArrPar[$row_id][$colName]= $id ;
     $this->saveRowOnUpdate($row_id, $colName);
   
    }

    public function  deletePhoto($row_id , $colName){

      $id =  $this->colArr[$row_id][$colName];
      
      
      $id =  DB::table("up_images")->where("id" , $id)->delete();

      $this->colArr[$row_id][$colName] = null;
      $this->colArrPar[$row_id][$colName] = null;
    
    
      $this->saveRowOnUpdate($row_id, $colName);

      

    //   dd( $id );
    }



    // Hook End of Form
    public function excuteFunctionEndOfForm($row_id, $action)
    {
        // $opts = $this->formOpts[$this->rowOpts[$row_id][0]];

        if ($action == "setTrialBalnceWidget") {

            $acc = isset($this->colArrPar[$row_id]['account_id']) ? $this->colArrPar[$row_id]['account_id'] : "";

            $str = "<div class='col-12'   style='height:350px;overflow-y:auto;border:2px solid #ddd;background-color:white'>
                      <table class='table table-bordered'>
                      <tr>
                      <td class='text-center' colspan='10' >حساب { $acc }  </td>
                      </tr>
                      <tr>
                      <td class='text-center' colspan='5' style='color:blue' >( الحسابات التي ساهمت بالزيادة لهذا الحساب)مدين/منه</td>
                      <td class='text-center' colspan='5'  style='color:blue' >(الحسابات التي ساهمت بالانقاص لهذا الحساب)دائن/له</td>
                      </tr>
                     <tr>
                     <td>رقم القيد</td>
                       <td>المبلغ مدين</td>
                       <td>البيان</td>
                       <td>مرجع القيد</td>
                       <td>التاريخ</td>

                       <td>رقم القيد</td>
                       <td>المبلغ دائن</td>
                       <td>البيان</td>
                       <td>مرجع القيد</td>
                       <td>التاريخ</td>
                     </tr>
                     ";

            $emptytr = "<td></td><td></td><td></td><td></td><td></td>";

            // $accName = DB::table("accounts")->select("name")->where("code" ,  $value2->acc_name)->first();
            // $BileDate = DB::table("entryheads")->select(["created_at","entry_id"])->where("entry_no" ,  $value->entry_no)->first();

            $acc_id = intval($this->colArr[$row_id]["account_id"]);

            //dd($acc_id);

            //acc_name is integer  mention to  account name
            $debitSide = [];
            $creditSide = [];
            $debitSum = 0;
            $creditSum = 0;

            //نجلب كل القيود سواء مدين او دائن
            //  هنا فقط لنعرف مرجع القيد
            $accPos = DB::table("entries")->where("acc_name", $acc_id)->groupby("entry_no")->get();

            //dd( $acc_id);

            if ($accPos) {

                foreach ($accPos as $k => $v) {

                    // dd( $v->entry_no);
                    //اذا كان الحساب مدينا
                    //  dd( $creditAccconts );
                    // account exit in entries

                    $entryAccconts = DB::table("entries")->where("entry_no", $v->entry_no)->get();

                    //dd($entryAccconts);

                    foreach ($entryAccconts as $kk => $vv) {

                        $effctAcountArr = [];
                        if ($vv->debtor > 0 && $vv->acc_name == $acc_id) {

                            // الحسابات المقابل الدائنة الذي تسببت بالزيادة
                            foreach ($entryAccconts as $kr => $vr) {

                                if ($vr->creditor > 0) {
                                    $effectedAccount = $vr->acc_name;
                                    $effectedAccountName = DB::table("accounts")->select("name")->where("code", $effectedAccount)->first();
                                    $effctAcountArr[] = $effectedAccountName->name . " <span style='font-size:10px'>({$vr->creditor})</span>";
                                }
                            }

                            $Bileheader = DB::table("entryheads")->where("entry_no", $vv->entry_no)->first();

                            // $creditEffect = DB::table("entries")->where("entry_no" ,  $v->entry_no)->where("creditor" ,">" ,0 )->first();

                            $debitSide[] = "<td>{$Bileheader->entry_id}</td>
                                      <td>{$vv->debtor}</td>
                                      <td>" . implode("<br>", $effctAcountArr) . "<span class='disc'>{$vv->bill_num}<br>{$Bileheader->decription}</span></td>
                                      <td>" . substr($vv->entry_no, -5) . "</td>
                                      <td>" . explode(' ', $Bileheader->created_at)[0] . "</td>";

                            $debitSum += floatval($vv->debtor);
                        }

                        if ($vv->creditor > 0 && $vv->acc_name == $acc_id) {

                            $effctAcountArr = [];
                            // الحساب للمقابل المدين الذي تسبب بالنقص

                            foreach ($entryAccconts as $kr => $vr) {
                                if ($vr->debtor > 0) {
                                    $effectedAccount = $vr->acc_name;
                                    $effectedAccountName = DB::table("accounts")->select("name")->where("code", $effectedAccount)->first();
                                    $effctAcountArr[] = $effectedAccountName->name . " <span style='font-size:10px'>({$vr->debtor})</span>";
                                }
                            }

                            //  $debtorAccconts = DB::table("entries")->where("entry_no" ,  $v->entry_no)->where("debtor" ,">" ,0 )->get();
                            //   $accName2 = DB::table("accounts")->select("name")->where("code" ,  $vv->acc_name)->first();

                            $Bileheader = DB::table("entryheads")->where("entry_no", $vv->entry_no)->first();

                            $creditSide[] = "<td>{$Bileheader->entry_id}</td>
                                            <td>{$vv->creditor}</td>
                                            <td>" . implode("<br>", $effctAcountArr) . "<span class='disc'>{$vv->bill_num}<br>{$Bileheader->decription}</span></td>
                                            <td>" . substr($vv->entry_no, -5) . "</td>
                                            <td>" . explode(' ', $Bileheader->created_at)[0] . "</td>";

                            $creditSum += floatval($vv->creditor);
                        }

                    }
                }
            }

            $max = max([count($debitSide), count($creditSide)]);

            for ($i = 0; $i < $max; $i++) {

                $left = isset($debitSide[$i]) ? $debitSide[$i] : $emptytr;
                $right = isset($creditSide[$i]) ? $creditSide[$i] : $emptytr;

                $str .= "<tr>{$left}{$right}</tr>";

            }

            $str .= "<tr><td>م.مدين</td><td  style='color:red'>{$debitSum}</td><td></td><td></td><td></td>";
            $str .= "<td>م.دائن</td><td   style='color:red'>{$creditSum}</td><td></td><td></td><td></td></tr>";

            $str .= "</table></div>";
            $bal = $debitSum - $creditSum;

            $str .= "<div class='text-center p-2'><h3 style='color:blue'>{$bal}</h3></div>";
            return $str;
        }

        if ($action == "setButtonTrialBalance") {

            $str = "<div class='col-12'   style='padding:10px;overflow-y:auto;border:2px solid #ddd;background-color:white'>
                           <div class='row' >
                                <div class='col-2' >
                                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value='انشاء دفتر استاذ'  >
                                </div>
                                <div class='col-2' >
                                <input  wire:click='delRow({$row_id})' type='button' class='btn btn-info wd100'  value='حذف دفتر الاستاذ'  >
                                </div>
                           </div>
                     </div>";
            return $str;

            // $this->saveRowOnUpdate($row_id,null);
        }

        if ($action == "createButtonOperation") {
            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white;z-index:1'>
                            <div class='row' >
                                <div class='col-2' >
                                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value='اجراء عملية السداد'  >
                                </div>
                                <div class='col-2' >
                                <input  wire:click='delRow({$row_id})' type='button' class='btn btn-info wd100'  value='حذف عملية السداد '  >
                                </div>
                            </div>
                    </div>";
            return $str;
        }

        if ($action == "setTotalWidget") {           

           // $this->msgs[] = "تم تعديل حالة الفاتورة";

            return $str = "<div  class='col-12 mt-1' style='padding:10px;height:250px;border:2px solid #ddd;background-color:white'>
                            <div class='row pb-2'>
                                <div class='col-3'></div>
                                <div class='col-3'></div>
                                <div class='col-3'>
                                <label class='t-1 text-start'>الاجمالي قبل الضريبة :</label>
                                </div>
                                <div class='col-3'>
                                <input class='form-control billResume' type='text' value='{$this->billResult["preTotal"]}' >
                                </div>
                            </div>
                            <div class='row pb-2'>
                                <div class='col-3'></div>
                                <div class='col-3'></div>
                                <div class='col-3'>
                                    <label class='t-1 text-start'>قيمة الضريبة :</label>
                                </div>
                                <div class='col-3'>
                                    <input class='form-control billResume' type='text' value='{$this->billResult["vatVal"]}' >
                                </div>
                            </div>
                            <div class='row pb-2'>
                                <div class='col-3'></div>
                                <div class='col-3'></div>
                                <div class='col-3'>
                                <label class='t-1 text-start'>المجموع :	</label>
                                </div>
                                <div class='col-3'>
                                <input class='form-control billResume' type='text' value='{$this->billResult["total"]}' >
                                </div>
                            </div>
                            <div class='row pb-2'>
                                <div class='col-3'></div>
                                <div class='col-3'></div>
                                <div class='col-3'>
                                    <label class='t-1 text-start'>المبالغ المدفوعة :</label>
                                </div>
                                <div class='col-3'>
                                    <input class='form-control billResume' type='text' value='{$this->billResult["pays"]}' >
                                </div>
                            </div>
                            <div class='row pb-2'>
                                <div class='col-3'></div>
                                <div class='col-3'></div>
                                <div class='col-3'>
                                    <label class='t-1 text-start'>المبلغ المستحق :</label>
                                </div>
                                <div class='col-3'>
                                    <input class='form-control billResume' type='text' value='{$this->billResult["amountDue"]}' >
                                </div>
                            </div>
                            </div>";

        }

        if ($action == "createbuttonsAreaBillHeader") {

            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white'>
            <div class='row' >

                <div class='col-4' >
                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value= 'فاتورة مشتريات جديدة'  >
                </div>

                <div class='col-4' >
                <input wire:click='createNewSupplier({$row_id})' type='button' class='btn btn-purple wd100'  value= 'إضافة مورد جديد'  >
                </div>


                <div class='col-4' >
                <input  wire:model='searching' type='text' class='form-control wd100' placeholder='رقم الفاتورة'>
                </div>


            </div>
            </div>";
            return $str;
        }

        if ($action == "setEntryBalaneced") {

            $ref = $this->refs["Create New Invoice Header"]["ref"];

            $debtorSum = DB::table("entries")->where("entry_no", $ref)->sum("debtor");
            $creditorSum = DB::table("entries")->where("entry_no", $ref)->sum("creditor");

            $str = "<div class='col-12'   style='padding:10px;overflow-y:auto;border:2px solid #ddd;background-color:white'>
                        <div class='row' >
                            <div class='col-3' >
                            </div>
                            <div class='col-3 text-center' >
                                <h3>$debtorSum</h3>
                            </div>
                            <div class='col-3 text-center' >
                            <h3>$creditorSum</h3>
                            </div>
                            <div class='col-3' >
                            </div>
                        </div>
                    </div>";
            return $str;
        }

        if($action == "addDefaultProductButton"){

            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white;z-index:1'>
            <div class='row' >
                <div class='col-2' >
                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value='اضافة منتج جديد'  >
                </div>
                <div class='col-2' >
                <input  wire:click='delRow({$row_id})' type='button' class='btn btn-info wd100'  value='حذف منتج '  >
                </div>
            </div>
            </div>";
             return $str;
        }

    }

    public function excuteFuncAfterEveryRow($row_id, $action){
      
        $str="";

        $id = $this->colArr[$row_id]["pic"];

        $res =  DB::table("up_images")->where("id" , $id)->first();

        if (isset($res->id)){

            $str .= "<div class='text-center' style='padding:2px;margin-top:3px'>";   
            $str .= "<img src='http://localhost/global_images/{$res->file_name}' style='width:100%;max-height:150px'>"; 
            $str .= "<div wire:click='deletePhoto($row_id , \"pic\")' class='text-center' style='cursor:pointer'>Delete</div>"; 
            $str .= "</div>";
            $this->colArrPar[$row_id]['pic']= $res->description;
            unset($this->photos[$row_id]);

            return $str;
            
        }else if (isset($this->photos[$row_id]["pic"])){

            $str .= "<div class='text-center' style='padding:2px;margin-top:3px'>";  
            $str .= "<img src='{$this->photos[$row_id]["pic"]->temporaryUrl()}' style='width:100%;max-height:150px'>"; 
            $str .= "<div><input wire:model ='photosDesc.{$row_id}.pic'  class='form-control'  type='text'    ></div>";   
            $str .= "<div wire:click='savePhoto($row_id , \"pic\")' class='text-center' style='cursor:pointer'>Save</div>"; 
            return $str."</div>";
        }else{            
            return "<div>No Image Upload</div>";
        }   
  }

}
