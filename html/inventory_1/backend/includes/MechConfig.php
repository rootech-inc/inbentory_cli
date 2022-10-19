<?php

namespace mechconfig;

use JetBrains\PhpStorm\NoReturn;
use PDO;
use PDOException;
use anton;
use db_handeer\db_handler;

class MechConfig
{




    public function mech_db()
    {

        try {
            $l_route = '';
            $local_sqlite = root.'/backend/includes/database/phpsqlite.db';

            return new PDO("sqlite:$local_sqlite");
        } catch (PDOException $err)
        {

            (new \anton)->error_handler('Mech Setup','Cannot connect to mechine db');
        }

    }

    public function mech_details()
    {
        $sql = $this->mech_db()->query("SELECT * FROM machine_config LIMIT  1");

        $valid_sql = $this->mech_db()->query("SELECT count('mechine_number') as 'mn' from machine_config");
        $valid_stmt = $valid_sql->fetch(PDO::FETCH_ASSOC);
        $valid = $valid_stmt['mn'];

        if(intval($valid) !== 1)
        {
            // add machine
            return array('mechine_number'=>'0');
        }
        else
        {
            return $sql->fetch(PDO::FETCH_ASSOC);
        }


    }

    public function validate_device()
    {

        $valid_sql = $this->mech_db()->query("SELECT count('mechine_number') as 'mn' from machine_config");
        $valid_stmt = $valid_sql->fetch(PDO::FETCH_ASSOC);
        $valid = $valid_stmt['mn'];

        if(intval($valid) !== 1)
        {
            // add machine
            require root."/backend/includes/parts/add-ons/add_machine.php";
            die();
        }

    }

    public function ini_device($number,$mac_addr,$desc)
    {
        $q = $this->mech_db();
        $q->execute("INSERT INTO machine_config (mechine_number, discr, mac_addr) value ($number,'$desc','$mac_addr'");
    }




}