<?php

use db_handeer\db_handler;
use taxer\tax_calculator;

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    ini_set('memory_limit', '-1');
    
    error_reporting(E_ALL ^ E_DEPRECATED);

    define('root',$_SERVER['DOCUMENT_ROOT']);
    define('host_ip',$_SERVER['HTTP_HOST']);


    $bill_number = 0;


    $phy = $_SERVER['DOCUMENT_ROOT'];
    require 'session.php';
    $session_id = session_id();
    $logo = $phy . "\assets\logo\xt.png";



    // initialize classes
    require 'anton.php';
    require 'db_handler.php';
    require 'tax_calculator.php';
    require 'MechConfig.php';

    $MConfig = new \mechconfig\MechConfig();
    $MConfig->validate_device();
    $config = $MConfig->config();

    define('DEBUG',$config['DEBUG']);
    define('MACH_NO',$config['MACH_NO']);
    define('MAC_ADDRESS',$config['MAC_ADDRESS']);
    define('bill_print',$config['BILL_PRINT']);
    define('LOC_ID',$config['LOC_ID']);

    define('logo',$logo);
    define("printer", $config['PRINTER']);
    define('PRINT_TYPE',$config['PRINT_TYPE']);
    define('db_host',$config['DB_HOST']);
    define('db_name',$config['DB_NAME']);
    define('db_user',$config['DB_USER']);
    define('db_password',$config['DB_PASSWORD']);

    define('evat',$config['EVAT']);
    define('evat_url',$config['EVAT_API']);

    define('loyalty_url',$config['LTY_URL']);
    define('loyalty_token',$config['LOY_TOKEN']);

    require 'classes/Billing.php';
    require 'classes/reports.php';
    require  'classes/auth.php';
    require 'classes/shift.php';
    require  'classes/Evat.php';
    require  'classes/Loyalty.php';
    require 'classes/ProductMaster.php';



    $bill = new \billing\Billing();


    $anton = new anton();
    $db = new db_handler();


    $taxCalc = new tax_calculator();



    $company = $db->get_rows('company',"`id` = 0");
    // validate machine
    define('mech_no',$config['MACH_NO']);

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
    define('is_shift',$shiftCL->is_shift(mech_no));
    





    define('today',$today);

    define('shift_enc',$shift_enc);
    $current_time = date("Y-m-d H:m:s");



    $root_host = root;
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



        // html header




    }




    // set core sessions
    /*
     * module = main mods
     * sub_module = sub mods
     * */
    $module = $anton->get_session('module');
