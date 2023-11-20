<?php

namespace loyalty;

use billing\Billing;

class Loyalty extends \db_handeer\db_handler
{


    public function cusReg($name,$email,$mob): array
    {
        // register customer
        try {
            $this->exe("insert into loy_customer(name, email,mobile) VALUES ('$name','$email','$mob')");
            $response = array('code'=>202,'message'=>$this->getCus($email));
        } catch (\Exception $e){
            $response = array('code'=>505,'message'=>"Error : ".$e->getMessage()." LINE : ".$e->getLine());
        }

        return $response;
    }

    public function getCus($str): array
    {



        try {
            $count = $this->row_count('loy_customer',"`email` = '$str' OR `name` = '$str' OR `cust_code` = '$str' OR `mobile`='$str'");
            if($count === 1){
                // there is customer
                $customer = $this->get_rows('loy_customer',"`email` = '$str' OR `name` = '$str' OR `cust_code` = '$str' OR `mobile`='$str' ");

                $cd = array(
                    'code'=>$customer['cust_code'],
                    'name'=>$customer['name'],
                    'email'=>$customer['email'],
                    'mobile'=>$customer['mobile'],
                    'points'=>$this->getPoint($customer['cust_code'])
                );

                return array('code'=>202,'message'=>$cd);
            }
            else {
                return array('code'=>404,'message'=>"CUSTOMER DOES NOT EXIST");
            }
        } catch (\Exception $e){
            return array('code'=>505,'message'=>"Error : ".$e->getMessage()." LINE : ".$e->getLine());
        }
    }

    public function getPoint($cust_code){
        // get points
        return $this->sum('loyalty_point_stmt','value',"`cust_code` = '$cust_code'");
    }

    public function givePoints($billRef): array
    {
        echo 'giving poing';
        $response = array(
            'code'=>0,'message'=>'ini...'
        );
        if($this->row_count('loyalty_tran',"`billRef` = '$billRef'") === 1)
        {
            $billing = (new Billing())->billSummaryV2($billRef);
            $total_bill = $billing['total_bill'];
            $loyalty_tran = $this->row_count('loyalty_tran',"`billRef` = '$billRef'");
            $cust_code = $loyalty_tran['cust_code'];

            try {
                (new \anton())->log2file('LOYALTY POINT GIVE');
                $this->exe("insert into loyalty_point_stmt(cust_code, billRef,value) values ('$cust_code','$billRef','$total_bill')");
                (new \anton())->log2file('LOYALTY POINT GIVE');
                $response['code'] = 202;
                $response['message'] = "$total_bill Points Added";
            } catch (\Exception $e){
                $response['code'] = 505;
                $response['message'] = "Error : ".$e->getMessage()." LINE : ".$e->getLine();
            }
        } else {
            $response['code'] = 404;
            $response['message'] = "NO CUSTOMER FOUND";
        }
        print_r($response);
        return $response;

    }

    public function loadCustomer($card_no){


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:1000/api/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'VIEW',
            CURLOPT_POSTFIELDS => '{
                "module":"card",
                "data":{
                    "card_no":"'.$card_no.'"
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        if($response['code'] === 200){
            // load card

            $billRef = billRef;
            // validate no duplicate
            if($this->row_count('loyalty_tran',"`billRef` = '$billRef'") > 0){
                $this->delete('loyalty_tran',"`billRef` = '$billRef'");
            }

            // insert
            $query = "INSERT INTO loyalty_tran (cust_code, billRef) values ('$card_no','$billRef')";
            $this->db_connect()->exec($query);

        }
        return $response;

    }

}

$loyalty = new Loyalty();