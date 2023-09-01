<?php
namespace billing;
use loyalty\Loyalty;
use mechconfig\MechConfig;
use MongoDB\Driver\Exception\Exception;
use PDO;
use PDOException;
use anton;
use db_handeer\db_handler;
class Billing
{

    public $response = array('code'=>404,'status'=>null);

    function billNumber (): int
    {
        $machine_number = mech_no;
        $today = today;
        return (new \db_handeer\db_handler)->row_count('bill_header',"`mach_no` = '$machine_number' and `bill_date`
         = '$today'") + 1;
    }

    public function AddToBill($bill_number,$item,$qty,$myName,$tran_type = 'SS')
    {
        $today = today;
        $clerk_code = clerk_code;
        $billRef = billRef;
        //get item details
        $machine_number = mech_no;
        $clerk = $_SESSION['clerk_id'];


        // get item details
        $item_desc = $item['desc'];
        $item_retail = $item['retail'];
        $barcode = $item['barcode'];
        $item_code = $item['id'];
        $disc = $item['discount'];
//        echo $disc;


        if($item['discount'] == '1')
        {
            // calculate discount rate off

            //$retail_p = $item_retail;
            $discount_rate = $item['discount_rate'];
            $retail_p = $item_retail - (new \anton())->percentage($discount_rate,$item_retail);
        }
        else
        {
            $retail_p = $item_retail;
        }

        $bill_amt = $retail_p * $qty;
        $tax_group = $item['tax_grp'];


        // get tax rate
        $taxDetails = (new \db_handeer\db_handler())->get_rows('tax_master',"`id` = '$tax_group'");
        $rate = $taxDetails['rate'];
        $tax_description =$taxDetails['description'];
        $tax_code = $taxDetails['attr'];

        $tax = (new \anton())->tax($tax_code,$bill_amt);

        try {
            $tax_tran = $this->TaxTran("$bill_number","$machine_number","$barcode","$qty");

            if($tax_tran['code'] === 200)
            {
                $tax_detail = $tax_tran['message'];
                $taxAmount = $tax_detail['vat'];

                // add to bill in trans
                $sql = "insert into `bill_trans` 
                (`mach`,`clerk`,`bill_number`,`item_barcode`,
                 `item_desc`,`retail_price`,`item_qty`,`tax_amt`,
                 `bill_amt`,`trans_type`,`tax_grp`,`tax_rate`,date_added,billRef,tran_type) values
                 ('$machine_number','$myName','$bill_number','$barcode',
                  '$item_desc','$item_retail','$qty','$taxAmount',
                  '$bill_amt','i','$tax_description','$rate','$today','$billRef','$tran_type')";

                $file = $_SERVER['DOCUMENT_ROOT'] . "/log_file.log";
                $text = "$sql\n";
                file_put_contents($file, $text, FILE_APPEND);

                (new \db_handeer\db_handler())->db_connect()->prepare($sql);
                (new \db_handeer\db_handler())->db_connect()->exec($sql);

                $this->response['code'] = 200;
                $this->response['message'] = 'bill saved';

            } else {
                $this->response['code'] = $tax_tran['code'];
                $this->response['message'] = $tax_tran['message'];

            }



        } catch (\Exception $e)
        {
            $this->response['code'] = 505;
            $this->response['message'] = $e->getMessage();
        }



        return $this->response;

    }

    public function billTotal($bill_number,$date,$billRef = ''): array
    {
        $machine_number = mech_no;
        $response = [
            'bill_number'=>$bill_number,'valid'=>'N','tran_qty'=>0.00,'taxable_amt'=>0.00,'tax_amt'=>0.00,'bill_amt'=>0.00,'amt_paid'=>0.00,'amt_bal'=>0.00,
            'disc_valid'=>'N','disc_rate'=>0.00,'disc_value'=>0.00
        ];

        $tran_qty = $this->db_handler()->row_count('bill_trans',"`bill_number` = '$bill_number' and `date_added` = '$date' and mach = $machine_number");
        $disc_cond = "`bill_number` = '$bill_number' and `date_added` = '$date' and mach = $machine_number and `trans_type` = 'D'";
//        die($disc_cond);
        $disc_qty = $this->db_handler()->row_count('bill_trans',$disc_cond);
        $taxable_amt = $this->db_handler()->sum('bill_trans','retail_price',"`bill_number` = '$bill_number' and `date_added` = '$date' and mach = $machine_number and `trans_type` = 'i'");
        $tax_amt = $this->db_handler()->sum('bill_tax_tran','tax_amt',"`bill_no` = '$bill_number' and `bill_date` = '$date' and mech_no = $machine_number");
        $bill_amt = $this->db_handler()->sum('bill_trans','bill_amt',"`bill_number` = '$bill_number' and `date_added` = '$date' and mach = $machine_number and `trans_type` = 'i'");

        if($tran_qty > 0)
        {
            $response['valid'] = 'Y';
            $response['tran_qty'] = $tran_qty;
            // get sums
            $response['taxable_amt'] = $bill_amt - $tax_amt;
            $response['tax_amt'] = $tax_amt;
            $response['bill_amt'] = $bill_amt;

            if($disc_qty === 1){
                $discount = $this->db_handler()->fetch_rows("SELECT * FROM bill_trans where $disc_cond",'array');
                $response['disc_valid'] = 'Y';
                // $response['disc_rate'] = $discount['bill_amt'];
                // $response['disc_value'] = $this->anton()->percentage($response['disc_rate'],$response['taxable_amt']);
                // $response['taxable_amt'] -= $response['disc_value'];
                // $response['bill_amt'] = $response['taxable_amt'];
                // $tax_amt = $response['tax_amt'];
                // $response['tax_amt'] = $this->anton()->percentage($response['disc_rate'],$tax_amt);
            }

        }

        

        return $response;


    }

    public function billSummary($billRef): array
    {
        $bill_hd = array(
            'ref'=>$billRef,
            'valid'=>'Y',
            'total'=>0.00,
            'discount_type'=>0.00,
            'discount'=>0.00,
            'disc_rate'=>0.00,
            'bill_amt'=>0.00,
            'tax_amt'=>0.00,
            'paid_amt'=>0.00,
            'amt_bal'=>0.00,
            'tran_qty'=>0
        );
        $dbHandler = new db_handler();

        $live_count = $dbHandler->row_count('bill_trans', "`billRef` = '$billRef'");
        $hist_count = $dbHandler->row_count('bill_history_trans', "`billRef` = '$billRef'");


        if ($live_count > 0) {
            $bill_hd['tran_qty'] = $live_count;
            $bill_trans_table = 'bill_trans';
            $bill_header_table = 'bill_header';
            $bill_tax_table = 'bill_tax_tran';
        } elseif ($hist_count > 0) {
            $bill_trans_table = 'bill_history_trans';
            $bill_header_table = 'bill_history_header';
            $bill_tax_table = 'history_tax_tran';
            $bill_hd['tran_qty'] = $hist_count;
        } else {
            $bill_hd['valid'] = 'N';
            return $bill_hd;
        }

        // check if bill is paid for
        $paid_for = (new db_handler())->row_count("$bill_header_table","`billRef` = '$billRef'");
        if($paid_for === 1){
            // bill has been paid for so update header with this values
            $bill_header = (new db_handler())->get_rows("$bill_header_table","`billRef` = '$billRef'");
            $bill_hd['total'] = $bill_header['gross'];
            $bill_hd['discount'] = $bill_header['disc_amt'];
            $bill_hd['bill_amt'] = $bill_header['net_amt'];
            $bill_hd['tax_amt'] = $bill_header['tax_amt'];
            $bill_hd['paid_amt'] = $bill_header['amt_paid'];
            $bill_hd['total'] = $bill_header['amt_bal'];
        } else {
            // values should be updated with transactions
            $total = (new db_handler())->sum($bill_trans_table,'bill_amt',"`trans_type` = 'i' and `billRef` = '$billRef'");
            $bill_hd['total'] = number_format($total,2);
            $tax = (new db_handler())->sum($bill_tax_table,'tax_amt',"`billRef` = '$billRef'");
            if((new db_handler())->row_count($bill_trans_table,"`billRef` = '$billRef' and `trans_type` = 'D'") === 1)
            {
                // there is discount
                $dic_rate = (new db_handler())->get_rows($bill_trans_table,"`billRef` = '$billRef' and `trans_type` = 'D'")['bill_amt'];
                $dis_value = $dic_rate / 100;
                $disc = $total * $dis_value;
                $disc_type = "D";
                // disc on tax
                $tax_disc = $tax * $dis_value;


            }
           elseif ((new db_handler())->row_count($bill_trans_table,"`billRef` = '$billRef' and `trans_type` = 'L'") === 1) {
               $disc = (new db_handler())->get_rows($bill_trans_table,"`billRef` = '$billRef' and `trans_type` = 'L'")['bill_amt'];
               $tax_disc = 0;
               $dic_rate = 0.00;
               $disc_type = 'L';
           }
            else {
                $disc = 0;
                $tax_disc = 0;
                $dic_rate = 0.00;
                $disc_type = '';
            }
            $bill_hd['disc_rate'] = $dic_rate;
            $bill_hd['discount'] = number_format($disc,2);
            if($disc_type === 'L'){
                $bill_hd['bill_amt'] = $total + $disc;
            }
            else {
                $bill_hd['bill_amt'] = $total - $disc;
            }

            $bill_hd['discount_type'] = $disc_type;


            $tax = (new db_handler())->sum($bill_tax_table,'tax_amt',"`billRef` = '$billRef'");
            $bill_hd['tax_amt'] = number_format($tax - $tax_disc,2);

        }

        return $bill_hd;

    }

    public function makePyament($method,$amount_paid): array
    {


        $myName = $_SESSION['clerk_id'];
        $today = today;
        $response = ['status'=>505,'message'=>'initialization'];
        $billRef = billRef;
        // get current bill details
        $bill_number = bill_no;
        $machine_number = (new MechConfig())->mech_details()['mechine_number'];
        $bill_tran_cond = "`bill_date` = '$today' and `mech_no` = '$machine_number' and `bill_number` = '$bill_number'";
        $bill_hd_cond = "`bill_date` = '$today' and `mach_no` = '$machine_number' and `bill_no` = '$bill_number'";
        $bill_trans_count = (new db_handler())->row_count('bill_trans',"`date_added` = '$today' and `mach` = '$machine_number' and `bill_number` = '$bill_number' and `trans_type` = 'i'");



        if($bill_trans_count > 0)
        {



            // get transaction details
            $bill_totals = $this->billTotal($bill_number,$today);
            $bill_totals = $this->billSummary($billRef);
            if($bill_totals['valid'] === 'Y')
            {
                $net = $bill_totals['bill_amt'];
                $tax_amt = $bill_totals['tax_amt'];
                $gross_amt = $bill_totals['total'];
                $tran_qty = $bill_totals['tran_qty'];
                $discount = $bill_totals['discount'];
                $disc_rate = $bill_totals['disc_rate'];

                if($method === 'refund'){
                    $amount_paid = $gross_amt;
                    $amt_balance = 0.00;
                } else {
                    $amt_balance = $amount_paid - $gross_amt;
                }

                $bill_totals['paid_amt'] = number_format($amount_paid,2);
                $bill_totals['tran_qty'] = number_format($amt_balance,2);

                #1 make bill tran payment.
                #2 make bill hd payment,
                #3 return bill details
                $bill_header_insert = "INSERT INTO bill_header (mach_no, clerk, bill_no, pmt_type, gross_amt, tax_amt, net_amt,tran_qty,amt_paid,amt_bal,bill_date,billRef,disc_rate,disc_amt)
                    VALUES ($machine_number, '$myName', $bill_number, '$method', $gross_amt, $tax_amt, $net, $tran_qty,$amount_paid,$amt_balance,'$today','$billRef','$disc_rate','$discount');";
                (new anton())->log2file("COPPER");
                (new anton())->log2file($bill_header_insert);
                (new anton())->log2file("COPPER");
                if($this->db_handler()->row_count('bill_header',$bill_hd_cond) == 0)
                {
                    // make bill
                    $this->anton()->log2file("###################");
                    (new anton())->log2file($bill_header_insert);
                    $this->anton()->log2file("###################");

                    # make EvatData
                    $send_inv = json_decode((new Evat())->send_invoice(billRef));
                    (new anton())->log2file("SEND INVOICE RESPONSE \n".var_export($send_inv,true));

                    if($send_inv->code === 200 || $send_inv->message === 'INVOICE ALREADY SUBMITTED'){
                        // get signature
                        $signature = json_decode((new Evat())->sign_invoice(billRef));

                        (new anton())->log2file("SIGNATURE RESPONSE \n".var_export($signature,true));
                        $sign = $signature->MESSAGE;
                        (new anton())->log2file("SIGN \n$signature->STATUS");

                        if($signature->STATUS === 'SUCCESS'){

                            // save signatures\
                            $ysdcrecnum = $sign->ysdcrecnum;
                            $ysdcid = $sign->ysdcid;
                            $ysdcintdata = $sign->ysdcintdata;
                            $ysdcmrc = $sign->ysdcmrc;
                            $ysdcitems = $sign->ysdcitems;
                            $ysdcmrctim = $sign->ysdcmrctim;
                            $ysdcregsig = $sign->ysdcregsig;
                            $ysdctime = $sign->ysdctime;
                            $qr_code = $signature->QR_CODE;

                            // save keys in database
                            $evat_tran = "INSERT into evat_transactions (billRef, ysdcid, ysdcitems, ysdcmrc, ysdcmrctim, ysdcrecnum, ysdctime, ysdcintdata, ysdcregsig, qr_code) VALUES 
                                                                        ('$billRef','$ysdcid','$ysdcitems','$ysdcmrc','$ysdcmrctim','$ysdcrecnum','$ysdctime','$ysdcintdata','$ysdcregsig','$qr_code')";

                            (new db_handler())->exe($evat_tran);

                            // continue
                            $this->db_handler()->db_connect()->exec($bill_header_insert);

                            if($method === 'refund') // update on refunds
                            {
                                // update values all to negative
                                $header = "UPDATE bill_header SET gross_amt = gross_amt - (gross_amt * 2),
                       tax_amt = tax_amt - (tax_amt * 2),net_amt = net_amt - (net_amt * 2),
                       tran_qty = tran_qty - (tran_qty * 2), amt_paid = amt_paid - (amt_paid * 2) 
                       where mach_no = $machine_number and bill_no = $bill_number and bill_date = '$today'";

                                // bill tran
                                $trans = "UPDATE bill_trans SET item_qty = item_qty - (item_qty * 2),tax_amt = tax_amt - (tax_amt * 2),
                      bill_amt = bill_amt - (bill_amt * 2) where mach = $machine_number and bill_number = $bill_number and date_added = '$today'";

                                // tax trans
                                $tax_tran = "UPDATE bill_tax_tran SET tran_qty = tran_qty - (tran_qty * 2),
                         taxableAmt = taxableAmt - (taxableAmt * 2), 
                         tax_amt = tax_amt - (tax_amt * 2) 
                         where bill_date = '$today' and mech_no = $machine_number and bill_no = $bill_number";


                            }

                            // print bill
                            printbill(mech_no,$bill_number,$method);

                            $response['status'] = 200;
                            $response['message'] = $bill_totals;



                        }
                        else {

                            $response['status'] = 505;
                            $response['message'] = $signature;
                        }

                    } else {
                        $response['status'] = 505;
                        $response['message'] = $send_inv;
                    }





                    $loyCount = (new db_handler())->row_count('loyalty_tran',"`billRef` = '$billRef'");
                    if($loyCount === 1){

                        // loyalty points insert $gross_amt
                        $customer_details = (new db_handler())->get_rows('loyalty_tran',"`billRef` = '$billRef'");
                        $cust_code = $customer_details['cust_code'];

                        (new Loyalty())->givePoints($cust_code,billRef,$net);

                    }

                }
                else {
                    $response['status'] = 404;
                    $response['message'] = "THERE IS BILL WITH HEADER";
                }


            }



        } else
        {
            $response['status'] = 404;
            $response['message'] = 'Cannot make payment for an empty transaction';
        }
        (new anton())->log2file(var_export($response,2));

        return $response;


    }

    private function db_handler(): db_handler
    {
        return (new db_handler());
    }

    private function anton(): anton
    {
        return (new anton());
    }

    function TaxTran($bill_no,$mech_no,$item_code,$qty){
        $date = today;
        $clerk = clerk_code;
        $billRef = billRef;
        // validate machine, item_code
        $itemCount = $this->db_handler()->row_count('prod_mast',"`barcode` = '$item_code'");
        $mechValid = $this->db_handler()->row_count("mech_setup","`mech_no` = '$mech_no'");

        if($itemCount < 1 || $itemCount > 1)
        {
            $this->response['message'] = " Item Not Found";
        }
//        elseif ($mechValid === '1')
//        {
//            $this->response['message'] = " Machine Number $mechValid";
//        }
        else {

            try {

                // there is item and also bill
                $item = $this->db_handler()->get_rows('prod_mast',"`barcode` = '$item_code'");
                $tax_code = $item['tax_grp'];
                $i_code = $item['id'];

                // get tax details
                $taxCount = $this->db_handler()->row_count('tax_master',"`id` = '$tax_code'");

                if($taxCount === 1)
                {

                    try {

                        // there is tax code
                        $taxDetail = $this->db_handler()->get_rows('tax_master',"`id` = '$tax_code'");
                        $tax_code = $taxDetail['attr'];
                        $retail = $item['retail'];
                        $cost = $retail * $qty;

                        if($tax_code === 'VM')
                        {
                            $nhil = (2.5 / 100) * $cost;
                            $gfund = (2.5 / 100 ) * $cost;
                            $covid  = (1 / 100 ) * $cost;

                            $vat_calc = $cost * 21.90;
                            $vat = $vat_calc / 121.9;

                            // insert into tax transactions
                            try {

                                $tax_ins_query = "INSERT INTO posdb.bill_tax_tran (bill_date, clerk_code, mech_no, bill_no, tran_code, tran_qty, taxableAmt, tax_code,
                                 tax_amt,billRef) VALUES ('$date', '$clerk', $mech_no, $bill_no, $i_code, $qty, $retail, 'nh', $nhil,'$billRef'),
                                                 ('$date', '$clerk', $mech_no, $bill_no, $i_code, $qty, $retail, 'gf', $gfund,'$billRef'),
                                                 ('$date', '$clerk', $mech_no, $bill_no, $i_code, $qty, $retail, 'cv', $covid,'$billRef'),
                                                 ('$date', '$clerk', $mech_no, $bill_no, $i_code, $qty, $retail, 'VM', $vat,'$billRef');";


                                (new anton())->log2file($tax_ins_query,"TAX INSERTIONS");

                                $this->db_handler()->db_connect()->exec($tax_ins_query);
                                $this->response['code'] = 200;
                                $tax_detail = array('code'=>$tax_code,'vat'=>$vat);
                                $this->response['message'] = $tax_detail;

                            } catch (PDOException $e)
                            {
                                $this->response['code'] = 505;
                                $this->response['message'] .= " ".$e->getMessage() . " " . $e->getLine();
                            }

                        }
                        else {
                            // this is flat rate
                            $tax_rate = $taxDetail['rate'];
                            $vat = ($tax_rate / 100 ) * $retail;

                            try {

                                $tax_ins_query = "INSERT INTO posdb.bill_tax_tran (bill_date, clerk_code, mech_no, bill_no, tran_code, tran_qty, taxableAmt, tax_code,
                                 tax_amt,billRef) VALUES ('$date', '$clerk', $mech_no, $bill_no, $i_code, $qty, $retail, '$tax_code', $vat,'$billRef');";
                                (new anton())->log2file($tax_ins_query);

                                $this->db_handler()->db_connect()->exec($tax_ins_query);
                                $this->response['code'] = 200;
                                $tax_detail = array('code'=>$tax_code,'vat'=>$vat);
                                $this->response['message'] = $tax_detail;

                            } catch (PDOException $e)
                            {
                                $this->response['code'] = 505;
                                $this->response['message'] .= " ".$e->getMessage();
                            }

                        }

                    } catch (Exception $e)
                    {

                        $this->response['code'] = 505;
                        $this->response .= " " . $e->getMessage();

                    }



                } else {
                    $this->response['message'] .= " invalid Tax Code";
                }

            } catch (\Exception $e)
            {
                $this->response['code'] = 505;
                $this->response['message'] .= " ".$e->getMessage();
            }



        }

