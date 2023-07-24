<?php
    require '../../includes/core.php';

    $response = array('code'=>0,'message'=>'ini');



    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $task = (new anton())->post('task');
        if($task === 'register'){

            // add new customer
            $email = $anton->post('email');
            $name = $anton->post('name');
            $mob = $anton->post('mobile');

            $response = $loyalty->cusReg($name,$email,$mob);

        }
        elseif ($task === 'get_customer'){
            // get customer
            $str = $anton->post('str');

            $response = $loyalty->getCus($str);
        }
    }

    header("Content-Type:Application/Json");
    echo json_encode($response);
