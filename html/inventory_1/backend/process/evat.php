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
        $response = $evat->invoice(1,1,today);
        header('Content-Type: application/json');
        echo json_encode($response);

    } else {

        echo 'NO CALL OR WHATEVER';

    }

}
