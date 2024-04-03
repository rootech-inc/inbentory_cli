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

    public function newUser(string $full_name,$grp): array
    {
        $clerk_code = $this->uniqieStr('clerk','clerk_code','4');
        $clerk_key_str = rand(1111,9999);
        $token_pin = rand(1111,9999);
        $clerk_key_md5 = md5($clerk_key_str);

        try {
            $sql = "INSERT INTO clerk (clerk_code, clerk_key, clerk_name, user_grp,token,pin) values 
                    ('$clerk_code','$clerk_key_md5','$full_name','$grp','$token_pin','$token_pin')" ;
            $stmt = $this->db_connect()->prepare($sql);
            $stmt->execute();
            $this->response['code'] = 202;
            $this->response['message'] = ['clerk_code'=>$clerk_code,'clerk_key'=>$clerk_key_str,'token'=>$token_pin];

        } catch (PDOException $e){
            $this->response['code'] = '505'; $this->response['message'] = $e->getMessage();
        }

        return $this->response;

    }

}

$auth = new auth();
