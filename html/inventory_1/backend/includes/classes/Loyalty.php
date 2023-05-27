<?php

namespace loyalty;

class Loyalty extends \db_handeer\db_handler
{


    public function cusReg($name,$email): array
    {
        // register customer
        try {
            $this->exe("insert into loy_customer(name, email) VALUES ('$name','$email')");
            $response = array('code'=>202,'message'=>$this->getCus($email));
        } catch (\Exception $e){
            $response = array('code'=>505,'message'=>"Error : ".$e->getMessage()." LINE : ".$e->getLine());
        }

        return $response;
    }

    public function getCus($str): array
    {
        try {
            $count = $this->row_count('loy_customer',"`email` = '$str' OR `name` = '$str' OR `cust_code` = '$str'");
            if($count === 1){
                // there is customer
                $customer = $this->get_rows('loy_customer',"`email` = '$str' OR `name` = '$str' OR `cust_code` = '$str'");

                $cd = array(
                    'code'=>$customer['cust_code'],
                    'name'=>$customer['name'],
                    'email'=>$customer['email'],
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

    public function givePoints($cust_code,$billRef,$point=0.00){
        try {
            $this->exe("insert into loyalty_point_stmt(cust_code, billRef,value) values ('$cust_code','$billRef','$point')");
            return array('code'=>202,'message'=>"Points Added");
        } catch (\Exception $e){
            return array('code'=>505,'message'=>"Error : ".$e->getMessage()." LINE : ".$e->getLine());
        }
    }

}

$loyalty = new Loyalty();