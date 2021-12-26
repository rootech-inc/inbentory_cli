<?php


    require '../includes/core.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {


        ##login
        if (isset($_POST['clerk_code']))
        {

            $clerk_code = htmlspecialchars($_POST['clerk_code']);
            $clerk_key = htmlspecialchars($_POST['clerk_key']);
            $state = htmlentities($_POST['db_state']);

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
            $function = $anton->post('function');

            if($function === 'logout')
            {
                $_SESSION = array();
                session_destroy();
            }
        }
    }
