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
                    $grp_id = $db->get_rows('item_group',"`grp_uni` = '$group'")['id'];

                    if($db->row_count('prod_mast',"`item_grp` = '$grp_id'") > 0)
                    {
                        // get items
                        $items = '';
                        $items_sql = $db->db_connect()->query("SELECT * FROM `prod_mast` WHERE `item_grp` = '$grp_id' order by `desc` ASC");
                        while ($item = $items_sql->fetch(PDO::FETCH_ASSOC))
                        {
                            $name = $item['desc'];
                            $retail = $item['retail'];
                            $id = $item['id'];
                            $uni = $item['item_uni'];
                            $barcode = $item['barcode'];
                            $disc = $item['discount'];
                            $ori_p = '';

                            if($disc === 1)
                            {
                                $bg = 'item_btn_discount';
                                $discount_rate = $item['discount_rate'];
                                $retail_p = $retail - $db->percentage($discount_rate,$retail);
                                $ori_p = $retail;
                            }
                            else
                            {
                                $bg = 'item_btn_bg';
                                $retail_p = $retail;
                            }

                            $items .= "
                                <div onclick='add_item_to_bill(\"$barcode\")' class=\"item_btn $bg m-2 p-1\">
                                        <div class=\"w-100 d-flex flex-wrap align-content-center h-50\">
                                            <p class=\"text-elipse m-0 p-0 font-weight-bolder\">$name</p>
                                        </div>
                                        <div class=\"w-100 d-flex flex-wrap align-content-center h-50\">
                                            <strike class='text-danger'><small>$ori_p</small></strike>
                                            <p class=\"text-elipse m-0 p-0\">$retail_p</p>
                                        </div>
                                    </div>
                            ";
                        }
                        $anton->done($items);
                    }
                    else
                    {
                        $items = "
                            <div class='w-100 h-100 d-flex flex-wrap align-content-center justify-content-center'>
                            <p class='enc'>No Item</p>
</div>
                        ";
                        $anton->done($items);
                    }

                }

            }

            elseif ($function === 'new_item') // add item to bill
            {

                $item = trim($anton->post('barcode'));
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
                if($db->row_count('prod_mast',"`barcode` = '$barcode'") < 1)
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
                if($bill_query->rowCount() < 1)
                {
                    echo 'no_bill%%';
                    exit();
                }
                $bill_items = 'done%%';
                $sn = 0;
                while($bill = $bill_query->fetch(PDO::FETCH_ASSOC))
                {
                    ++$sn;
                    $item = $bill['item_barcode'];
                    $item_barcode_md5 = md5($item);

                    // get item details
                    $i_d = $db->get_rows('prod_mast',"`barcode` = $item");
                    $item_name = $bill['item_desc'];
                    $qty = $bill['item_qty'];
                    $cost = $bill['bill_amt'];
                    $barcode = $bill['item_barcode'];
                    $id = $bill['id'];
                    if($bill['selected'] === 1)
                    {
                        $cart_item = 'cart_item active';
                    }
                    else
                    {
                        $cart_item = 'cart_item';
                    }


                    // make bill item
                    $bill_item = "<div 
                                    onclick= \"mark_bill_item('$id')\" id='billItem$barcode'
                                    class=\"d-flex flex-wrap $cart_item align-content-center justify-content-between border-dotted pb-1 pt-1\"
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

                // check if there discount and append discount value
                $disc_condition = "`clerk` = '$myName' AND `bill_number` = '$bill_number' AND `trans_type` = 'D' and `date_added` = '$today'";
                $discount_code = '';
                if($db->row_count('bill_trans',"$disc_condition") > 0)
                {
                    // set row;
                    $disc_rate = $db->get_rows('bill_trans',"$disc_condition")['bill_amt'];
                    $bill_condition = "`clerk` = '$myName' AND `bill_number` = '$bill_number' AND `trans_type` = 'i' and `date_added` = '$today'";
                    $disc_condition = "`clerk` = '$myName' AND `bill_number` = '$bill_number' AND `trans_type` = 'D' and `date_added` = '$today'";
                    $disc_amount = $db->discount($bill_condition,$disc_condition);
                    $discount_code .= "<div 
                                    class=\"d-flex flex-wrap cart_item bg-warning text-danger align-content-center justify-content-between border-dotted pb-1 pt-1\"
                                    >
                                    
                                    <div class=\"75 h-100 d-flex flex-wrap align-content-center pl-1\">
                                        <p class=\"m-0 p-0\"><strong>Discount</strong></p>
                                    </div>
            
                                    <!--Cost-->
                                    <div class=\"w-25 h-100 d-flex flex-wrap align-content-center pl-1\">
                                        <p class=\"m-0 p-0\">"."<strong>-$disc_rate%($disc_amount)</strong>"."</p>
                                    </div>
                                </div>";
                }

                // return bill item
                echo $bill_items.$discount_code;
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

                $randomString = $db->uniqieStr('`bill_hold`','`bill_grp`',4);

                //$anton->print_bill('2','hold');


                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number'") > 0 )
                {
                    // todo print bill
                    // insert bill into holding
                    $items = $db->db_connect()->query("SELECT * FROM `bill_trans` WHERE `trans_type` = 'i' AND `bill_number` = '$bill_number' AND `date_added` = '$today'");
                    while($item = $items->fetch(PDO::FETCH_ASSOC))
                    {
                        $item_barcode = $item['item_barcode'];
                        $item_qty = $item['item_qty'];

                        if($db->db_connect()->exec("INSERT INTO `bill_hold`(`bill_grp`,`item_barcode`,`item_qty`) values ('$randomString','$item_barcode','$item_qty')"))
                        {
                            // delete item
                            $db->db_connect()->exec("DELETE FROM `bill_trans` WHERE `bill_number` = '$bill_number' AND `item_barcode` = '$item_barcode'");
                        }


                    }
                    $anton->done('bill_held');
                    //$db->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item_desc`,`trans_type`,`clerk`,`item_barcode`) values ('$machine_number','$bill_number','bill_held','H','$myName','not_item')");
                }
            }

            elseif ($function === 'sub_total')// sub total
            {
                // sub total
                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number'  AND `date_added` = '$today'") > 0 )
                {

                    $bill_condition = "`clerk` = '$myName' AND `bill_number` = '$bill_number' AND `trans_type` = 'i' and `date_added` = '$today'";
                    $disc_condition = "`clerk` = '$myName' AND `bill_number` = '$bill_number' AND `trans_type` = 'D' and `date_added` = '$today'";

                    // check if there is discount
                    if($db->row_count('bill_trans',"$disc_condition") > 0)
                    {
                        $_SESSION['sub_total'] = 0;
                        $disc_rate = $db->get_rows('bill_trans',"$disc_condition")['bill_amt'];
                        // calculate sub total with discount
                        $items = $db->db_connect()->query("SELECT * FROM `bill_trans` WHERE $bill_condition");
                        while($it = $items->fetch(PDO::FETCH_ASSOC))
                        {
                            $item_barcode = $it['item_barcode'];
                            $item = $db->get_rows('prod_mast',"`barcode` = '$item_barcode'");

                            if($item['discount'] === 1)
                            {
                                //dont  apply discount

                                $s_toal = $it['bill_amt'];
                            }
                            else
                            {
                                //apply discount
                                $b_amt = $it['bill_amt'];
                                $s_toal = $b_amt - $db->percentage($disc_rate,$b_amt);
                            }

                            $s = $_SESSION['sub_total'];
                            $_SESSION['sub_total'] = $s + $s_toal;

                        }

                        $sub_total = $_SESSION['sub_total'];
                        unset($_SESSION['sub_total']);

                    }
                    else
                    {
                        $sub_total = $db->sum('bill_trans',"bill_amt",$bill_condition);
                    }

                    //$sub_total = $db->sum('bill_trans',"bill_amt",$bill_condition);
                    $tax_total = $db->sum('bill_trans',"tax_amt",$bill_condition);

                    $anton->done(number_format($sub_total,2)."()".number_format($tax_total,2));

                }
            }

            elseif ($function === 'payment') // making payment
            {
                $method = $anton->post('method');
                $amount_paid = $anton->post('amount_paid');

                // todo paused

                // make payment
                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number' AND `date_added` = '$today'") > 0 )
                {
                    // todo print bill

                    // get bill quantity items
                    $itm_qty = $db->db_connect()->query("SELECT SUM(bill_amt) from `bill_trans` WHERE `bill_number` = '$bill_number'");
                    $itm_qty_stmt = $itm_qty->fetch(PDO::FETCH_ASSOC);
                    $num_of_items = $itm_qty_stmt['SUM(bill_amt)'];
                    // mark bill as canceled
                    try {
                        // todo print_bill
                        //$anton->print_bill($bill_number,'P');
                        $db->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item_desc`,`trans_type`,`clerk`,`item_barcode`) values ('$machine_number','$bill_number','$method','P','$myName','PAYMENT')");
                        $anton->done('bill_done');
                    } catch (PDOException $exception)
                    {
                        $error = $exception->getMessage();
                        $anton->err($error);
                    }
                }

            }

            elseif ($function === 'recall_bill') // recall bill
            {
                $bill_grp = $anton->post('bill_grp');

                // check if bill number exist
                if($db->row_count('bill_hold',"`bill_grp` = '$bill_grp' AND `bill_date` = '$today'") < 1 )
                {
                    $anton->err('bill_recall_does_not_exits');
                    die();
                }
                else
                {
                    // load bill
                    $held_bill = $db->db_connect()->query("SELECT * FROM `bill_hold` WHERE `bill_grp` = '$bill_grp' and `bill_date` = '$today'");
                    while($item = $held_bill->fetch(PDO::FETCH_ASSOC))
                    {
                        $barcode = $item['item_barcode'];
                        $item_qty = $item['item_qty'];

                        // insert into bill
                        $db->add_item_bill("$bill_number","$barcode","$item_qty","$myName");

                    }
                    // delete all bill item
                    $db->delete("`bill_hold`","`bill_grp` = '$bill_grp'");
                    $anton->done('bill_found');
                }





            }

            elseif ($function === 'discount')
            {
                $rate = $anton->post('rate');
                $user_id = $anton->post('user_id');
                $password = $anton->post('password');
                $rate = $anton->post('rate');


                if($db->clerkAuth($user_id,$password))
                {
                    //$anton->done('pass');
                    // apply discount
                    // check if discount already already applied
                    if($db->row_count('bill_trans',"`bill_number` = $bill_number AND `clerk` = '$myName' AND `date_added` = '$today' AND `trans_type` = 'D'") < 1)
                    {
                        $db->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item_desc`,`trans_type`,`clerk`,`item_barcode`,`bill_amt`) values ('$machine_number','$bill_number','DISCOUNT','D','$myName','DICOUNT','$rate')");
                        $anton->done('discount_applied');
                    }
                    else
                    {
                        // update discount
                        $db->db_connect()->exec("UPDATE `bill_trans` SET `bill_amt` = '$rate' WHERE `bill_number` = $bill_number AND `clerk` = '$myName' AND `date_added` = '$today' AND `trans_type` = 'D'");
                        $anton->done('discount_updated');
                    }

                }
                else
                {
                    $anton->err('no_clerk_account');
                }

                die();

                // check if discount is less than or equal to allowed


            }

            elseif ($function === 'admin_auth')
            {
                $clerk_id = $anton->post('user_id');
                $password = $anton->post('password');

                $db->clerkAuth($clerk_id,$password);

                print_r($_POST);
            }


        }

    }
