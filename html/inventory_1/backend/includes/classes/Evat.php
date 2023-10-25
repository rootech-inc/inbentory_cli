<?php

namespace billing;

use db_handeer\db_handler;
use Exception;

class Evat extends db_handler
{
   public function send_invoice($billRef,$flag = 'INVOICE',$original_bill_ref = ''){

        if($this->checkServer()){
            
            # get bill summary
            $bill = (new \billing\Billing())->billSummaryV2($billRef,1);

            $hd = $bill['bill_header'];
            (new \anton())->log2file("BULL SUMMARY",'',1);
            (new \anton())->log2file(var_export($bill,true),'',0);
            (new \anton())->log2file("BILL HEADER");
            (new \anton())->log2file(var_export($hd,true));
            $refund_id = '';

            if($flag === 'REFUND'){
                // get extra
                $refund_id = billRef;
                $billRef = (new \anton())->get_session('refundBillRef'); // original invoice number;
            }

            # do trans
            $output = "";
            $t_q = $this->db_connect()->query("SELECT * FROM bill_trans where billRef = '$billRef' and trans_type = 'i'");
            $t_q = $this->db_connect()->query("
                    select item_barcode, pm.`desc` as 'name', pm.retail as 'retail_price', tm.attr as 'tax_group',tm.rate as 'tax_rate',
                case when tm.attr = 'VM' then 'B' else 'A' end as 'tax_com', sum(item_qty) as 'qty', sum(tax_amt) as 'tax_amt',
                sum(nhis) as 'LEVY_A', sum(gfund) as 'LEVY_B', sum(covid) as 'LEVY_C' from bill_trans
                right join posdb.prod_mast pm on bill_trans.item_barcode = pm.barcode right join tax_master tm on pm.tax_grp = tm.id
                where bill_trans.billRef = '$billRef' and bill_trans.trans_type = 'i' group by bill_trans.item_barcode;
                    ");

            $trans = $bill['bill_trans'];
            $item_count = 0;
            while($tran = $t_q->fetch(\PDO::FETCH_ASSOC))
            {
                $item_count ++;
                $barcode = $tran['item_barcode'];
                $item = $this->get_rows('prod_mast',"`barcode` = '$barcode'");
                $item_code = $item['id'];
                    $tran_code = $tran['item_barcode'];
                // get taxes
                $LEVY_A = $tran['LEVY_A']; //NHIS 2.5
                $LEVY_B = $tran['LEVY_B']; //GETFL 2.5 %
                $LEVY_C = $tran['LEVY_C']; //COVID 1

                $data ='{
                            "ITMREF": "'.$tran['item_barcode'].'",
                            "ITMDES": "'.$tran['name'].'",
                            "TAXRATE": "'.$tran['tax_rate'].'",
                            "TAXCODE": "'.$tran['tax_com'].'",
                            "LEVY_AMOUNT_A": "'.abs($LEVY_A).'",
                            "LEVY_AMOUNT_B": "'.abs($LEVY_B).'",
                            "LEVY_AMOUNT_C": "'.abs($LEVY_C).'",
                            "LEVY_AMOUNT_D": "0",
                            "QUANTITY": "'.abs($tran['qty']).'",
                            "UNITYPRICE": "'.abs($tran['retail_price']).'",
                            "ITMDISCOUNT": "0",
                            "BATCH": "",
                            "EXPIRE": "",
                            "ITEM_CATEGORY": "NOT SET"
                        },';
                $output .= $data;
            }

            $curl = curl_init();
                $setting = array(
                    CURLOPT_URL => evat_url.'send_invoice/',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_POSTFIELDS =>'{
                "header": {
                    "COMPUTATION_TYPE": "INCLUSIVE",
                    "FLAG": "'.$flag.'",
                    "SALE_TYPE":"NORMAL",
                    "USER_NAME": "ARNAU",
                    "NUM": "'.$billRef.'",
                    "REFUND_ID":"'.$refund_id.'",
                    "INVOICE_DATE": "2020-07-15",
                    "CURRENCY": "GHS",
                    "EXCHANGE_RATE": "1",
                    "CLIENT_TIN": "C0022825405",
                    "CLIENT_TIN_PIN": "2222",
                    "CLIENT_NAME": "Elissa",
                    "TOTAL_VAT": "'.abs($hd['TOTAL_VAT']).'",
                    "TOTAL_LEVY":  "'.abs($hd['TOTAL_LEVY']).'",
                    "TOTAL_AMOUNT":  "'.abs($hd['TOTAL_AMOUNT']).'",
                    "ITEMS_COUNTS":  "'.abs($item_count).'",
                    "VOUCHER_AMOUNT": "0",
                    "DISCOUNT_TYPE":"GENERAL",
                    "DISCOUNT_AMOUNT":"0",
                    "FILE_NAME": "",
                    "CALL_BACK": "http://host/receiptCallback.php"
                },
                "item_list": ['.rtrim($output,',').']
                }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Cookie: csrftoken=etMJ2XQLTdShoVg4UxIfhE67JjVuYtNP'
                    ),
                );
            curl_setopt_array($curl,
                $setting);

