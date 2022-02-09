<?php
    require '../includes/core.php';

    //echo $_SERVER['REQUEST_METHOD'];

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {



        if(isset($_POST['function'])) // if we have a function from post call
        {
            $function = $anton->post('function');

            if($function === 'change_item_group') // if we are making a group change
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

            elseif ($function === 'new_item') // add item to bill
            {

                $item = $anton->post('barcode');
                // split item to find multiple
                $item_split = explode('*',$item);

                if(count($item_split) == 2)
                {
                    $qty = $item_split[0]; // first index is quantity
                    $barcode = $item_split[1]; // last index is item barcode
                }
                else
                {
                    $qty = 1; // set quantity to 1
                    $barcode = $item; // barcode is item
                }

                // check if item exist
                if($db->row_count('items_master',"`barcode` = '$barcode'") < 1)
                {
                    $anton->err('item_does_not_exist');
                    exit();
                }

                if($db->add_item_bill($bill_number,$barcode,$qty,$myName))
                {
                    $anton->done('bill_added');
                }
                else
                {
                    $anton->err('could_not_add_item');
                }



            }

            elseif ($function === 'get_bill_items') // get bill
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
                    $item = $bill['item_barcode'];
                    $item_barcode_md5 = md5($item);

                    // get item details
                    $i_d = $db->get_rows('items_master',"`barcode` = $item");
                    $item_name = $bill['item_desc'];
                    $qty = $bill['item_qty'];
                    $cost = $bill['bill_amt'];

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
                    $qty = 1; // set quantity to 1
                    $barcode = $item; // barcode is item
                }

                // return bill item
                echo $bill_items;
            }

            elseif ($function === 'cancel_current_bill') // cancel current bill
            {


                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number'") > 0 )
                {
                    // todo print bill

                    // mark bill as canceled
                    $db->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item_desc`,`trans_type`,`clerk`,`item_barcode`) values ('$machine_number','$bill_number','bill_canced','C','$myName','not_item')");
                }


            }

            elseif ($function === 'hold_current_bill')
            {
                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number'") > 0 )
                {
                    // todo print bill

                    $db->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item_desc`,`trans_type`,`clerk`,`item_barcode`) values ('$machine_number','$bill_number','bill_held','H','$myName','not_item')");
                }
            }

            elseif ($function === 'sub_total')// sub total
            {
                // sub total
                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number'  AND `date_added` = '$today'") > 0 )
                {
                    $bill_condition = "`clerk` = '$myName' AND `bill_number` = '$bill_number' AND `trans_type` = 'i'";
                    $sub_total = $db->sum('bill_trans',"bill_amt",$bill_condition);
                    $tax_total = $db->sum('bill_trans',"tax_amt",$bill_condition);

                    $anton->done(number_format($sub_total,2)."()".number_format($tax_total,2));

                }
            }

            elseif ($function === 'payment') // making payment
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
