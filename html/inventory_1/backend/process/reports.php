<?php
    require '../includes/core.php';
    $req_method = $_SERVER['REQUEST_METHOD'];
    $response = ['status'=>000,'message'=>000];

    if($req_method === 'POST')
    {
        if(isset($_POST['function']))
        {

            $function = $anton->post('function');
            if($function === 'eod'){
                // TAKE EOD
                $clerk_code = $anton->post('clerk_code');
                $clerk_key = $anton->post('clerk_key');
                // admin authenticate
                $admin_auth = $auth->adminAuth($clerk_code,$clerk_key);
                if($admin_auth['code'] === 200)
                {
                    // access granted
                    $Reports->eod();

                } else {
                    echo $anton->json_enc($admin_auth);
                    // access denied
                }

            } elseif ($function === 'z_report'){
                // Z REPORT
                $clerk_code = $anton->post('clerk_code');
                $clerk_key = $anton->post('clerk_key');
                $recId = $anton->post('recId');
                // admin authenticate
                $admin_auth = $auth->adminAuth($clerk_code,$clerk_key);
                if($admin_auth['code'] === 200)
                {
                    // access granted
                    $zreport = $Reports->z_report($recId);
                    if($zreport['code'] === 202)
                    {
                        // print z details
                        printzreport($recId);
                    }

                } else {
                    echo $anton->json_enc($admin_auth);
                    // access denied
                }
            }

        }
    }