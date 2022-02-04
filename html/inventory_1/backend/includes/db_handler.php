<?php


class db_handler
{


    public function db_connect() // connect to database
    {
        $host = "127.0.0.1";
        $user = "root";
        $password = "Sunderland@411";
        $db = "SMHOS";
        //set DSN
        $dns = 'mysql:host='.$host.';dbname='.$db;

        //create pdo instanse
        $pdo = new PDO($dns,$user,$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }

    public function db_sqlite() // connect to sqlite
    {

        $l_route = '';
        $local_sqlite = '/home/stuffs/Development/PHP/inbentory_cli/html/inventory_1/backend/includes/database/phpsqlite.db';
        return new PDO("sqlite:$local_sqlite");
    }

    public function machine_details() // get machine details
    {
        $machine_detail = $this->db_sqlite()->query("select * from machine_config");
        return $machine_detail->fetch(PDO::FETCH_ASSOC);
    }
    public function machine_number() // machine number
    {
       return $this->machine_details()['machine_number'];
    }

    function row_count($table,$condition='none') // row count of a table
    {

        if($condition === 'none')
        {
            $sql = $this->db_connect()->query("SELECT * FROM $table");
        }
        else
        {
            $sql = $this->db_connect()->query("SELECT * FROM $table WHERE $condition");
        }

        return $sql->rowCount();
    }

    function get_rows($table, $condition) // get rows from table
    {
        if($condition === 'none')
        {
            $sql = "SELECT * FROM $table";
        }
        else
        {
            $sql = "SELECT * FROM $table WHERE $condition";
        }
        $stmt = $this->db_connect()->query($sql);


        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res;


    }

    public function add_item_bill($item,$quantity,$clerk='0',$bill_number)
    {
        //get item details
        $machine_number = $this->machine_number();
        // add to bill in trans
        $this->db_connect()->exec(
            "insert into `bill_trans` (`mach`,`bill_number`,`item`,`trans_type`,`amount`,`clerk`) values ('$machine_number','$bill_number','$item','i','$quantity','$clerk')"
        );
    }

}