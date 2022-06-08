<?php

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);

    require 'session.php';
    $session_id = session_id();

    // initialize classes
    require 'anton.php';
    require 'db_handler.php';
    require 'tax_calculator.php';
    $anton = new anton();
    $db = new db_handler();
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


        if($module === 'billing' || $module === 'home')
        {
            // check my bill
            //$bill_num_sql = $db->db_connect()->query("SELECT * FROM `bill_trans` WHERE `trans_type` != 'i' OR `trans_type` != 'D' AND `clerk` = '$myName' AND `date_added` = '$today' order by id DESC LIMIT 1");
            $bill_num_sql = $db->db_connect()->query("SELECT * FROM `bill_trans` WHERE `trans_type` = 'P' or `trans_type` = 'C' AND `clerk` = '$myName' AND `date_added` = '$today' order by id DESC LIMIT 1");
            if($bill_num_sql->rowCount() > 0 )
            {
                $bill_num_res = $bill_num_sql->fetch(PDO::FETCH_ASSOC);

                $bill_number = $bill_num_res['bill_number'] + 1;
            }
            else
            {
                $bill_number = 1;
            }
        }
        elseif ($module === 'inventory')
        {

        }



    }




    // set core sessions
    /*
     * module = main mods
     * sub_module = sub mods
     * */
    $module = $anton->get_session('module');
