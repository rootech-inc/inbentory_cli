<?php

use db_handeer\db_handler;
use taxer\tax_calculator;

ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    ini_set('memory_limit', '-1');
    
    error_reporting(E_ALL ^ E_DEPRECATED);

    define('root',$_SERVER['DOCUMENT_ROOT']);
    define('host_ip',$_SERVER['HTTP_HOST']);
    const printer = 'EPSON';

    $bill_number = 0;

    const db_host = 'localhost';
    const db_user = 'anton';
    const db_password = '258963';
    const db_name = "posdb";

    $phy = $_SERVER['APPL_PHYSICAL_PATH'];


    require 'session.php';
    $session_id = session_id();
    $logo = $phy . "\comp_logo_1.png";
    define('logo',$logo);


    // initialize classes
    require 'anton.php';
    require 'db_handler.php';
    require 'tax_calculator.php';
    require 'MechConfig.php';
    require 'classes/Billing.php';
    require 'classes/reports.php';
    require  'classes/auth.php';
    require 'classes/shift.php';
    require  'classes/Evat.php';
    require  'classes/Loyalty.php';
    require 'classes/ProductMaster.php';

    $evat = new \billing\Evat('');

    $bill = new \billing\Billing();


    $anton = new anton();
    $db = new db_handler();
    $d_b = new db_handler();

$anton->log2file($logo,"LOGO",1);

    $taxCalc = new tax_calculator();
    $MConfig = new \mechconfig\MechConfig();
    $company = $db->get_rows('company',"`id` = 0");
    // validate machine
    define('mech_no',$MConfig->mech_details()['mechine_number']);
    $machine_number = mech_no;
    $shift_no = '';
    if($shiftCL->is_shift(mech_no))
    {
        $rid = $db->get_rows("shifts","`mech_no` = '$machine_number'  AND `end_time` is null ")['recId'];
        $shit_detail = $shiftCL->my_shift($rid);
        
        $today = $shit_detail['date'];
        $shift_enc = $shit_detail['enc'];
        $shift_no = $shit_detail['shift_no'];
        
    } else {
        $today = date('Y-m-d');
        $shift_enc = '';
    }
    define('shift_no',$shift_no);
    

    $evat = false;
    $evat_url = '';
    if(
        $db->row_count("sys_settings","`set_key` = 'evat'") === 1 &&
        $db->get_rows("sys_settings","`set_key` = 'evat'")['set_status'] === 1
    ){
        $evat = true;
        $evat_url = $db->get_rows("sys_settings","`set_key` = 'evat'")['set_value'];
    }
    define('evat_url',$evat_url);
    $bill_print = false;
    if(
        $db->row_count("sys_settings","`set_key` = 'bill_print'") === 1 &&
        $db->get_rows("sys_settings","`set_key` = 'bill_print'")['set_status'] === 1
    ){
        $bill_print = true;
    }
    define('evat',$evat);
    define('today',$today);
    define('bill_print',$bill_print);
    define('shift_enc',$shift_enc);
    $current_time = date("Y-m-d H:m:s");



    define('doc_root',$_SERVER['DOCUMENT_ROOT']);

    $root_host = $_SERVER['DOCUMENT_ROOT'];
    $shift = $db->shift(mech_no);
    define('shift',$shift);
    define('company_name',$company['c_name']);
    define('company_country',$company['country']);
    define('company_city',$company['city']);
    define('company_street',$company['street']);
    define('company_mob',$company['phone']);





    if(isset($_SESSION['cli_login']) && $_SESSION['cli_login'] === 'true')
    {
        $session_id = session_id();
        $clerk_id = $anton->get_session('clerk_id');
        $my = $db->get_rows('clerk',"`id` = '$clerk_id'");
        $myName = $my['clerk_name'];


        define('clerk_code',$my['clerk_code']);
        define('clerk_name',$my['clerk_name']);
        define('clerk_id',$clerk_id);
        $clerk_code = clerk_code;

        if(!isset($_SESSION['action']))
        {
            $_SESSION['action'] = 'view';
        }

        $module = $anton->get_session('module');
        $sub_module = $anton->get_session('sub_module');
        $action = $anton->get_session('action');
//        print_r($module);


        // $bill_number = $MConfig->bill_number();
        $bill_number = $bill->billNumber();
        $billRef = "001".date('ymd',strtotime(today)).$bill_number.shift_no.mech_no;
        //$billRef = "001".date('ymd').$bill_number.mech_no;
        define('billRef',$billRef);
        $_SERVER['billRef'] = $billRef;
        define('bill_total',$bill->billTotal($bill_number,$today));
        define('bill_no',$bill_number);
        $anton->set_session(
            ["bill_no=$bill_number","bill_ref=$billRef","shift=$shift_no"]
        );
        $response = ['status' => 000,'message'=>'null'];
        $bill_condition = "`clerk` = '$myName' AND `bill_number` = '$bill_number' AND `trans_type` = 'i' and `date_added` = '$today'";

        // html header




    }




    // set core sessions
    /*
     * module = main mods
     * sub_module = sub mods
     * */
    $module = $anton->get_session('module');
