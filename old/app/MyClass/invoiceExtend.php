<?php
namespace App\MyClass;

trait invoiceExtend
{

    public $msgs = [];
    public $submit_visible;
    public $baseRef;
    public $targetRefCol;
    public $autoArr;

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

    public function getActionButtons($formType, $row_id, $visibilty = "visible")
    {

        if ($visibilty !== "submit_hidden") {

            $actionHtml = "<div class='col-12 pt-3'>";
            $actionHtml .= "<input type='button' wire:click='createNewFormRow({$row_id})' class='btn btn-success' value='insert New Row' > ";

            if ($formType == 0) {
                $actionHtml .= "<input type='button' wire:click='deleteRow({$row_id})' class='btn btn-warning' value='Delete Row ({$row_id})' > ";
            } else {
                $actionHtml .= "<input type='button' wire:click='updateRow' class='btn btn-success'  value='Update Row'> ";
            }
            return $actionHtml . "</div>";

        } else {
            return null;
        }
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

    // Hook End of Form
    public function excuteFunctionEndOfForm($row_id, $action)
    {

        if ($action == "setTrialBalnceWidget") {

            $str = "<div class='col-12'   style='height:350px;overflow-y:auto;border:2px solid #ddd;background-color:white'>
                      <table class='table table-bordered'>
                      <tr>
                      <td class='text-center' colspan='10' >حساب {$this->colArrPar[$row_id]['account_id']}  </td>
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
                                      <td>" . implode("<br>", $effctAcountArr) . "<span class='disc'>{$Bileheader->decription}</span></td>
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
                                            <td>" . implode("<br>", $effctAcountArr) . "<span class='disc'>{$Bileheader->decription}</span></td>
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
            return $str;
        }

        if ($action == "setButtonTrialBalance") {

            $str = "<div class='col-12'   style='padding:10px;overflow-y:auto;border:2px solid #ddd;background-color:white'>
                           <div class='row' >
                                <div class='col-1 p-1' >
                                <input type='button' class='btn btn-info'  value='انشاء دفتر استاذ'  >
                                </div>
                                <div class='col-1 p-1' >
                                <input type='button' class='btn btn-info'  value='حذف دفتر الاستاذ'  >
                                </div>
                           </div>
                     </div>";
            return $str;
        }

    }

}
