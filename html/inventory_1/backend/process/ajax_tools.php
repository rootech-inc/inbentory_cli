<?php

    require '../includes/core.php';


    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_POST['function']))
        {
            $function = $anton->post('function');
            if($function === 'set_session')
            {
                // get form data
                $session_data = $_POST['session_data'];
                print_r($session_data);
                $anton->set_session($session_data);
            }

            elseif ($function === 'mech_ini')
            {
                $desc = $anton->post('desc');
                $mac_addr = $anton->post('mac_addr');
                $mech_no = $anton->post('mech_no');
                $ip = $anton->myIp();

                $mysql_ins = "INSERT INTO mech_setup (mech_no, descr, mac_addr) values ($mech_no,'$desc','$mac_addr') ";
                $sqlite_ins = "INSERT INTO machine_config (mechine_number, discr, mac_addr) value ($mech_no,'$desc','$mac_addr') ";

                try {
                    (new \db_handeer\db_handler())->db_connect()->query($mysql_ins);
                    (new \mechconfig\MechConfig())->ini_device($mech_no,$mac_addr,$desc);

                    $anton->done("reload");
                } catch (PDOException $e)
                {
                    (new \db_handeer\db_handler())->db_connect()->query("DELETE FROM mech_setup where mech_no = $mech_no");
                    (new \mechconfig\MechConfig())->mech_db()->query('DELETE FROM machine_config');

                    $anton->err("Could Not initialize machine");

                }


            }

            elseif ($function === 'unset_session')
            {
                // get form data
                $session_data = $_POST['sess_var'];
//                print_r($session_data);
                $anton->unset_session($session_data);

            }

            elseif ($function === 'get_session')
            {
                $sess_var = $anton->post('sess_var');
                echo $anton->get_session("$sess_var");
            }

            elseif ($function === 'row_count') // count rows
            {
                $table = $anton->post('table');
                $condition = $_POST['condition'];

                echo $db->row_count("$table","$condition");

            }

            elseif ($function === 'query')
            {
                $response = array('code'=>1,'message'=>'ini');
                $query = $_POST['query'];
                echo($query);
                try {
                    $db->exe($query);
                    $response['code'] = 202;
                    $response['message'] = "Query Executed Successfully";
                } catch (Exception $e){
                    $response['code'] = 505;
                    $response['message'] = $e->getMessage();
                }

                header("Content-Type:Application/Json");
                echo json_encode($response);

            }

            elseif ($function === 'get_row')
            {
                $table = $anton->post('table');
                $condition = $_POST['condition'];
                $res =  $db->get_rows($table,$condition,'json');
                print_r($res);
            }

            elseif ($function === 'fetch_rows')
            {
                $table = $anton->post('table');
                $query = $_POST['query'];
                $res =  $db->fetch_rows($query,'json');
                print_r($res);
            }

            elseif ($function === 'return_rows') // return row
            {
                $query = $_POST['query'];
                $stmt = $db->db_connect()->query($query);
                header('Content-Type: application/json');
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($res);
            }

            elseif ($function === 'insert') // insert into table
            {
                $query = $_POST['query'];
//                echo($query);
                try {
                    $db->db_connect()->exec($query);
                    echo '1';
                } catch (PDOException $e){
                    echo $e->getMessage();
                }
            }

            elseif ($function === 'get_input_tax')
            {

                $invoice_value = $anton->post('invoice_value');
                $tax_class = $anton->post('tax_class');
                $tax_trigger = $tax_class."($invoice_value)";
                $tax_return = $taxCalc->tax_input($invoice_value,$tax_class);

                echo $tax_return;
            }

            elseif ($function === 'getUser') // get user details
            {
                $id = $anton->post('id');
                $res = $db->get_rows('clerk',"`id` = $id",'json');
                print_r($res);
            }

            elseif ($function === 'doc_trans') // document transaction
            {
                $doc = $anton->post('doc');
                $func = $anton->post('func');
                $entry_no = $anton->post('entry_no');
                $db->doc_trans($doc,$entry_no,$func);
            }

            elseif ($function === 'make_md5')
            {
                $str = $anton->post('str');
                echo md5($str);
            }

            elseif ($function === 'isHold')
            {
               echo $hold_count = $MConfig->lite_row_count('hold_hd','entry_no','`id` > 0');

            }

            elseif ($function === 'adminAuth'){
                $code = $anton->post('code');
                $clerk_key = $anton->post('clerk_key');
                header('Content-Type: application/json');
                echo json_encode($auth->adminAuth($code,$clerk_key));
            }
            elseif ($function === 'this_mech')
            {
                header('Content-Type: application/json');
                echo(json_encode($MConfig->mech_details()));

            }
            elseif ($function === 'print_bill')
            {
                require '../includes/print.php';
//                print_r($_POST);
                $billNo = $anton->post('billNo');
                $mechNo = $anton->post('mechNo');
                $day = $anton->post('day');
                printbill($mechNo,$billNo,'unknown');
            }
            elseif ($function === 'print_sales')
            {
                require '../includes/print.php';

                printSales();
            }

            elseif ($function === 'bill_summary'){
                $billRef = billRef;
                $sum = (new \billing\Billing())->billSummary($billRef);
                header("Content-Type:Application/Json");
                echo json_encode($sum);

            }

        }
    }
