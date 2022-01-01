<?php
    require '../includes/core.php';
    print_r($_POST);

    //echo $_SERVER['REQUEST_METHOD'];

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {

        if(isset($_POST['function'])) // if we have a function from post call
        {
            $fuction = $anton->post('function');

            if($fuction === 'change_item_group') // if we are making a group change
            {
                // get group
                $group = $anton->post('group');

                // check if group exist
                if($db->row_count('item_group',"`grp_uni` = '$group'") > 0)
                {
                    // get items
                    if($db->row_count('items_master',"`item_grp` = '$group'") > 0)
                    {
                        // get items
                        $items = '';
                        $items_sql = $db->db_connect()->query("SELECT * FROM `items_master` WHERE `item_grp` = '$group' order by `desc` ASC");
                        while ($item = $items_sql->fetch(PDO::FETCH_ASSOC))
                        {
                            $name = $item['desc'];
                            $retail = $item['retail'];
                            $id = $item['id'];
                            $uni = $item['item_uni'];
                            $items .= "
                                <div onclick='add_item_to_bill(\"$uni\")' class=\"item_btn m-2 p-1\">
                                        <div class=\"w-100 d-flex flex-wrap align-content-center h-50\">
                                            <p class=\"text-elipse m-0 p-0 font-weight-bolder\">$name</p>
                                        </div>
                                        <div class=\"w-100 d-flex flex-wrap align-content-center h-50\">
                                            <p class=\"text-elipse m-0 p-0\">$ $retail</p>
                                        </div>
                                    </div>
                            ";
                        }
                        $anton->done($items);
                    }
                }

            }

            elseif ($fuction === 'add_item_to_bill') // add item to bill
            {
                $item = $anton->post('item');
                $quantity = $anton->post('quantity');

                //get item details
                $item_details = $db->db_connect()->query("SELECT * FROM `items_master` WHERE `item_uni` = '$item'");
                $item_d = $item_details->fetch(PDO::FETCH_ASSOC);

                $barcode = $item_d['barcode'];
                // add to bill in trans
                $db->db_connect()->exec(
                    "insert into `bill_trans` (`mach`,`bill_number`,`item`,`trans_type`,`amount`,`clerk`) values ('$machine_number','$bill_number','$barcode','i','$quantity','$myName')"
                );


            }

            elseif ($fuction === 'get_bill_items') // get items in current bill
            {
                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number' AND `date_added` = '$today'") > 0 )
                {
                    // get all from bill
                    $bill_query = $db->db_connect()->query(
                        "SELECT * FROM `bill_trans` WHERE 
                                 `bill_number` = '$bill_number' AND 
                                 `mach` = '$machine_number' AND 
                                 `trans_type` = 'i' AND `date_added` = '$today'"
                    );
                    $bill_items = 'done%%';
                    $sn = 0;
                    while($bill = $bill_query->fetch(PDO::FETCH_ASSOC))
                    {
                        ++$sn;
                        $item = $bill['item'];
                        $item_barcode_md5 = md5($item);

                        // get item details
                        $i_d = $db->get_rows('items_master',"`barcode` = $item");
                        $item_name = $i_d['desc'];
                        $qty = $bill['amount'];
                        $cost = $i_d['retail'] * $qty;

                        // make bill item
                        $bill_item = "<div 
                                    oncontextmenu=\"mark_bill_item('$item_barcode_md5')\" 
                                    ondblclick=\"mark_bill_item('$item_barcode_md5')\" 
                                    class=\"d-flex flex-wrap cart_item align-content-center justify-content-between border-dotted pb-1 pt-1\"
                                    >
                                    
                                    <div class=\"w-10 h-100 d-flex flex-wrap align-content-center pl-1\">
                                        <p class=\"m-0 p-0\">$sn</p>
                                    </div>
            
                                    <div class=\"w-50 h-100 d-flex flex-wrap align-content-center pl-1\">
                                        <p class=\"m-0 p-0\">$item_name</p>
                                    </div>
            
                                    <div class=\"w-20 h-100 d-flex flex-wrap align-content-center pl-1\">
                                        <p class=\"m-0 p-0\">$qty</p>
                                    </div>
            
                                    <!--Cost-->
                                    <div class=\"w-20 h-100 d-flex flex-wrap align-content-center pl-1\">
                                        <p class=\"m-0 p-0\">".number_format($cost,2)."</p>
                                    </div>
                                </div>";

                        // append item to bills
                        $bill_items .= $bill_item;

                    }

                    // return bill item
                    echo $bill_items;
                }
                else
                {
                    $anton->err();
                }


            }

            elseif ($fuction === 'cancel_current_bill') // cancel current bill
            {


                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number'") > 0 )
                {
                    // todo print bill

                    // get bill quantity items
                    $itm_qty = $db->db_connect()->query("SELECT SUM(amount) from `bill_trans` WHERE `bill_number` = '$bill_number'");
                    $itm_qty_stmt = $itm_qty->fetch(PDO::FETCH_ASSOC);
                    $num_of_items = $itm_qty_stmt['SUM(amount)'];
                    // mark bill as canceled
                    $db->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item`,`trans_type`,`amount`,`clerk`) values ('$machine_number','$bill_number','bill_canced','C','$num_of_items','$myName')");
                }


            }

            elseif ($fuction === 'hold_current_bill')
            {
                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number'") > 0 )
                {
                    // todo print bill

                    // get bill quantity items
                    $itm_qty = $db->db_connect()->query("SELECT SUM(amount) from `bill_trans` WHERE `bill_number` = '$bill_number'");
                    $itm_qty_stmt = $itm_qty->fetch(PDO::FETCH_ASSOC);
                    $num_of_items = $itm_qty_stmt['SUM(amount)'];
                    // mark bill as canceled
                    $db->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item`,`trans_type`,`amount`,`clerk`) values ('$machine_number','$bill_number','bill_canced','H','$num_of_items','$myName')");
                }
            }

            elseif ($fuction === 'sub_total')// sub total
            {
                // sub total
                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number'  AND `date_added` = '$today'") > 0 )
                {
                    // get sub total
                    $bill_query = $db->db_connect()->query(
                        "SELECT * FROM `bill_trans` WHERE 
                                 `bill_number` = '$bill_number' AND 
                                 `mach` = '$machine_number' AND 
                                 `trans_type` = 'i' AND `date_added` = '$today'"
                    );

                    $sub_total = 0.00;
                    $tax_total = 0.00;

                    while($bill = $bill_query->fetch(PDO::FETCH_ASSOC))
                    {
                        $item = $bill['item'];

                        // get retail
                        $i_d = $db->get_rows('items_master',"`barcode` = $item");
                        $item_name = $i_d['desc'];
                        $qty = $bill['amount'];
                        $tax_group = $i_d['tax_grp'];
                        $tax_rate = $db->get_rows('tax_master',"`id` = '$tax_group'")['rate'];


                        $cost = $i_d['retail'] * $qty;
                        $tax_value = number_format($anton->percentage($tax_rate,$cost),2);
                        $tax_total += $tax_value;

                        // get tax grooup


                        $sub_total += $cost;

                        // todo calculate tax for sub total

                    }

                    $anton->done(number_format($sub_total,2)."()".number_format($tax_total,2));

                }
            }

            elseif ($fuction === 'payment') // making payment
            {
                $method = $anton->post('method');
                $amount_paid = $anton->post('amount_paid');

                // make payment
                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number' AND `date_added` = '$today'") > 0 )
                {
                    // todo print bill

                    // get bill quantity items
//                    $itm_qty = $db->db_connect()->query("SELECT SUM(amount) from `bill_trans` WHERE `bill_number` = '$bill_number'");
//                    $itm_qty_stmt = $itm_qty->fetch(PDO::FETCH_ASSOC);
//                    $num_of_items = $itm_qty_stmt['SUM(amount)'];
                    // mark bill as canceled
                    try {
                        $db->db_connect()->exec("insert into `bill_trans` 
                                            (`mach`,`bill_number`,`item`,`trans_type`,`amount`,`clerk`) values 
                                            ('$machine_number','$bill_number','$method','P','$amount_paid','$myName')");
                        $anton->done();
                    } catch (PDOException $exception)
                    {
                        $error = $exception->getMessage();
                        $anton->err($error);
                    }
                }

            }


        }
    }
