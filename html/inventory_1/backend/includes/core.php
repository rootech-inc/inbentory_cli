<?php

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);

    require 'session.php';
    $session_id = session_id();

    // initialize classes
    require 'anton.php';
    require 'db_handler.php';
    $anton = new anton();
    $db = new db_handler();
    $today = date('Y-m-d');
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

        // check my bill
        $bill_num_sql = $db->db_connect()->query("SELECT * FROM `bill_trans` WHERE `trans_type` != 'i' AND `clerk` = '$myName' AND `date_added` = '$today' order by id DESC LIMIT 1");

        $anton->not_session('bill_number',1);
        $anton->not_session('cart','empty');

        $bill_num = $anton->get_session('bill_number');

        if($bill_num_sql->rowCount() > 0 )
        {
            $bill_num_res = $bill_num_sql->fetch(PDO::FETCH_ASSOC);

            $bill_number = $bill_num_res['bill_number'] + 1;
        }
        else
        {
            $bill_number = 1;
        }

        $bill_number = $anton->get_session('bill_number');


    }




    // set core sessions
    /*
     * module = main mods
     * sub_module = sub mods
     * */
    $module = $anton->get_session('module');
