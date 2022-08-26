<?php

class grn
{

    public function db_connect() // connect to database
    {
        $host = "127.0.0.1";
        $user = "root";
        $password = "Sunderland@411";
        $db = "SMHOS";
        //set DSN
        $dns = 'mysql:host='.$host.';dbname='.$db;

        //create pdo instanse
        $pdo = new PDO($dns,$user,$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }

    function grn_list_item($query_details,$supplier)
    {
        if(strlen($query_details) < 1)
        {
            // return error
            return $this->return_error();

        } else {
            // search for item and return result as array
            $query =  $this->db_connect()->query("SELECT * FROM `prod_master` WHERE `item_code` like '%$query_details%' OR item_desc like '%$query_details%' OR barcode = like '%$query_details%' AND supplier = '$supplier'");
            if($query->rowCount() > 0 )
            {
                header('Content-Type: application/json');
                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                return json_encode($res);
            }
        }
    }
}