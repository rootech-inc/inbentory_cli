<?php

namespace mechconfig;

use JetBrains\PhpStorm\NoReturn;
use PDO;
use PDOException;
use anton;
use db_handeer\db_handler;

class MechConfig
{

    public function config(): array
    {
        $iniFile = $_SERVER['DOCUMENT_ROOT'] . '/config.ini';
        $config = parse_ini_file($iniFile, true);

        $system = $config['system_config'];
        if($system['DB_SOURCE'] === 'NETWORK'){
            $db = $config['DB_NETWORK'];
        } else {
            $db = $config['DB_LOCAL'];
        }
        $printer = $config['PRINTER'];
        $evat = $config['EVAT'];
        $settings =  array(
            'DEBUG'=>$system['DEBUG'],
            'MACH_NO'=>$system['MACH_NO'],
            'MAC_ADDRESS'=>$system['MAC_ADDRESS'],
            'LOC_ID'=>$system['LOC_ID'],

            'DB_HOST'=>$db['HOST'],
            'DB_PASSWORD'=>$db['PASSWORD'],
            'DB_USER'=>$db['USER'],
            'DB_NAME'=>$db['NAME'],
            'DB_PORT'=>$db['PORT'],

            'PRINTER'=>$printer['NAME'],
            'BILL_PRINT'=>$printer['BILL_PRINT'],

            'EVAT'=>$evat['ACTIVE'],
            'EVAT_API'=>$evat['BASE_URL']
        );


        return $settings;
    }



    public function mech_db()
    {

        try {
            $l_route = '';
            $local_sqlite = root.'/backend/includes/database/phpsqlite.db';

            return new PDO("sqlite:$local_sqlite");
        } catch (PDOException $err)
        {

            (new \anton)->error_handler('Mech Setup','Cannot connect to mechine db');
        }

    }

    public function mech_details(): array
    {
        $machine = $this->config();
        $mech_no = $machine['MACH_NO'];
        $mech_mach_addr = $machine['MAC_ADDRESS'];


        return array(
            'machine_number'=>$mech_no,
            'machine_mac'=>$mech_mach_addr,
            'mechine_number'=>$mech_no
        );

//        $sql = $this->mech_db()->query("SELECT * FROM machine_config LIMIT  1");
//
//        $valid_sql = $this->mech_db()->query("SELECT count('mechine_number') as 'mn' from machine_config");
//        $valid_stmt = $valid_sql->fetch(PDO::FETCH_ASSOC);
//        $valid = $valid_stmt['mn'];
//
//        if(intval($valid) !== 1)
//        {
//            // add machine
//            return array('mechine_number'=>'0');
//        }
//        else
//        {
//            return $sql->fetch(PDO::FETCH_ASSOC);
//        }


    }

    public function validate_device()
    {
        $machine = $this->config();
        $number = $machine['MACH_NO'];
        $mac = $machine['MAC_ADDRESS'];



        if($number == 0){
            (new anton())->error_handler("MACHINE ENV ERROR","There is an error with your Machine Number. It is not set in environment");
        }



        $valid_sql = (new db_handler())->db_connect()->query("SELECT count('mech_no') as 'mn' from mech_setup where mech_no = '$number' and mac_addr = '$mac'");
        $valid_stmt = $valid_sql->fetch(PDO::FETCH_ASSOC);
        $valid = $valid_stmt['mn'];

        if(intval($valid) !== 1)
        {

            // add machine
            $mec_mac = getenv('MAC_ADDRESS');
            $mec_no = getenv('MECH_NO');
            $mech_db = (new db_handler())->db_connect();

            // validate mach address is empty;
            if((new db_handler())->row_count('mech_setup',"mac_addr = '$mec_mac'") !== 0){
                (new anton())->error_handler("MAC ADDRESS TAKEN","Machine with mac address $mec_mac exists");
            }
            elseif ((new db_handler())->row_count('mech_setup',"mech_no = '$mec_no'") !== 0){
                (new anton())->error_handler("MAC No. TAKEN","Machine with numbers $mec_no exists");
            }
            else {
                require root."/backend/includes/parts/add-ons/add_machine.php";
            }
            die();
        }

    }

    public function ini_device($number,$mac_addr,$desc)
    {
        $q = "INSERT INTO machine_config (mechine_number, discr, mac_addr) values ($number,'$desc','$mac_addr')";
//        echo $q;
        $this->mech_db()->prepare($q);
        $this->mech_db()->exec($q);

    }

    // get current bill
    public function bill_number()
    {
        $td = date('Y-m-d');
        $query = "SELECT count(bill_no) as 'bill_no' from bill_header where bill_date = '$td'";
        $exe = $this->mech_db()->query($query);
        $r = $exe->fetch(PDO::FETCH_ASSOC);
        return $r['bill_no'] + 1;
    }

    function lite_row_count($table,$column,$condition): int // row count of a table
    {

        $xquery = "SELECT count($column) as 'col_count' from $table where $condition";
//        echo "SQLITE ROW COUNT ::  $xquery";
        $exe = $this->mech_db()->query($xquery);
        $r = $exe->fetch(PDO::FETCH_ASSOC);
        return $r['col_count'];
    }

    public function sum($table,$column,$condition,$as = 'result')
    {
        $sql = $this->mech_db()->query("SELECT SUM($column) as $as FROM `$table` WHERE $condition");
        $stmt = $sql->fetch(PDO::FETCH_ASSOC);
        return $stmt["$as"];
    }

    public function add_item_bill($bill_number,$item,$qty,$myName)
    {
        //get item details
        $machine_number = mech_no;
        $clerk = $_SESSION['clerk_id'];


        // get item details
        $item_desc = $item['desc'];
        $item_retail = $item['retail'];
        $barcode = $item['barcode'];
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
        if($taxDetails['rate'] < 1)
        {
            $taxAmount = 0.00;
        }
        else
        {
            // calculate for tax
            $taxAmount = (new \anton)->tax($rate,$bill_amt);
        }



        // add to bill in trans
        $sql = "insert into `bill_trans` 
                (`mach`,`clerk`,`bill_number`,`item_barcode`,
                 `item_desc`,`retail_price`,`item_qty`,`tax_amt`,
                 `bill_amt`,`trans_type`,`tax_grp`,`tax_rate`) values
                 ('$machine_number','$myName','$bill_number','$barcode',
                  '$item_desc','$item_retail','$qty','$taxAmount',
                  '$bill_amt','i','$tax_description','$rate')";


        try {
            $this->mech_db()->prepare($sql);
            $this->mech_db()->exec($sql);

            return true;

        } catch (PDOException  $e)
        {
//            echo "error%%".$e->getMessage();
            return false;
        }


    }

    // get row
    function get_rows($table, $condition, $result = 'array') // get rows from table
    {
        if($condition === 'none')
        {
            $sql = "SELECT * FROM $table";
        }
        else
        {
            $sql = "SELECT * FROM $table WHERE $condition";
        }
        $stmt = $this->mech_db()->query($sql);



        if($result === 'array')
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        elseif ($result === 'json')
        {
            header('Content-Type: application/json');
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($res);
        }
        else
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }



    }





}