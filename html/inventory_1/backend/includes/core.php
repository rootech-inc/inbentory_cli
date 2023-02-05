<?php

use db_handeer\db_handler;

ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    ini_set('memory_limit', '-1');
    error_reporting(E_ALL);
    define('root',$_SERVER['DOCUMENT_ROOT']);
    define('host_ip',$_SERVER['HTTP_HOST']);

    $bill_number = 0;

    const db_host = 'localhost';
    const db_user = 'anton';
    const db_password = '258963';
    const db_name = "posdb";


    require 'session.php';
    $session_id = session_id();
    $logo = root . "/assets/logo/comp_logo.png";
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

    $bill = new \billing\Billing();


    $anton = new anton();
    $db = new db_handler();
    $db->db_connect();
    $taxCalc = new tax_calculator();
    $MConfig = new \mechconfig\MechConfig();
    $company = $db->get_rows('company',"`id` = 0");
    // validate machine
    define('mech_no',$MConfig->mech_details()['mechine_number']);
    $machine_number = mech_no;
    if($shiftCL->is_shift(mech_no))
    {
        $shit_detail = (new db_handler())->get_rows('shifts',"`mech_no` = '$machine_number'");
        $today = $shit_detail['shift_date'];
    } else {
        $today = date('Y-m-d');
    }


    define('today',$today);
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
        $clerk_code = clerk_code;

        if(!isset($_SESSION['action']))
        {
            $_SESSION['action'] = 'view';
        }

        $module = $anton->get_session('module');
        $sub_module = $anton->get_session('sub_module');
        $action = $anton->get_session('action');
//        print_r($module);


        $bill_number = $MConfig->bill_number();
        $bill_number = $bill->billNumber();
        define('bill_total',$bill->billTotal($bill_number,$today));
        define('bill_no',$bill_number);
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
