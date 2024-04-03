<?php




    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {


        ##login
        if (isset($_POST['clerk_code']))
        {

            $clerk_code = htmlspecialchars($_POST['clerk_code']);
            $clerk_key = htmlspecialchars($_POST['clerk_key']);
            $state = htmlentities($_POST['db_state']);
            $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config.ini', true);

            if($state === 'NETWORK'){
                $config['system_config']['DB_SOURCE'] = 'NETWORK';
            } else {
                $config['system_config']['DB_SOURCE'] = 'LOCAL';
            }

            // Save the modified array back to the INI file
            $iniContent = '';

            foreach ($config as $section => $values) {
                $iniContent .= "[$section]\n";
                foreach ($values as $key => $value) {
                    $iniContent .= "$key = $value\n";
                }
                $iniContent .= "\n";
            }

            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/config.ini', $iniContent);

            require '../includes/core.php';

            if($db->row_count('clerk',"`clerk_code` = '$clerk_code'") > 0)
            {
                // get user details
                $clerk_details = $db->get_rows('clerk',"`clerk_code` = '$clerk_code'");
                $clerk_db_key = $clerk_details['clerk_key'];

                if($anton->compare(md5($clerk_key),$clerk_db_key))
                {

                    $id = $clerk_details['id'];
                    $session_id = md5($clerk_code.$clerk_key.$clerk_db_key.date("Y-m-d H:i:s"));
                    $anton->set_session(['cli_login=true',"clerk_id=$id",'module=home']);
                    $anton->done();

                }
                else
                {

                    $anton->err("Wrong Password");

                }


            }
            else
            {
                $anton->err("User name not found");
            }

            die();



            //check if clerk exist
            if (row_count("clerk" , "`clerk_code` = '$clerk_code'", database_connect($db_host, $db_user, $db_password, "SMHOS")) < 1)
            {
                error("clerk does not exist");
            }
            else
            {
                //echo "clerk exist";
                $clerk_details = get_row("clerk" , "`clerk_code` = '$clerk_code'", database_connect($db_host, $db_user, $db_password, "SMHOS"));

                $clerk_db_key = $clerk_details['clerk_key'];
                //compare keys
                if (compare_two_strings(md5($clerk_key) , $clerk_db_key))
                {




                    //start sessions
                    $session_id = md5($clerk_code.$clerk_key.$clerk_db_key.date("Y-m-d H:i:s"));
                    session_id($session_id);
                    session_start();
                    $_SESSION['state'] = $state;
                    $_SESSION['cli_login'] = true;
                    $_SESSION['clerk_id'] = $clerk_details['id'];
                    $_SESSION['view'] = 'welcome';
                    //br($state);

                    $url_with_token = 'http://cli.localhost/?token='.session_id();
                    echo $url_with_token;

                    //header("Location:".$url_with_token);
                    die();


                    exit();


                }

                else
                {
                    set_session('clerk_code', $clerk_code);
                    error("Wrong key combination");
                    echo "Wrong Password";
                }
            }
            gb();
        }

        ## login with pin
        if(isset($_POST['pin'])){
            require '../includes/core.php';
            $pin = $anton->post('pin');
            $db = (new \db_handeer\db_handler());
            $response = array(
                'status_code'=>0,'message'=>'none'
            );

            $r = $db->row_count('clerk',"`pin` = '$pin'");
            ## check if pin exist
            if($db->row_count('clerk',"`pin` = '$pin'") === 1){
                // login
                $account = $db->get_rows('clerk',"`pin` = '$pin'");

                $id = $account['id'];
                $clerk_code = $account['clerk_code'];
                $clerk_db_key = $account['clerk_key'];
                $session_id = md5($clerk_code.$clerk_db_key.date("Y-m-d H:i:s"));
                $anton->set_session(['cli_login=true',"clerk_id=$id",'module=home']);


                $response['status_code'] = 200;
                $response['message'] = "Login Successful";
            } else {
                $response['status_code'] = 404;
                $response['message'] = "Invalid Pin ($pin) $r " . $db->db_host;
            }

            echo json_encode($response);
        }

        ## master authenticate
        if(isset($_POST['master_auth']) && isset($_GET['mod']))
        {
            require '../inc/core.php';
            $name = htmlentities($_POST['username']);
            $password = htmlentities($_POST['password']);

            //check if user exist
            if(row_count('users', "`username` = '$name'", $route) > 0)
            {
                // get user group
                $user = get_row('users', "`username`='$name'",$route);
                $password_db = $user['password'];

                // compare password
                if(password_verify($password, $password_db))
                {
                    echo 'valid password';
                    //change session to mod
                    $_SESSION['view'] = $_GET['mod'];
                    $_SESSION['main'] = 'company_setup';
                    $_SESSION['sub'] = 'tax';
                    $_SESSION['document_state'] = 'view';
                }
                else
                {
                    echo 'invalid password';
                    error('Invalid password for user');
                }
            }
            else
            {
                echo 'user does not exist';
                error('User does not exist');

            }

            gb();
        }

        // logout
        if(isset($_POST['function']))
        {
            require '../includes/core.php';
            $function = $anton->post('function');

            if($function === 'logout')
            {
                $files = glob(tmpdir . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                $_SESSION = array();
                session_destroy();
            }
        }
    }
