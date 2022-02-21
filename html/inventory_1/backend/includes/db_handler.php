<?php


class db_handler extends anton
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

    public function add_item_bill($bill_number,$barcode,$qty,$myName)
    {
        //get item details
        $machine_number = $this->machine_number();
        $clerk = $_SESSION['clerk_id'];

        // get item details
        $item = $this->get_rows('items_master',"`barcode` = '$barcode'");
        $item_desc = $item['desc'];
        $item_retail = $item['retail'];
        $bill_amt = $item_retail * $qty;
        $tax_group = $item['tax_grp'];


        // get tax rate
        $taxDetails = $this->get_rows('tax_master',"`id` = '$tax_group'");
        $rate = $taxDetails['rate'];
        $tax_description =$taxDetails['description'];
        if($taxDetails['rate'] < 1)
        {
            $taxAmount = 0.00;
        } else
        {
            // calculate for tax
            $taxAmount = $this->tax($rate,$bill_amt);
        }



        // add to bill in trans
        $sql = "insert into `bill_trans` 
                (`mach`,`clerk`,`bill_number`,`item_barcode`,
                 `item_desc`,`retail_price`,`item_qty`,`tax_amt`,
                 `bill_amt`,`trans_type`,`tax_grp`,`tax_rate`) value
                 ('$machine_number','$myName','$bill_number','$barcode',
                  '$item_desc','$item_retail','$qty','$taxAmount',
                  '$bill_amt','i','$tax_description','$rate')";
        if($this->db_connect()->exec($sql))
        {
            return true;
        }
        else
        {
            return false;
        }


    }

    public function sum($table,$column,$condition,$as = 'result')
    {
        $sql = $this->db_connect()->query("SELECT SUM($column) as $as FROM `$table` WHERE $condition");
        $stmt = $sql->fetch(PDO::FETCH_ASSOC);
        return $stmt["$as"];
    }


    public function uniqieStr(string $table, string $column, int $length)
    {
        $unique = $this->generateRandomString($length);

        if($this->row_count("$table","$column = '$unique'") > 0)
        {
            // repeat function
            $this->uniqieStr($table,$column,$length);
        }

        return $unique;


    }

    public function delete($table,$condition = 'none'): bool
    {
        if($condition === 'none')
        {
            $sql = "DELETE FROM $table";
        }
        else
        {
            $sql = "DELETE FROM $table WHERE $condition";
        }

        if($this->db_connect()->exec($sql))
        {
            return true;
        } else
        {
            return false;
        }
    }

}