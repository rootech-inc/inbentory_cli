<?php

    require '../includes/core.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_POST['function']))
        {
            $function = $anton->post('function');
            if($function === 'set_session')
            {
                // get form data
                $session_data = $anton->post('session_data');
                print_r($session_data);
                // TODO Setting Session
            }
        }
    }
