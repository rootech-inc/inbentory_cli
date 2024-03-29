<?php

namespace billing;

use db_handeer\db_handler;
use Exception;

class Evat extends db_handler
{
    public $call_url;

    public $response = array("status"=>505,'response'=>'INI');

    public function __construct($call_url)
    {
        $this->call_url = $call_url;
    }

    public function set_url($url){
        $this->call_url = $url;
    }

    # send invoice

    // invoice header
    public function invoice_header($mech_no,$bill_number,$tran_date): string
    {
        $invoice_no = md5(rand(1738,9999));
        return "{
              'COMPUTATION_TYPE': 'INCLUSIVE',
              'FLAG': 'INVOICE',
              'SALE_TYPE':'NORMAL',
              'USER_NAME': 'ARNAU',
              'NUM': 'INV$invoice_no',
              'INVOICE_DATE': '2023-07-15',
              'CURRENCY': 'GHS',
              'EXCHANGE_RATE': '1',
              'CLIENT_TIN': 'C0022825405',
              'CLIENT_TIN_PIN': '2222',
              'CLIENT_NAME': 'Elissa',
              'TOTAL_VAT': '159',
              'TOTAL_LEVY': '60',
              'TOTAL_AMOUNT': '3219',
              'ITEMS_COUNTS': '2',
              'VOUCHER_AMOUNT': '0',
              'DISCOUNT_TYPE':'GENERAL',
              'DISCOUNT_AMOUNT':'0',
              'FILE_NAME': '',
              'CALL_BACK': 'http://host/receiptCallback.php'
        }";
    }

    // invoice items list
    public function inv_items($mech_no,$bill_number,$tran_date): string
    {
        $items = "{
         'ITMREF': 'MANGO01',
         'ITMDES': 'Mango juice',
         'TAXRATE': '0',
         'TAXCODE': 'A',
          'LEVY_AMOUNT_A': '0', 
	      'LEVY_AMOUNT_B': '0', 
	      'LEVY_AMOUNT_C': '0',
	      'LEVY_AMOUNT_D': '0',
          'QUANTITY': '20',
          'UNITYPRICE': '100',
          'ITMDISCOUNT': '0',
          'BATCH': '',
         'EXPIRE': '',
         'ITEM_CATEGORY': 'JUICE'
      },
      {
         'ITMREF': '1000P1322',
         'ITMDES': 'INYAGE MILK 2% 500ML',
         'TAXRATE': '15',
         'TAXCODE': 'B',
         'LEVY_AMOUNT_A': '25', 
	     'LEVY_AMOUNT_B': '25', 
	     'LEVY_AMOUNT_C': '10',
	     'LEVY_AMOUNT_D': '0',
         'QUANTITY': '1',
         'UNITYPRICE': '1219',
         'ITMDISCOUNT': '0',
         'BATCH': '',
         'EXPIRE': '',
          'ITEM_CATEGORY': 'MILK'
      }";

        return "[
            $items
         ]";
    }

    public function invoice($mech_no,$bill_number,$tran_date){

        $inv_header = $this->invoice_header($mech_no,$bill_number,$tran_date);
        $inv_items = $this->inv_items(mech_no,$bill_number,$tran_date);

        try {
            $curl = curl_init();

            if ($curl === false) {
                throw new Exception('failed to initialize');
            }

            curl_setopt_array($curl, array(
                CURLOPT_URL => "$this->call_url/post_receipt_Json.jsp",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>"{
                'company': {
                        'COMPANY_NAMES': 'SNEDA SHOPPING CENTRE',
                        'COMPANY_SECURITY_KEY': 'APXNTSNXVFFZ6ZF7PPZVDOCIZFIVQ0RG',
                        'COMPANY_TIN': 'C0003545865'
                    },
                'header': $inv_header,
                'item_list': [
                      $inv_items
                   ]
                
                }",
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: JSESSIONID=000B34D06B8C3990FC40018C1A91929E'
                ),
            ));

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            // execute
            $call = curl_exec($curl);
            $resp = json_decode($call)->RESPONSE;
            curl_close($curl);
            $MESSAGE = $resp->MESSAGE;
            $STATUS = $resp->STATUS;
            if($STATUS === 'SUCCESS')
            {
                $NUM = $MESSAGE->NUM;
                $this->response = $this->get_signature($NUM);
//                $this->response['status'] = 200;
//                $this->response['message'] = "EVAT SUCCESS $NUM";

            } else {
                $this->response['message'] = "Could not get response";
            }
            return $this->response;

        } catch(Exception $e) {

            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);

        } finally {
            // Close curl handle unless it failed to initialize
            if (is_resource($curl)) {
                curl_close($curl);
            }
        }

    }
    # send invoice

    # START GET SIGNATURE #

    public function get_signature($num){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.2.88:8080/evat_api/get_Response_JSON.jsp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>"
                {
                'company': {
                        'COMPANY_NAMES': 'SNEDA SHOPPING CENTRE',
                        'COMPANY_SECURITY_KEY': 'APXNTSNXVFFZ6ZF7PPZVDOCIZFIVQ0RG',
                        'COMPANY_TIN': 'C0003545865'
                    },
                'header': {
                'NUM': '$num',
                'FLAG': 'INVOICE'
                }
                }
            ",
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: JSESSIONID=D95E1548B21E07B6FF3E72AE2D62462A'
                ),
            ));

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

            $call = curl_exec($curl);
            $response = json_decode($call)->RESPONSE;
            curl_close($curl);

            $STATUS = $response->STATUS;

            $sig_resp = array('status'=>505,'message'=>'');

            if($STATUS === 'SUCCESS') {

                $MESSAGE = $response->MESSAGE;
                $flag = $MESSAGE->flag;
                $ysdcrecnum = $MESSAGE->ysdcrecnum;
                $num = $MESSAGE->num;
                $ysdcid = $MESSAGE->ysdcid;
                $ysdcintdata = $MESSAGE->ysdcintdata;
                $ysdcmrc = $MESSAGE->ysdcmrc;
                $ysdcitems = $MESSAGE->ysdcitems;
                $ysdcmrctim = $MESSAGE->ysdcmrctim;
                $ysdcregsig = $MESSAGE->ysdcregsig;
                $ysdctime = $MESSAGE->ysdctime;

                $QR_CODE = $response->QR_CODE;

                $sig_resp['status'] = 200;
                $sig_resp['message'] = array(
                    'flag'=>$flag,'num'=>$num,'ysdcrecnum'=>$ysdcrecnum,'ysdcid'=>$ysdcid,'ysdcintdata'=>$ysdcintdata,
                    'ysdcmrc'=>$ysdcmrc,'ysdcitems'=>$ysdcitems,'ysdcmrctim'=>$ysdcmrctim,'ysdcregsig'=>$ysdcregsig,'ysdctime'=>$ysdctime,
                    'qr_code'=>$QR_CODE,
                );
            } else {
                $sig_resp['status'] = 500;
                $error_code = $sig_resp['status'];
                $error_msg = $response->MESSAGE;
                $sig_resp['message'] = array('code'=>$error_code,'message'=>$error_msg);
            }

            return $sig_resp;

    }

    # END GET SIGNATURE #

}