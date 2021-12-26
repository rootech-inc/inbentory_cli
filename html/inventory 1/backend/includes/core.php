<?php
    require 'session.php';
    $session_id = session_id();

    // initialize classes
    require 'anton.php';
    require 'db_handler.php';
    $anton = new anton();
    $db = new db_handler();
    $today = date('Y-m-d');
    $machine_number = $db->machine_number();

    if(isset($_SESSION['cli_login']) && $_SESSION['cli_login'] === 'true')
    {
        $session_id = session_id();
        $clerk_id = $anton->get_session('clerk_id');
        $my = $db->get_rows('clerk',"`id` = '$clerk_id'");
        $myName = $my['clerk_name'];

        // check my bill
        if($db->row_count('bill_trans',"`trans_type` = 's' AND `mach` = '$machine_number'") > 0)
        {
            // get last bill number
            $bill_number = $db->get_rows(
                'bill_trans',
                "`trans_type` = 's' AND `mach` = '$machine_number' ORDER BY `id` DESC LIMIT 1"
            ) + 1;
        }
        else
        {
            $bill_number = 1;
        }


    }




    // set core sessions
    /*
     * module = main mods
     * sub_module = sub mods
     * */
    $module = $anton->get_session('module');
