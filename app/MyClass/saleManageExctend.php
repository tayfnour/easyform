<?php
namespace App\MyClass;

use DB;

trait saleManageExctend
{
    public function getRandomStr($len)
    {
        return bin2hex(random_bytes($len));
    }

    // عملية السداد
    public function receptsOperation($row_id)
    {
        $opts = $this->formOpts[$this->rowOpts[$row_id][0]];

        $pk = $opts["pk"];

        $id = $this->colArr[$row_id][$pk];
        $recipt = $this->colArr[$row_id]["payValue"];
        $acc_id = $this->colArr[$row_id]["account_id"];
        $ref = $this->colArr[$row_id]["bill_ref"];
        $customer_acc_id = $this->refs["invoice_headers"]["supplier"];
        

        // dd($id ,  $recipt ,$customer_acc_id)

        // تسجيل دفعة للزبون في حسابه
        DB::table('entries')
            ->updateOrInsert(

                ["entry_no" => $ref, "bill_num" => "RSP_" . $id, "entry_type" => "receipts"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "RSP_" . $id,
                    "debtor" => 0,
                    "creditor" => $recipt,
                    "acc_name" => $customer_acc_id,
                    "discription" => "Receipts Automatic Entry",
                    "entry_type" => "receipts",
                ]
            );

        // تسجيل لاين ذهبت الدفعة
        DB::table('entries')
            ->updateOrInsert(

                ["entry_no" => $ref, "bill_num" => "INCOME_" . $id, "entry_type" => "income"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "INCOME_" . $id,
                    "debtor" => $recipt,
                    "creditor" => 0,
                    "acc_name" => $acc_id,
                    "discription" => "Receipts Automatic Entry",
                    "entry_type" => "income",
                ]
            );

        $this->formsLoop();

    }

