<?php

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
    define('root',$_SERVER['DOCUMENT_ROOT']);
    define('host_ip',$_SERVER['SERVER_ADDR']);

    const db_host = '172.29.0.1';
    const db_user = 'anton';
    const db_password = '258963';
    const db_name = "SMHOS";

    require 'session.php';
    $session_id = session_id();

    // initialize classes
    require 'anton.php';
    require 'db_handler.php';
    require 'tax_calculator.php';
    $anton = new anton();
    $db = new db_handler();
    $db->db_connect();
    $taxCalc = new tax_calculator();

    $today = date('Y-m-d');
    $current_time = date("Y-m-d H:m:s");
    $machine_number = $db->machine_number();
    $root_host = $_SERVER['DOCUMENT_ROOT'];
//    die($root_host);
//    $anton->done($today);
//    die();

    if(isset($_SESSION['cli_login']) && $_SESSION['cli_login'] === 'true')
    {
        $session_id = session_id();
        $clerk_id = $anton->get_session('clerk_id');
        $my = $db->get_rows('clerk',"`id` = '$clerk_id'");
        $myName = $my['clerk_name'];

        if(!isset($_SESSION['action']))
        {
            $_SESSION['action'] = 'view';
        }

        $module = $anton->get_session('module');
        $sub_module = $anton->get_session('sub_module');
        $action = $anton->get_session('action');
//        print_r($module);


        $bill_number = $db->row_count('bill_header',"`mach_no` = '$machine_number' and `bill_date` = '$today'") + 1;



    }




    // set core sessions
    /*
     * module = main mods
     * sub_module = sub mods
     * */
    $module = $anton->get_session('module');
