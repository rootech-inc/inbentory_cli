<?php
namespace billing;
use loyalty\Loyalty;
use mechconfig\MechConfig;
use MongoDB\Driver\Exception\Exception;
use PDO;
use PDOException;
use anton;
use db_handeer\db_handler;
use billing\shift;

class Billing extends db_handler
{

    public $response = array('code'=>404,'status'=>null);

    function billNumber (): int
    {
        $shift_no = shift_no;
        $machine_number = mech_no;
        $today = today;
        return (new \db_handeer\db_handler)->row_count('bill_header',"`mach_no` = '$machine_number' and `bill_date`
         = '$today' and `shift` = '$shift_no'") + 1;
    }

    public function AddToBill($bill_data)
    {
        $today = today;
        $billRef = $this->getRef();
        //get item details
        $machine_number = mech_no;
        $shift_no = shift_no;
        $myName = clerk_code;

        $bill_number = $this->billNumber();

        $item = $bill_data['item'];
        // get item details
        $item_desc = $item['desc'];
        $item_retail = $item['retail'];
        $barcode = $item['barcode'];
        $billing_type = $bill_data['billing_type'];
        $qty = $bill_data['qty'];
        $qt = $bill_data['qty'];
        $refund_ref = $bill_data['refund_ref'];
        $ref_trans = $bill_data['ref_trans'];
        //echo $ref_trans; =
        $tran_type = 'SS';
        if($billing_type === 'refund'){
            $qty = abs($bill_data['qty']);
            $tran_type = 'RR';
            // validate product

            if((new db_handler())->row_count("$ref_trans","`item_barcode` = '$barcode' AND `billRef` = '$refund_ref' AND `item_qty` <= '$qt'") < 1){
                $this->response['code'] = 404;
                $this->response['message'] = 'Item Does Not exist in bill';

                echo json_encode($this->response);
                exit();
            }

        }

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



        try {
            $tax_tran = $this->TaxTran("$bill_number","$machine_number","$barcode","$qty");
            $tax = $tax_tran['message'];

            if(true)
            {
                $taxAmount = $item['tax_amt'];
                $gf = $tax['gf'];
                $nh = $tax['nh'];
                $cv = $tax['cv'];
                $vat = $tax['vat'];

                $tax_description = $item['tax_grp'];
                $rate = 0;
                $tax_code = $tax_description;

                // add to bill in trans
                $sql = "insert into `bill_trans` 
                (`mach`,`clerk`,`bill_number`,`item_barcode`,
                 `item_desc`,`retail_price`,`item_qty`,`tax_amt`,
                 `bill_amt`,`trans_type`,`tax_grp`,`tax_rate`,date_added,billRef,tran_type,covid,gfund,nhis,vat,tax_code,shift) values
                 ('$machine_number','$myName','$bill_number','$barcode',
                  '$item_desc','$item_retail','$qty','$taxAmount',
                  '$bill_amt','i','$tax_description','$rate','$today','$billRef','$tran_type','$cv','$gf','$nh','$vat','$tax_code','$shift_no')";



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

    public function getRef(): string
    {

        $bill_no = $this->billNumber();
        return LOC_ID.date('ymd',strtotime(today)).$bill_no.shift_no.mech_no;
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
        $paid_for = $this->row_count("$bill_header_table","`billRef` = '$billRef'");
        if($paid_for === 1){
            // bill has been paid for so update header with this values
            $bill_header = $this->get_rows("$bill_header_table","`billRef` = '$billRef'");
            $bill_hd['total'] = $bill_header['gross'];
            $bill_hd['discount'] = $bill_header['disc_amt'];
            $bill_hd['bill_amt'] = $bill_header['net_amt'];
            $bill_hd['tax_amt'] = $bill_header['tax_amt'];
            $bill_hd['paid_amt'] = $bill_header['amt_paid'];
            $bill_hd['total'] = $bill_header['amt_bal'];
        } else {
            // values should be updated with transactions
            $total = $this->sum($bill_trans_table,'bill_amt',"`trans_type` = 'i' and `billRef` = '$billRef'");
            $bill_hd['total'] = number_format($total,2);
            $tax = $this->sum($bill_tax_table,'tax_amt',"`billRef` = '$billRef'");
            if($this->row_count($bill_trans_table,"`billRef` = '$billRef' and `trans_type` = 'D'") === 1)
            {
                // there is discount
                $dic_rate = $this->get_rows($bill_trans_table,"`billRef` = '$billRef' and `trans_type` = 'D'")['bill_amt'];
                $dis_value = $dic_rate / 100;
                $disc = $total * $dis_value;
                $disc_type = "D";
                // disc on tax
                $tax_disc = $tax * $dis_value;


            }
           elseif ($this->row_count($bill_trans_table,"`billRef` = '$billRef' and `trans_type` = 'L'") === 1) {
               $disc = $this->get_rows($bill_trans_table,"`billRef` = '$billRef' and `trans_type` = 'L'")['bill_amt'];
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


            $tax = $this->sum($bill_tax_table,'tax_amt',"`billRef` = '$billRef'");
            $bill_hd['tax_amt'] = number_format($tax - $tax_disc,2);

        }

        return $bill_hd;

    }

    public function makePyament($method,$amount_paid,$oriRef = '',$billing_type='sales',$customer=''): array
    {

        $dbHandler = (new db_handler());

        $myName = $_SESSION['clerk_id'];
        $today = today;
        $response = [];
        $billRef = $this->getRef();
        // get current bill details
        $bill_cond = "`billRef` = '$billRef'  and `trans_type` = 'i'";
        $bill_number = $this->billNumber();
        $machine_number = (new MechConfig())->mech_details()['mechine_number'];
        $bill_tran_cond = "`bill_date` = '$today' and `mech_no` = '$machine_number' and `bill_number` = '$bill_number'";
        $bill_hd_cond = "`bill_date` = '$today' and `mach_no` = '$machine_number' and `bill_no` = '$bill_number'";
        $bill_trans_count = $this->row_count('bill_trans',$bill_cond);



        if($bill_trans_count > 0)
        {



            // get transaction details
            $bill_totals = $this->billTotal($bill_number,$today);
            $bill_totals = $this->billSummary($billRef);
            $totals2 = $this->billSummaryV2($billRef);
            $header2 = $totals2['bill_header'];


            $msg = "";
            $code = "";
            if($bill_totals['valid'] === 'Y')
            {


                $net = $header2['BILL_AMT'];
                $tax_amt = $header2['TOTAL_VAT'];
                $gross_amt = $header2['TOTAL_AMOUNT'];
                $tran_qty = $header2['ITEMS_COUNTS'];
                $discount = $header2['DISCOUNT'];
                $disc_rate = $header2['DISCOUNT_RATE'];

                $flag = "INVOICE";
                if($method === 'refund'){
                    $amount_paid = $gross_amt;
                    $amt_balance = 0.00;
                    $flag = "REFUND";
                } else {
                    $amt_balance = $amount_paid - $gross_amt;
                }

                $levies = $header2['TOTAL_LEVY'];
                $vat = $header2['TOTAL_VAT'];

                $tax_amt = $levies + $vat;

                $taxable_amount = $header2['TAXABLE_AMOUNT'];
                $non_taxable_amount = $header2['NON_TAXABLE_AMOUNT'];
                #1 make bill tran payment.
                #2 make bill hd payment,
                #3 return bill details
                $shift_no = shift_no;
                $bill_header_insert = "INSERT INTO bill_header (mach_no, clerk, bill_no, pmt_type, gross_amt, tax_amt, net_amt,tran_qty,amt_paid,amt_bal,bill_date,billRef,disc_rate,disc_amt,taxable_amt,non_taxable_amt,shift,sales_type,customer)
                    VALUES ($machine_number, '$myName', $bill_number, '$method', $gross_amt, $tax_amt, $net, $tran_qty,$amount_paid,$amt_balance,'$today','$billRef','$disc_rate','$discount','$taxable_amount','$non_taxable_amount','$shift_no','$billing_type','$customer');";

                //if($this->db_handler()->row_count('bill_header',$bill_hd_cond) == 0)
                if(true)
                {


                    $billComplete = false;
                    $billError = "There is an error completing bill";
                    if(evat){

                        # make EvatData
                        $send_inv = json_decode((new Evat())->send_invoice($this->getRef(),$flag,$oriRef));

                        if($send_inv->code === 202 || $send_inv->message === 'INVOICE ALREADY SUBMITTED')
                        {
                            // get signature
                            $signature = json_decode((new Evat())->sign_invoice($this->getRef(),$flag,$oriRef));

                            $sign = $signature->MESSAGE;

                            if($signature->STATUS === 'SUCCESS'){
                                $response['status'] = 200;
                                $response['message'] = $sign;
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
                                $this->db_handler()->exe($evat_tran);

                                $msg = "BILL DONE";
                                $code = 200;


                                $billComplete = true;
                            }

                            else {

                                $message = $signature->MESSAGE;
                                $msg = $message->ysdcregsig;
                                $code = 505;
                                $billError = $msg;



                            }

                        }
                        else {
                            $code = 505;
                            $msg = $send_inv->message;
                            $billComplete = false;
                        }

                    }
                    else{
                        $billComplete = true;
                    }

                    
                    

                    if($billComplete === true){
                        
                        // continue
                        $this->db_handler()->db_connect()->exec($bill_header_insert);



                        if($billing_type === 'refund') // update on refunds
                        {

                            // update values all to negative
                            $header = "UPDATE bill_header SET gross_amt = gross_amt - (gross_amt * 2),
                       tax_amt = tax_amt - (tax_amt * 2),net_amt = net_amt - (net_amt * 2),
                       tran_qty = tran_qty - (tran_qty * 2), amt_paid = amt_paid - (amt_paid * 2),old_bill_ref='$oriRef' ,
                       taxable_amt = taxable_amt - (taxable_amt * 2),non_taxable_amt = non_taxable_amt - (non_taxable_amt * 2)
                       where billRef = '$billRef'";

                            // bill tran
                            $trans = "UPDATE bill_trans SET item_qty = item_qty - (item_qty * 2),tax_amt = tax_amt - (tax_amt * 2),
                      bill_amt = bill_amt - (bill_amt * 2) where billRef = '$billRef'";

                            // tax trans
                            $tax_tran = "UPDATE bill_tax_tran SET tran_qty = tran_qty - (tran_qty * 2),
                         taxableAmt = taxableAmt - (taxableAmt * 2), 
                         tax_amt = tax_amt - (tax_amt * 2) 
                         where bill_date = '$today' and mech_no = $machine_number and bill_no = $bill_number";

                            $this->db_connect()->exec($header);
                            $this->db_connect()->exec($trans);
                            // check if there is loyalty
                            $original_reference = $oriRef;
                            $loyalty_cont = $this->row_count('loyalty_tran',"billRef = '$original_reference'");
                            if($loyalty_cont === 1){
                                $loyalty_tran = $this->get_rows('loyalty_tran',"billRef = '$original_reference'");
                                $cust_code = $loyalty_tran['cust_code'];
                                $points = $amount_paid;
                                $curl = curl_init();
                                // then substitute points
                                $payload = '{
                                "module":"points",
                                    "pass_from":"PUT",
                                    "data":{
                                        "billRef":"'.$billRef.'",
                                        "card_no":"'.$cust_code.'",
                                        "points":"'.$points.'"
                                    }
                                }';

                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => 'http://localhost:8000/api/',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS =>"$payload",
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json'
                                    ),
                                ));

                                curl_exec($curl);

                                curl_close($curl);



                            }


                        }

                        // REDEEM points
                        if($this->row_count('bill_trans',"`billRef` = '$billRef' and tran_type = 'L'") === 1)
                        {
                            $loyalty_tran = $this->get_rows('bill_trans',"`billRef` = '$billRef' and tran_type = 'L'");
                            $cust_code = $loyalty_tran['item_barcode'];
                            $points = $loyalty_tran['loyalty_points'];
                            $curl = curl_init();

                            $payload = '{
                                "module":"points",
                                "pass_from":"PUT",
                                "data":{
                                    "billRef":"'.$billRef.'",
                                    "card_no":"'.$cust_code.'",
                                    "points":"'.$points.'"
                                }
                            }';
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => 'http://localhost:8000/api/',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS =>"$payload",
                                CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json'
                                ),
                            ));

                            curl_exec($curl);

                            curl_close($curl);

                            $update_q = "UPDATE loyalty_tran SET points_earned = $points, current_points = points_before + $points where billRef = '$billRef'";
                            $this->db_connect()->exec($update_q);

                        }
                        else
                        {
                            // give points
                            if($this->row_count('loyalty_tran',"`billRef` = '$billRef'") === 1){
                                $cardno = $this->get_rows('loyalty_tran',"`billRef` = '$billRef'")['cust_code'];
                                $points = $net;
                                $payload = '{
                                    "module":"points",
                                    "pass_from":"PUT",
                                    "data":{
                                        "billRef":"'.$billRef.'",
                                        "card_no":"'.$cardno.'",
                                        "points":"'.$points.'"
                                    }
                                }';

                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => 'http://localhost:8000/api/',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS =>"$payload",
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json'
                                    ),
                                ));