    public function calculateProductCount($product_id)
    {
        $res_purch = DB::table("bills")->where("product_id", $product_id)->sum("quantity");
        $res_sales = DB::table("invoices")->where("product_id", $product_id)->sum("quantity");

        return $res_purch - $res_sales;
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

    // sort feild of form
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

    public function moveBillItemToEntries($row_id, $col)
    {

        $this->calculateOtherField($row_id, $col);

        $total = $this->colArr[$row_id]["total"];
        $preTotal = $this->colArr[$row_id]["preTotal"];
        $vatVal = $this->colArr[$row_id]["vatVal"];
        $id = $this->colArr[$row_id]["invoice_id"];
        $ref = $this->colArr[$row_id]["ref"];
        $quantity = $this->colArr[$row_id]["quantity"];

        $product_id = $this->colArr[$row_id]["product_id"];

        $product = DB::table("simpleproducts")->where("id", $product_id)->first();

        $p_price = $product->buyprice;

        $saleCost = floatval($p_price) * floatval($quantity);

        // dd($this->refs["invoice_headers"]["supplier"]);

        //  dd($saleCost);
        // تسجيل في المخزون
        DB::table('entries')
            ->updateOrInsert(

                ["entry_no" => $ref, "bill_num" => "INV_" . $id, "entry_type" => "inventory"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "INV_" . $id,
                    "debtor" => 0,
                    "creditor" => $saleCost,
                    "acc_name" => 48,
                    "discription" => "Automatic Entry",
                    "entry_type" => "inventory",
                ]
            );

        // تكلفة البضائع
        DB::table('entries')
            ->updateOrInsert(
                ["entry_no" => $ref, "bill_num" => "INV_" . $id, "entry_type" => "cost"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "INV_" . $id,
                    "debtor" => $saleCost,
                    "creditor" => 0,
                    "acc_name" => 28,
                    "discription" => "Automatic Entry",
                    "entry_type" => "cost",
                ]
            );

        // تسجيل في المبيعات
        DB::table('entries')
            ->updateOrInsert(
                ["entry_no" => $ref, "bill_num" => "INV_" . $id, "entry_type" => "sales"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "INV_" . $id,
                    "debtor" => 0,
                    "creditor" => $preTotal,
                    "acc_name" => 26,
                    "discription" => "Automatic Entry",
                    "entry_type" => "sales",
                ]
            );
        // تسجيل في القيمة المضافة
        DB::table('entries')
            ->updateOrInsert(

                ["entry_no" => $ref, "bill_num" => "Vat_" . $id, "entry_type" => "vat"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "Vat_" . $id,
                    "debtor" => 0,
                    "creditor" => $vatVal,
                    "acc_name" => 24,
                    "discription" => "Automatic Entry",
                    "entry_type" => "vat",
                ]
            );

        // تسجيل الدفعة على حساب  العميل
        // ممكن ان يكون عميل نقدي
        DB::table('entries')
            ->updateOrInsert(

                ["entry_no" => $ref, "bill_num" => "INV_" . $id, "entry_type" => "customer"]
                ,
                [
                    "entry_no" => $ref,
                    "bill_num" => "INV_" . $id,
                    "debtor" => $total,
                    "creditor" => 0,
                    "acc_name" => $this->refs["invoice_headers"]["supplier"],
                    "discription" => "Automatic Entry",
                    "entry_type" => "customer",
                ]
            );

        $this->msgs[] = $preTotal . "/" . $vatVal . "Add invoice to entries";

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

    public function createNewCustomer($row_id)
    {

        $id = $this->createNewFormRow(null, "customers");
        $this->colArr[$row_id]["supplier"] = $id;
        $this->saveRowOnUpdate($row_id, "supplier");
        $this->formsLoop();

    }
    //--globlal function to here;

    public function excuteFunctionEndOfForm($row_id, $action)
    {

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

        if ($action == "AddNewCutomerButton") {

            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white;z-index:1'>
                            <div class='row' >
                                <div class='col-6' >
                                <input wire:click='createNewFormRow({$row_id},null)' type='button' class='btn btn-info wd100'  value='تعديل معلومات عميل'  >
                                </div>
                                <div class='col-6' >
                                <input  wire:click='delRow({$row_id})' type='button' class='btn btn-info wd100'  value='رصيد حساب العميل'  >
                                </div>
                            </div>
                    </div>";
            return $str;
        }

        if ($action == "AddInvoieHeaderButtons") {

            $str = "<div class='col-12 p-2'   style='border:1px solid #ddd;background-color:white;z-index:1'>
                            <div class='row' >
                                <div class='col-6' >
                                <input wire:click='createNewCustomer({$row_id})' type='button' class='btn btn-info wd100'  value='اضافة عميل جديد'  >
                                </div>
                                <div class='col-6' >
                                <input  wire:click='createNewFormRow({$row_id})' type='button' class='btn btn-info wd100'  value='إضافة فاتورة مشتريات'  >
                                </div>
                            </div>
                    </div>";
            return $str;
        }

        if ($action == "setTotalWidget") {

            $preTotal = DB::table("invoices")->where("ref", $this->colArr[$row_id]["ref"])->sum("preTotal");
            $preTotal = round($preTotal, 2);
            $vatVal = DB::table("invoices")->where("ref", $this->colArr[$row_id]["ref"])->sum("vatVal");
            $vatVal = round($vatVal, 2);
            $sums = round($preTotal + $vatVal, 2);

            $pays = DB::table("entries")->where("entry_no", $this->colArr[$row_id]["ref"])->sum("creditor");
            $amountDue = $sums - $pays;

            return $str = "<div  class='col-12 mt-1' style='padding:10px;height:240px;border:2px solid #ddd;background-color:white'>
                            <div class='row' >
                                <div class='col-2' >
                                  <input  wire:click='createNewFormRow({$row_id})' type='button' class='btn btn-info wd100'  value='إضافة منتج الى الفاتورة'  >
                                </div>
                            </div>
                             <div class='row pb-2'>
                                 <div class='col-3'></div>
                                 <div class='col-3'></div>
                                 <div class='col-3'>
                                 <label class='t-1 text-start'>الاجمالي قبل الضريبة :</label>
                                 </div>
                                 <div class='col-3'>
                                 <input class='form-control billResume' type='text' value='{$preTotal}' id=''>
                                 </div>
                             </div>
                             <div class='row pb-2'>
                                 <div class='col-3'></div>
                                 <div class='col-3'></div>
                                 <div class='col-3'>
                                     <label class='t-1 text-start'>قيمة الضريبة :</label>
                                 </div>
                                 <div class='col-3'>
                                     <input class='form-control billResume' type='text' value='{$vatVal}' id=''>
                                 </div>
                             </div>
                             <div class='row pb-2'>
                                 <div class='col-3'></div>
                                 <div class='col-3'></div>
                                 <div class='col-3'>
                                 <label class='t-1 text-start'>المجموع :	</label>
                                 </div>
                                 <div class='col-3'>
                                 <input class='form-control billResume' type='text' value='{$sums}' id=''>
                                 </div>
                             </div>
                             <div class='row pb-2'>
                                 <div class='col-3'></div>
                                 <div class='col-3'></div>
                                 <div class='col-3'>
                                     <label class='t-1 text-start'>المبلغ المستحق :</label>
                                 </div>
                                 <div class='col-3'>
                                     <input class='form-control billResume' type='text' value='{$amountDue}' id=''>
                                 </div>
                             </div>
                             </div>";

        }

        if ($action == "setEntryBalaneced") {

            $ref = $this->refs["invoice_headers"]["ref"];

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

    }

}
