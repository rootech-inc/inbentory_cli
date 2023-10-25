<?php

use db_handeer\db_handler;
use mechconfig\MechConfig;

require '../includes/core.php';

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $function = $_POST['evfunc'];

    if($function === 'invoice')
    {
        // make invoice
        $billRef = '';
        $response = $evat->send_invoice($billRef);

        echo $response;

    }
    elseif ($function === 'sign_invoice'){
        // sign invoice
        $num = $_POST['num'];
        $response = $evat->sign_invoice($num);
        $resp_encode = json_decode($response);
        $status = $resp_encode->STATUS;

        if($status === 'SUCCESS'){
            // SAVE IN BILL MORE
        }

        echo $response;
    }
    else {

        echo 'NO CALL OR WHATEVER';

    }

}