            $response = curl_exec($curl);

            curl_close($curl);

            header("Content-Type:Application/Json");

        } else {
            $rr = array(
                'code'=>505,'message'=>"COULD NOT CONNECT TO EVAT API. ( send_inv )"
            );
            $response = json_encode($rr);
        }
       


       return $response;

   }

   public function checkServer(){
    

    // Initialize cURL session
    $curl = curl_init(evat_url);

    // Set cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5); // Set a timeout in seconds

    // Execute the cURL request
    $response = curl_exec($curl);

    // Check if the cURL request was successful and the response code is 200 (OK)
    if ($response === false || curl_getinfo($curl, CURLINFO_RESPONSE_CODE) !== 200) {
        $r = false;
    } else {
        $r = true;
    }

   

    // Close the cURL session
    curl_close($curl);
    return $r;

   }

    public function sign_invoice($num,$flag='INVOICE',$ref_id = '')
    {
        if($this->checkServer())
        {
            if($flag === 'REFUND'){
                // get extra
                $ref_id = billRef;
                $num = (new \anton())->get_session('refundBillRef'); // original invoice number;
            }
    
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL =>  evat_url.'sign/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => '{
    
                "header": {
                        "NUM": "'.$num.'",
                        "FLAG": "'.$flag.'",  
                        "REFUND_ID": "'.$ref_id.'" 
                    }
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: csrftoken=etMJ2XQLTdShoVg4UxIfhE67JjVuYtNP'
                ),
            ));
    
            $response = curl_exec($curl);
    
            curl_close($curl);
        } else {
            $response = array(
                'code'=>505,'message'=>"COULD NOT CONNECT TO EVAT API. ( sign )"
            );
        }
        

        print_r($response);
        return $response;

    }

    public function get_signature($billRef): array
    {
        $dbHandler = new db_handler();

        $sign = $dbHandler->get_rows('evat_transactions',"`billRef` = '$billRef'");

        $signature = array(
            'ysdcid'=> $sign['ysdcid'] ?? 'none',
            'ysdcitems'=> $sign['ysdcitems'] ?? 'none',
            'ysdcmrc'=> $sign['ysdcmrc'] ?? 'none',
            'ysdcmrctim'=> $sign['ysdcmrctim'] ?? 'none',
            'ysdcrecnum'=> $sign['ysdcrecnum'] ?? 'none',
            'ysdctime'=> $sign['ysdctime'] ?? 'none',
            'ysdcintdata'=> $sign['ysdcintdata'] ?? 'none',
            'ysdcregsig'=> $sign['ysdcregsig'] ?? 'none',
            'qr_code'=> $sign['qr_code'] ?? 'none',
        );

        return $signature;

    }

}