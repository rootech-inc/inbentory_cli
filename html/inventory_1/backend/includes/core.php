<?php

use db_handeer\db_handler;

ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
    define('root',$_SERVER['DOCUMENT_ROOT']);
    define('host_ip',$_SERVER['HTTP_HOST']);

    $bill_number = 0;

    const db_host = '172.25.192.1';
    const db_user = 'anton';
    const db_password = '258963';
    const db_name = "SMHOS";

    require 'session.php';
    $session_id = session_id();

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
    // validate machine


    $today = date('Y-m-d');
    define('today',$today);
    $current_time = date("Y-m-d H:m:s");
    define('mech_no',$MConfig->mech_details()['mechine_number']);
    define('doc_root',$_SERVER['DOCUMENT_ROOT']);
    $machine_number = mech_no;
    $root_host = $_SERVER['DOCUMENT_ROOT'];
    $shift = $db->shift(mech_no);
    define('shift',$shift);





    if(isset($_SESSION['cli_login']) && $_SESSION['cli_login'] === 'true')
    {
        $session_id = session_id();
        $clerk_id = $anton->get_session('clerk_id');
        $my = $db->get_rows('clerk',"`id` = '$clerk_id'");
        $myName = $my['clerk_name'];

        define('clerk_code',$my['clerk_code']);
        define('clerk_name',$my['clerk_name']);

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
