<?php

    require '../../includes/core.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_POST['function']))
        {
//            print_r($_POST);
            $function = $anton->post('function');

            if($function === 'LKUP') // look for an item
            {
                $query_str = $anton->post('query_str');

                // get item with query and stock type is not discontinued
                $query = $db->db_connect()->query("SELECT * FROM `prod_mast` where `desc` like '%$query_str%' or `barcode` like '%$query_str%' and `stock_type` != 3");

                if($query->rowCount() > 0)
                {
//                    $anton->done($query->rowCount()." items found");
                    $code = '';
                    while($item = $query->fetch(PDO::FETCH_ASSOC))
                    {
                        $barcode = $item['barcode'];
                        $js_id = "item".md5($barcode);
                        $barcode_id = "barcode$js_id";
                        $qty_id = "qty$js_id";
                        $js_param = "$('#$js_id').val()*$barcode";
                        $description = $item['desc'];
                        $retail = $item['retail'];
                        $code .= "<tr><td id='$barcode_id'>$barcode</td><td>$description</td><td>$retail</td><td><input id='$qty_id' style='width: 100px' class='form-control' value='1' type='number'></td><td><kbd onclick=\"lookupAddToBill('$js_id')\">Add</kbd></td></tr>";
                    }

                    $anton->done($code);

                }
                else
                {
                    $anton->err("No Items");
                }


            }

            elseif ($function === 'mark_bill') // mark bill
            {
                print_r($_POST);
                $id = $anton->post('id');
//                $selected = $db->get_rows('bill_trans',"`id` = '$id'")['selected'];
                $selected = $db->get_rows('bill_trans',"`id` = '$id'")['selected'];

                if($selected == '1')
                {
                    $m = 0;
                }
                else
                {
                    $m = 1;
                }
                echo $m;

                $db->db_connect()->exec("UPDATE `bill_trans` set selected = '$m' where  `id` = '$id'");
                $anton->done();
            }

            elseif ($function === 'bill_refund') // refund bill
            {
                $response = array('code'=>404,'message'=>array('bill_no'=>0,'msg'=>'none'));
                try {
                    $ref_type = $anton->post('ref_type');
                    $billRef = $anton->post('billRef');
                    $refund_item = $_POST['refund_item'];

                    // check reference type
                    if($ref_type === 'active_shift'){
                        $table = 'bill_trans';
                    } elseif ($ref_type === ''){
                        $table = 'bill_history_trans';
                    } else {
                        die();
                    }

                    // get trans
                    for ($i = 0; $i < count($refund_item); $i++) {
                        $item = $refund_item[$i];

                        // separate
                        $item_sep = explode('|',$item);
                        $barcode = $item_sep[0];
                        $id = $item_sep[1];
                        $anton->log2file("BARCODE : $barcode");
                        $anton->log2file("ID : $id");

                        // get item
                        $sold = (new \db_handeer\db_handler())->get_rows("$table","`id` = '$id' AND `item_barcode` = '$barcode'");
                        $soldQuantity = $sold['item_qty'];
                        $refundQty = $soldQuantity * -1;
                        $refundItem = (new \db_handeer\db_handler())->get_rows('prod_mast',"`barcode` = '$barcode'");
                        $add_bill = (new \billing\Billing())->AddToBill("$bill_number",$refundItem,"$refundQty",clerk_code);

                    }

                    $response['code'] = 200;
                    $response['message']['bill_no'] = $bill_number;
                    $response['message']['msg'] = "REFUND DONE";

                    print_r($_POST);
                } catch (Exception $e){
                    $response['code'] = 505;
                    $response['message']['bill_no'] = $bill_number;
                    $response['message']['msg'] = $e->getMessage();
                }

                echo json_encode($response);

            }

        }
    }
