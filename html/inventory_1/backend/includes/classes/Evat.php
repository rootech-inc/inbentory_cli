<?php

namespace billing;

use db_handeer\db_handler;
use Exception;

class Evat extends db_handler
{
   public function send_invoice($billRef){
       # get bill summary
       $bill = (new \billing\Billing())->billSummaryV2('001230129291',1);

       $hd = $bill['bill_header'];

       # do trans
       $output = "";
       $t_q = (new db_handler())->db_connect()->query("SELECT * FROM bill_trans where billRef = '$billRef' and trans_type = 'i'");

       $trans = $bill['bill_trans'];

       foreach ($trans as $item) {

           foreach ($item as $key => $value) {
               $data ='{
                    "ITMREF": "1000P1322",
                    "ITMDES": "INYAGE MILK 2% 500ML",
                    "TAXRATE": "15",
                    "TAXCODE": "B",
                    "LEVY_AMOUNT_A": "25",
                    "LEVY_AMOUNT_B": "25",
                    "LEVY_AMOUNT_C": "10",
                    "LEVY_AMOUNT_D": "0",
                    "QUANTITY": "1",
                    "UNITYPRICE": "1219",
                    "ITMDISCOUNT": "0",
                    "BATCH": "",
                    "EXPIRE": "",
                    "ITEM_CATEGORY": "MILK"
                },';
               $output .= $data;
           }

       }

       $curl = curl_init();
        $setting = array(
            CURLOPT_URL => 'http://127.0.0.1:8000/send_invoice/',
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
              "FLAG": "INVOICE",
              "SALE_TYPE":"NORMAL",
              "USER_NAME": "ARNAU",
              "NUM": "HELLO_ANTON1",
              "INVOICE_DATE": "2020-07-15",
              "CURRENCY": "GHS",
              "EXCHANGE_RATE": "1",
              "CLIENT_TIN": "C0022825405",
              "CLIENT_TIN_PIN": "2222",
              "CLIENT_NAME": "Elissa",
              "TOTAL_VAT": "'.$hd['TOTAL_VAT'].'",
              "TOTAL_LEVY":  "'.$hd['TOTAL_LEVY'].'",
              "TOTAL_AMOUNT":  "'.$hd['TOTAL_AMOUNT'].'",
              "ITEMS_COUNTS":  "'.$hd['ITEMS_COUNTS'].'",
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


       return $response;

   }

    public function sign_invoice($num,$flag='INVOICE',$ref_id = '')
    {
        $num = "HELLO_ANTON";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://127.0.0.1:8000/sign/',
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


        return $response;

    }

}