        return $this->response;


    }

    function MechSalesSammry($mech_no = 0): array
    {
        $gross = (new db_handler())->sum('bill_trans','bill_amt',"`mach`= '$mech_no' and `tran_type` in ('SS')");

        $deduct = 0;
        if((new db_handler())->row_count('bill_trans',"`mach`= '$mech_no' and `tran_type` in ('RF')") > 0){
            $deduct = (new db_handler())->sum('bill_trans','bill_amt',"`mach`= '$mech_no' and `tran_type` in ('RF')");
        }


        $net = $gross - abs($deduct);

        $tax = (new db_handler())->sum('bill_tax_tran','tax_amt',"`mech_no`= '$mech_no'");

        return array(
            'gross'=>$gross,'deduct'=>$deduct,'net'=>$net,'tax'=>$tax
        );

    }

    function billSummaryV2($bill_ref = billRef,$mach_no = mech_no): array
    {

        // Initialize variables
        $tax_rate_levies = 0.06; // 6%
        $tax_rate_vat = 0.159; // 15.9%
        $discount_rate = 0.03; // 3%
        $taxable_total = 0;
        $none_taxable_total = 0;
        $bill_trans = array();
        // Calculate taxable total and none-taxable total
        $bills = $this->db_handler()->db_connect()->query("SELECT * FROM bill_trans where billRef = '$bill_ref' and trans_type = 'i'");
        while ($item = $bills->fetch(PDO::FETCH_ASSOC)) {
            $barcode = $item['item_barcode'];
            $retail_price = $item['retail_price'];
            $item_qty = $item['item_qty'];
            $bill_amt = $item['bill_amt'];
            $tax_grp = $item['tax_grp'];

            $this_item = array(
                "name"=>"NAME","ref"=>$barcode,'qty'=>$item_qty,"retail_price"=>$retail_price,"total"=>$bill_amt,'tax_type'=>$tax_grp
            );
            $bill_trans[] = $this_item;
//            array_push($bill_trans,array($this_item));


            // get item details
            $product = new ProductMaster($barcode);

            if ($product->isTaxable()) {
                $taxable_total += $product->getPrice()['taxableAmt'];
            } else {
                $none_taxable_total += $product->getPrice()['taxableAmt'];
            }
        }

        // Apply discount to the total bill
        $total_bill = ($taxable_total + $none_taxable_total) * (1 - $discount_rate);

        // Calculate levies amount
        $levies_amount = $taxable_total * $tax_rate_levies;

        // Calculate VAT amount
        $vat_amount = ($taxable_total + $levies_amount) * $tax_rate_vat;

        // Calculate final bill amount
        $final_bill = $total_bill + $levies_amount + $vat_amount;

        $bill_header = array();
        if((new db_handler()) -> row_count('bill_header',"`billRef` = '$bill_ref'")){
            $hd = (new db_handler()) -> get_rows('bill_header',"`billRef` = '$bill_ref'");
            $bill_header['INVOICE_DATE'] = $hd['bill_date'];
            $bill_header['TOTAL_AMOUNT'] = $final_bill;
            $bill_header['TOTAL_LEVY'] = $levies_amount;
            $bill_header['TOTAL_VAT'] = $vat_amount;
            $bill_header['ITEMS_COUNTS'] = (new db_handler()) -> row_count('bill_trans',"billRef = '$bill_ref' and trans_type = 'i'");
        }

        // Prepare the response array
        $response = array(
            'bill_header'=>$bill_header,
            'bill_number' => $bill_ref,
            'mach_no' => $mach_no,
            'taxable_total' => $taxable_total,
            'none_taxable_total' => $none_taxable_total,
            'total_bill' => $total_bill,
            'levies_amount' => $levies_amount,
            'vat_amount' => $vat_amount,
            'final_bill' => $final_bill,
            'bill_trans'=>$bill_trans
        );

        // Return the response
        return $response;

    }

}