                                curl_exec($curl);

                                curl_close($curl);

                                // update total in trans

                                $update_q = "UPDATE loyalty_tran SET points_earned = $amount_paid, current_points = points_before + $amount_paid where billRef = '$billRef'";
                                $this->db_connect()->exec($update_q);

                            }
                        }


                        // print bill
                        printbill($billRef);
                        

                        $code = 200;
                        $msg = "BILL COMPLETED";

                    }
                    else {
                        $code = 505;
                        printMessage($billError);
                    }

                }
                else {
                    $code = 505;
                    $msg = "THERE IS BILL WITH HEADER";
                }


            }



        } else
        {
            $code = 505;
            $msg  = 'Cannot make payment for an empty transaction';
        }


        $x = [
            'code'=>$code,
            'message'=>$msg
        ];

        printMessage($x['message']);

        (new anton())->log2file(var_export($x,true));
        (new anton())->log2file("HELLO FUTURE");
        return $x;

    }

    private function db_handler(): db_handler
    {
        return (new db_handler());
    }

    private function anton(): anton
    {
        return (new anton());
    }

    function TaxTran($bill_no=bill_no,$mech_no=mech_no,$item_code='',$qty=1){
        $date = today;
        $clerk = clerk_code;
        $billRef = $this->getRef();
        // validate machine, item_code
        $itemCount = $this->db_handler()->row_count('prod_mast',"`barcode` = '$item_code'");


        if($itemCount!== 1)
        {
            $this->response['message'] = "Item Not Found $item_code";
        }
        else {

            try {

                // there is item and also bill
                $item = $this->db_handler()->get_rows('prod_mast',"`barcode` = '$item_code'");
                $tax_code = $item['tax_grp'];
                $i_code = $item['id'];

                // get tax details

                if(true)
                {

                    try {

                        // there is tax code

                        $retail = $item['retail'];
                        $cost = $retail * $qty;

                        if($tax_code === 'YES')
                        {

                            $covidRate = 1;
                            $nhisRate = 2.5;
                            $getFundRate = 2.5;

                            $totalCost = $cost; // retail price + quantity

                            $taxableAmount = $totalCost * 100 / 121.9;

                            // get levies values
                            $covid = ($covidRate / 100) * $taxableAmount;
                            $nhis = ($nhisRate / 100) * $taxableAmount;
                            $gFund = ($getFundRate / 100) * $taxableAmount;
                            $vat = (15.9 / 100) * $taxableAmount;


                            try {


                                $this->response['code'] = 200;
                                $tax_detail = array(
                                    'code'=>$tax_code,
                                    'vat'=>number_format($vat,2),
                                    'cv'=>number_format($covid,2),
                                    'gf'=>number_format($gFund,2),
                                    'nh'=>number_format($nhis,2)
                                );
                                $this->response['message'] = $tax_detail;

                            } catch (PDOException $e)
                            {
                                $this->response['code'] = 505;
                                $this->response['message'] .= " ".$e->getMessage() . " " . $e->getLine();
                            }

                        }
                        else {
                            // this is flat rate
                            $this->response['code'] = 200;
                            $tax_detail = array('code'=>$tax_code,'vat'=>0.00,'cv'=>0.00,'gf'=>0.00,'nh'=>0.00);
                            $this->response['message'] = $tax_detail;

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

    public function tax_inclusive($value) {
        try {

            $covidRate = 1;
            $nhisRate = 2.5;
            $getFundRate = 2.5;

            $totalCost = $value; // retail price + quantity

            $taxableAmount = $totalCost * 100 / 121.9;

            // get levies values
            $covid = ($covidRate / 100) * $taxableAmount;
            $nhis = ($nhisRate / 100) * $taxableAmount;
            $gFund = ($getFundRate / 100) * $taxableAmount;
            $vat = (15.9 / 100) * $taxableAmount;

            $this->response['code'] = 200;
            $tax_detail = array(
                'type'=>"INCLUSIVE",
                'vat'=>number_format($vat,2),
                'cv'=>number_format($covid,2),
                'gf'=>number_format($gFund,2),
                'nh'=>number_format($nhis,2)
            );
            $this->response['message'] = $tax_detail;

        } catch (\Exception $e){
                $this->response['code'] = 505;
                $this->response['message'] .= " ".$e->getMessage() . " " . $e->getLine();
        }

        return $this->response;
    }

    function MechSalesSammry($mech_no = 0): array
    {
        $gross = $this->sum('bill_trans','bill_amt',"`mach`= '$mech_no' and `tran_type` in ('SS')");

        $deduct = 0;
        if($this->row_count('bill_trans',"`mach`= '$mech_no' and `tran_type` in ('RF')") > 0){
            $deduct = $this->sum('bill_trans','bill_amt',"`mach`= '$mech_no' and `tran_type` in ('RF')");
        }


        $net = $gross - abs($deduct);

        $tax = $this->sum('bill_tax_tran','tax_amt',"`mech_no`= '$mech_no'");

        return array(
            'gross'=>$gross,'deduct'=>$deduct,'net'=>$net,'tax'=>$tax
        );

    }

    function billSummaryV2($bill_ref = '',$mach_no = mech_no): array
    {
        if($bill_ref === ''){
            $bill_ref = $this->getRef();
        }
        // Initialize variables
        $tax_rate_levies = 0.06; // 6%
        $tax_rate_vat = 0.159; // 15.9%
        $discount_rate = 0.03; // 3%
        $taxable_total = 0;
        $none_taxable_total = 0;
        $bill_trans = array();
        // Calculate taxable total and none-taxable total
        $bills = $this->db_handler()->db_connect()->query("SELECT * FROM bill_trans where billRef = '$bill_ref' and trans_type = 'i'");
        $totalTrans = 0;
        while ($item = $bills->fetch(PDO::FETCH_ASSOC)) {
            $totalTrans ++;
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

        $final_bill = $this->sum('bill_trans',"bill_amt","`billRef` = '$bill_ref' and `trans_type` = 'i'") ;

        $vat = $this->sum('bill_trans',"`vat`","`billRef` = '$bill_ref'");

        $cv = $this->sum('bill_trans',"`covid`","`billRef` = '$bill_ref' and `trans_type` = 'i'");
        $nh = $this->sum('bill_trans',"`nhis`","`billRef` = '$bill_ref' and `trans_type` = 'i'");
        $gf = $this->sum('bill_trans',"`gfund`","`billRef` = '$bill_ref' and `trans_type` = 'i'");
        $taxable_amount_inclusive = $this->sum('bill_trans','bill_amt',"`billRef` = '$bill_ref' and `tax_code` = 'YES' and `trans_type` = 'i'");
        $non_taxable_amount = $this->sum('bill_trans','bill_amt',"`billRef` = '$bill_ref' and `tax_code` != 'YES' and `trans_type` = 'i'");


        if($this->row_count('bill_trans',"`tran_type` = 'D' and `billRef` = '$bill_ref'") === 1)
        {
            // there is rate discount
            $disc_row = $this->get_rows('bill_trans',"`tran_type` = 'D' and `billRef` = '$bill_ref'");
            $rate = $disc_row['discount_rate'] / 100;

            $discount1 = $final_bill * $rate;
            $discount2 = $discount1 * 2;
            $discount = $discount1 - $discount2;

        } elseif ($this->row_count('bill_trans',"`tran_type` = 'L' and `billRef` = '$bill_ref'") === 1){
            $discount = $this->sum('bill_trans',"`discount`","`billRef` = '$bill_ref' and `tran_type` = 'L'");
        } else {
            $discount = 0;
        }



        $levies = $gf + $nh + $cv;



        $taxRate = 0.219; // 21.9%



        $bill_header = array();
        $bill_header['BILL_REF'] = $bill_ref;
        $bill_header['BILL_AMT'] = number_format($final_bill + $discount,2);
        $bill_header['TOTAL_AMOUNT'] = $final_bill;
        $bill_header['TOTAL_LEVY'] = $levies;
        $bill_header['TOTAL_VAT'] = $vat;
        $bill_header['ITEMS_COUNTS'] = $totalTrans;
        $bill_header['TAXABLE_AMOUNT'] = $taxable_amount_inclusive;
        $bill_header['NON_TAXABLE_AMOUNT'] = $non_taxable_amount;
        $bill_header['DISCOUNT'] = number_format($discount,2);
        $bill_header['DISCOUNT_RATE'] = 0;


        if($this -> row_count('bill_header',"`billRef` = '$bill_ref'")){
            $hd = $this -> get_rows('bill_header',"`billRef` = '$bill_ref'");
            $bill_header['INVOICE_DATE'] = $hd['bill_date'];

        } else {
            $bill_header['INVOICE_DATE'] = today;
        }

        // Prepare the response array
        $response = [
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
        ];

        // Return the response
        return $response;

    }

    function shifts(){
        return (new shift());
    }
    // make total sales
    function downloadSales($sales_date = today): array
    {
        $response = array('status_code'=>505,'message'=>null);
        try {
           
            $this->db_connect()->exec("CALL copySalesHd('$sales_date')"); // copy hd
            $this->db_connect()->exec("CALL copySalesTrans('$sales_date')"); // copy trans
            # customer
            $this->checkPayments($sales_date);
            $response['status_code'] = 200;
            $response['message'] = "SALES COPIED";
        } catch (\Exception $e){
            $msg = $e->getMessage() . " LINE " . $e->getLine();
            $response['message'] = "Could not copy sales " . $msg;
        }

        return $response;

    }

    public function checkPayments ($date = today) {
        $bills_query = "SELECT gross_amt,customer,tran_qty,billRef FROM bill_header where sales_date = '$date'";
        $bill_stmt = $this->db_connect()->prepare($bills_query);
        $bill_stmt->execute();

        while($row = $bill_stmt->fetch(PDO::FETCH_ASSOC)){
            $cust_no = $row['customer'];
            $gross = -$row['gross_amt'];
            $nega_gross = $gross * -1;
            $qty = $row['tran_qty'];
            $ref = $row['billRef'];
            if($this->row_count('customers',"`cust_no` = '$cust_no'") === 1){
                $customer = $this->get_rows('customers',"`cust_no` = '$cust_no'");
                $id = $customer['customer_id'];

                // add transaction
                $transaction = "INSERT INTO customers_trans (customer_id, total_amount, payment_method, items_purchased, transaction_notes) VALUES 
                                                            ('$id','$gross','credit','$qty','$ref')";
                $this->exe($transaction);
            }

        }
    }

    // validate refund
    public function validateRefund($table,$oldReference){
        // check if bill exist
        $response = array('status'=>false,'message'=>'not initialized');
        if($this->row_count("$table","`billRef` = '$oldReference'") > 0){
            $error = 0;
            $error_msg = 0;
            // check items
            $sql = "SELECT * FROM `$table` where `billRef`='$oldReference'";
            $stmt = $this-db_connect()->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->PDO::FETCH_ASSOC) {
                $barcode = $row['item_barcode'];
                $qty = $row['qty'];

                // check in current bill
                
            }
            $response['status'] = true;

        }

        return $response;
    }

}