<?php
require '../../includes/core.php';

$response = ['code'=>'000','message'=>'null'];

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if(isset($_POST['function'])){
        $function  = $anton->post('function');

        if($function === 'start_shift') {
            // start shift
            $shiftCL->start_shift();

        }
        elseif ($function === 'new_user') {
            // add new clerk
            $full_name = $anton->post('full_name');
            $grp = $anton->post('grp');
            echo json_encode($auth->newUser($full_name,$grp));
        }
        elseif ($function === 'get_tax_val'){
            // tax calc
            $tax_code = $anton->post('tax_code');
            $amount = $anton->post('amount');
//            print_r($_POST);
            $tax = $anton->tax($tax_code,$amount);
            echo json_encode($tax);
        }

    }
}