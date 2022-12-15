<?php

namespace billing;

use db_handeer\db_handler;
use PDO;
use PDOException;

class auth extends db_handler
{
    private $connect;
    private array $response = ['code'=>000,'message'=>'Initialization'];

    public function __construct()
    {
        //set DSN
        $dns = 'mysql:host='.db_host.';dbname='.db_name;

        //create pdo instance
        try {

            $pdo = new PDO($dns, db_user, db_password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->connect =  $pdo;
        } catch (PDOException $err)
        {
            $this->connect = false;
//            (new \anton)->error_handler('Database Error',$err->getMessage());

        }
    }

    public function adminAuth($code,$clerk_key): array
    {

        if($this->row_count('clerk',"`clerk_code` = '$code'") === 1) {
            // there is clerk
            $clerk = $this->get_rows('clerk',"`clerk_code` = '$code'");
            if($clerk['user_grp'] === 1)
            {

                if($clerk['clerk_key'] === md5($clerk_key))
                {
                    $this->response['code'] = 200;
                    $this->response['message'] = 'ACCESS GRANTED';

                } else {
                    $this->response['code'] = 505;
                    $this->response['message'] = 'Wrong Password';
                }


            } else {
                $this->response['code'] = 403;
                $this->response['message'] = 'No Authorization For Account';
            }
        } else {
            // no clerk
            $this->response['code'] = 404;
            $this->response['message'] = 'User Does Not Exists';
        }
        return $this->response;
    }

}

$auth = new auth();
