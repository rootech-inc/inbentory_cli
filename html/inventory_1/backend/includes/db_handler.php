<?php

namespace db_handeer;

use PDO;
use PDOException;

class db_handler
{


    public function db_connect() // connect to database
    {
        //set DSN
        $dns = 'mysql:host='.db_host.';dbname='.db_name;

        //create pdo instance
        try {

            $pdo = new PDO($dns, db_user, db_password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $pdo;
        } catch (PDOException $err)
        {
            (new \anton)->error_handler('Database Error',$err->getMessage());
            return false;
        }
    }




    function row_count($table,$condition='none'): int // row count of a table
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

    function col_sum($table,$column,$condition='none') // get sum of column
    {
          if($condition === 'none')
          {
              $sql = "SELECT SUM($column) as summed from $table";
          } else
          {
              $sql = "SELECT SUM($column) as summed from $table WHERE $condition";
          }
//          echo $sql;
          $sum_query =   $this->db_connect()->query($sql);
          $sum_res = $sum_query->fetch(PDO::FETCH_ASSOC);
          return $sum_res['summed'];
    }

    function get_rows($table, $condition, $result = 'array') // get rows from table
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



        if($result === 'array')
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        elseif ($result === 'json')
        {
            header('Content-Type: application/json');
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($res);
        }
        else
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }



    }

    function fetch_rows($query, $result = 'array') // get rows from table
    {

        $stmt = $this->db_connect()->query($query);

        if($result === 'array')
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        elseif ($result === 'json')
        {
            header('Content-Type: application/json');
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($res);
        }
        else
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }



    }

    public function add_item_bill($bill_number,$barcode,$qty,$myName)
    {
        //get item details
        $machine_number = mech_no;
        $clerk = $_SESSION['clerk_id'];

        // get item details
        $item = $this->get_rows('prod_mast',"`barcode` = '$barcode'");
        $item_desc = $item['desc'];
        $item_retail = $item['retail'];
        $disc = $item['discount'];
//        echo $disc;


        if($item['discount'] == '1')
        {
            // calculate discount rate off

            //$retail_p = $item_retail;
            $discount_rate = $item['discount_rate'];
            $retail_p = $item_retail - (new \anton())->percentage($discount_rate,$item_retail);
        }
        else
        {
            $retail_p = $item_retail;
        }

        $bill_amt = $retail_p * $qty;
        $tax_group = $item['tax_grp'];


        // get tax rate
        $taxDetails = $this->get_rows('tax_master',"`id` = '$tax_group'");
        $rate = $taxDetails['rate'];
        $tax_description =$taxDetails['description'];
        if($taxDetails['rate'] < 1)
        {
            $taxAmount = 0.00;
        }
        else
        {
            // calculate for tax
            $taxAmount = (new \anton)->tax($rate,$bill_amt);
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
        $unique = (new \anton)->generateRandomString($length);

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

    public function clerkAuth($clerk_code,$key) :bool
    {
        if($this->row_count('clerk',"`clerk_code` = '$clerk_code'") > 0)
        {
            // compare password
            $clerk = $this->get_rows('clerk',"`clerk_code` = '$clerk_code'");
            $db_key = $clerk['clerk_key'];


            // compare
            if((new \anton)->compare(md5($key),$db_key))
            {
                // login pass
                return true;
            }
            else
            {
                // wrong password
                return false;
            }
        }
        else
        {
            // no user
            return false;
        }
    }

    public function discount($bill_condition,$disc_condition)
    {
        $_SESSION['sub_total'] = 0;
        $_SESSION['disc_value'] = 0;
        $disc_rate = $this->get_rows('bill_trans',"$disc_condition")['bill_amt'];
        // calculate sub-total with discount
        $items = $this->db_connect()->query("SELECT * FROM `bill_trans` WHERE $bill_condition");
        while($it = $items->fetch(PDO::FETCH_ASSOC))
        {
            $item_barcode = $it['item_barcode'];
            $item = $this->get_rows('prod_mast',"`barcode` = '$item_barcode'");

            if($item['discount'] === 1)
            {
                //dont  apply discount
                $s_toal = $it['bill_amt'];
            }
            else
            {
                //apply discount
                $b_amt = $it['bill_amt'];
                $_SESSION['disc_value'] += (new \anton)->percentage($disc_rate,$b_amt);
                $s_toal = $b_amt - (new \anton)->percentage($disc_rate,$b_amt);
            }

            $s = $_SESSION['sub_total'];
            $_SESSION['sub_total'] = $s + $s_toal;

        }

        $sub_total = $_SESSION['sub_total'];
        $dis_value = $_SESSION['disc_value'];
        unset($_SESSION['sub_total']);
        return $dis_value;
    }

    // update
    function update_record($details)
    {
        $error = '0';

        if(!is_array($details))
        {
            $error = '1';
            return (new \anton)->err("Can't Continue");
        }


        $table = $details['table'];
        $columns = $details['columns'];
        $set = '';
        $values = $details['values'];
        $cond = $details['condition'];


        // validate cols and vals
        if(!is_array($columns) && !is_array($values))
        {
            return (new \anton)->err("Columns or Values not an array");
            
        }

        // validate cols and vals length
        if(count($columns) !== count($values))
        {
           return (new \anton)->err("Columns and Value Miss match");

        }
        $cols_count = count($details);

        //die('_'.count($columns,''));

        // iterate to match vals and colums
        foreach ($columns as $key => $value)
        {

            $col = $columns[$key];
            $val = $values[$key];


            $set .= "`$col` = \"$val\",";

        }
        $sen_set = rtrim($set,',');

        if($cond === 'none')
        {
            $sql = "UPDATE `$table` SET $sen_set";
        } else
        {
            $sql = "UPDATE `$table` SET $sen_set WHERE $cond";
        }



        if($error === '0')
        {
            //echo $sql;
            try {
                $this->db_connect()->exec($sql);
                return true;
            } catch (PDOException $e)
            {

                $message = $e->getMessage();
                $error_show = "Message : $message, Query : $sql";
                (new \anton)->err($error_show);
//                return $e->getMessage();
                return false;
            }
        }
        elseif ($error === '1')
        {
            (new \anton)->err("Error saving PO, Contact System Administrator");
            return false;
        }

    }

    ### GRN FUNCTIONS ###

    function grn_list_item($query_details,$supplier)
    {
        if(strlen($query_details) < 1)
        {
            // return error
            return (new \anton)->return_error();

        } else {
            // search for item and return result as array
            $sql = "SELECT * FROM `prod_master` WHERE `item_code` like '%$query_details%' OR item_desc like '%$query_details%' OR barcode like '%$query_details%' AND supplier = '$supplier'";
//            echo $sql;
            $query =  $this->db_connect()->query($sql);
            $rows = $this->row_count('prod_master',"`item_code` like '%$query_details%' OR item_desc like '%$query_details%' OR barcode like '%$query_details%' AND supplier = '$supplier'");
            if($rows > 0 )
            {
                header('Content-Type: application/json');
                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                return json_encode($res);
            }
        }
    }

    ### GRN FUNCTIONS ###

    // unlock table
    function unlock($table)
    {
        $this->db_connect()->exec("UNLOCK TABLES");
    }
    // lock table
    function lock_write($table)
    {
        $this->db_connect()->exec("TABLES TABLE `$table`");
    }

    // add doc transactions
    function doc_trans($document,$entry_no,$function): bool
    {
        @!session_start();
        $owner = (new \anton)->get_session('clerk_id');
        if(!empty($document) && !empty($entry_no) && !empty($function) && !empty($owner))
        {
            $this->db_connect()->query("INSERT INTO `doc_trans` (`doc_type`,`entry_no`,`trans_func`,`created_by`) VALUES ('$document','$entry_no','$function','$owner')");
            return true;
        } else
        {
            return false;
        }

    }


    // get user screen access
    function screen_access($screen_id){
        //check if screen exist
        $screen_exist =  $this->row_count('screens',"`scr_uni` = '$screen_id'");

        if($screen_exist == 1)
        {
            // check if user has screen
            $user = $_SESSION['clerk_id'];

            if($this->row_count('clerk',"`id` = '$user'") == 1)
            {
                $user_details = $this->get_rows('clerk',"`id` = '$user'");
                $user_group = $user_details['user_grp'];

                $q = "select * from screens join user_access ug on screens.id = ug.screen where ug.`group` = '$user_group';";
                $query = $this->db_connect()->query($q);

                if($query->rowCount() == 1)
                {
                    //screen access
                    $result = $query->fetch(PDO::FETCH_ASSOC);
                    $page_read = $result['read'];
                    $page_write = $result['write'];
                    $page_print = $result['print'];

                    // todo create page permissions json format and return it


                } else
                {
                    (new \anton)->error_handler("Screen Error","No Group Access $q");
                    die();
                }


            }
            else
            {
                (new \anton)->error_handler("Screen Error",'User does not exist');
                die();
            }


        } else {
            // no screen
            (new \anton)->error_handler("No Screen",'Screen Not Found');
            die();
        }
    }

}