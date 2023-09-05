<?php

use db_handeer\db_handler;
use mechconfig\MechConfig;

require '../includes/core.php';
    require '../includes/print.php';


/* A wrapper to do organise item names & prices into columns */


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
                if($db->row_count('item_buttons',"`button_index` = '$group'") > 0)
                {
                    $grp_id = $db->get_rows('item_buttons',"`button_index` = '$group'")['button_index'];


                    if($db->row_count('prod_mast',"`item_grp` = '$grp_id'") > 0)
                    {
                        // get items
                        $items = '';
                        $sql = "SELECT * FROM `prod_mast` WHERE `item_grp` = '$grp_id' order by `desc` ASC";


                        $items_sql = $db->db_connect()->query($sql);
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
                    $anton->err("$barcode DOES NOT EXIST");
                    exit();
                }
                $item = (new \db_handeer\db_handler())->get_rows('prod_mast',"`barcode` = '$barcode'");

                $add_bill = (new \billing\Billing())->AddToBill($bill_number,$item,$qty,clerk_code);

                echo json_encode($add_bill);



            }

            elseif ($function === 'get_bill_items') // get bill
            {

                $q = "SELECT * FROM `bill_trans` WHERE
                                 `bill_number` = '$bill_number' AND
                                 `mach` = '$machine_number' AND
                                 `trans_type` in ('i','D') AND `date_added` = '$today'";





                // get all from bill
                $bill_query = (new \db_handeer\db_handler())->db_connect()->query($q);
                if((new \db_handeer\db_handler())->row_count('bill_trans',"`bill_number` = '$bill_number' AND `date_added` = '$today'") < 1)
                {
                    echo 'no_bill%%';
                    exit();
                }

//                die('HELLO');

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
                    $selected = '';
                    if($bill['selected'] == '1')
                    {
                        $cart_item = 'cart_item active';
                        $selected = 'selected';
                    }
                    else
                    {
                        $cart_item = 'cart_item';
                    }
                    $selected = '';


                    // make bill item
                    $bill_item = "<div
                                    onclick= \"mark_bill_item('$id')\" id='billItem$barcode'
                                    class=\"d-flex flex-wrap $cart_item align-content-center justify-content-between border-dotted pb-1 pt-1\"
                                    >

                                    <div class=\"w-10 h-100 d-flex flex-wrap align-content-center pl-1\">
                                        <p class=\"m-0 p-0\">$sn</p>
                                    </div>

                                    <div class=\"w-50 h-100 d-flex flex-wrap align-content-center pl-1\">
                                    <small>$barcode</small>
                                        <p class=\"m-0 p-0\">$item_name</p>
                                        <small class='text-info'>$selected</small>
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

            elseif ($function === 'get_bill') // get bill v2
            {
                $billRef = billRef;
                $response = ['status'=>404,'message'=>'null'];
                $bill_cond = "`bill_no` = '$bill_number' AND `bill_date` = '$today' and mech_no = '$machine_number'";
                // count bill tran count
                $bill_tran_count = (new \db_handeer\db_handler())->row_count('bill_trans',"`bill_number` = '$bill_number' AND `date_added` = '$today'");

                $response['message'] = $bill_tran_count;
                if($bill_tran_count > 0){

                    // get all bill trans and loop
                    $q = "SELECT * FROM `bill_trans` WHERE `trans_type` in ('i','D') AND billRef = '$billRef' order by trans_type desc";
                    (new anton())->log2file("BILL TRANS \n $q \n BILL TRANS");
                    $bill_query = (new \db_handeer\db_handler())->db_connect()->query($q);




                    $bill_items = 'done%%';
                    $sn = 0;
                    $trans = [];
                    $total = 0;
                    $tax_total = 0;
                    while($bill = $bill_query->fetch(PDO::FETCH_ASSOC)) {

                        ++$sn;
                        $item = $bill['item_barcode'];
                        $item_barcode_md5 = md5($item);

                        // get item details
//                        $i_d = $db->get_rows('prod_mast', "`barcode` = $item");
                        $item_name = $bill['item_desc'];
                        $qty = $bill['item_qty'];
                        $cost = $bill['bill_amt'];
                        $barcode = $bill['item_barcode'];
                        $id = $bill['id'];
                        $select = $bill['selected'] ;
                        $tax = $bill['tax_amt'];
                        $tran = $bill['trans_type'];
                        $total += $cost;
                        $tax_total += $tax;

                        $this_tran = [
                            'id'=>$id,
                            'barcode'=>$barcode,
                            'desc'=>$item_name,
                            'qty'=>number_format($qty,2),
                            'cost'=>number_format($cost,2),
                            'tax'=>number_format($tax,2),
                            'select'=>$select,
                            'tran'=>$tran
                        ];

                        $trans[] = $this_tran;

                        $selected = '';
                        if ($bill['selected'] == '1') {
                            $cart_item = 'cart_item active';
                            $selected = 'selected';
                        } else {
                            $cart_item = 'cart_item';
                        }
                        $selected = '';

                    }

                    $bill_trans = [
                        'bill_header'=>(new billing\Billing())->billSummaryV2(),
                        'count'=>$bill_tran_count,
                        'total'=>number_format(bill_total['taxable_amt'],2),
                        'tax'=>number_format($db->sum('bill_tax_tran','tax_amt',$bill_cond),2),
                        'trans'=>$trans
                    ];
                    $response['status'] = 202;
                    $response['message'] = $bill_trans;
                }
                else{
                    $response['message'] = 'NO BILL TRANS';
                }


                header('Content-Type: application/json');
                echo json_encode($response);
            }

            elseif ($function === 'void') // void
            {
                $db->db_connect()->query("DELETE FROM `bill_trans` WHERE `bill_number` = '$bill_number' AND `date_added` = '$today' AND `selected` = 1 and mach = '$machine_number'");

                // todo delete tax transactions
                echo 'done';
            }

            elseif ($function === 'cancel_current_bill') // cancel current bill
            {


                if($db->row_count('bill_trans',"`trans_type` = 'i' AND `bill_number` = '$bill_number'") > 0 )
                {
                    // todo print bill

                    // mark bill as canceled
//                    $db->db_connect()->exec("CALL DelBill('$bill_number','$machine_number',1,'$today')");
                    $db->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item_desc`,`trans_type`,`clerk`,`item_barcode`) values ('$machine_number','$bill_number','bill_canced','C','$myName','not_item')");
                    $db->db_connect()->query("DELETE FROM `bill_trans` WHERE `bill_number` = '$bill_number' AND `date_added` = '$today'  and mach = '$machine_number' and bill_number = '$bill_number'");
                    $db->db_connect()->query("DELETE FROM `bill_tax_tran` WHERE `bill_no` = '$bill_number' AND `bill_date` = '$today'  and `mech_no` = '$machine_number' and bill_no = '$bill_number'");

                }


            }

            elseif ($function === 'hold_current_bill')
            {
                $response = ['status'=>202,'message'=>"INI"];
                $randomString = $db->uniqieStr('`bill_hold`','`bill_grp`',4);

                //$anton->print_bill('2','hold');



                if(bill_total['valid'] === 'Y')
                {
                    // todo print bill

                    $items = $db->db_connect()->query("SELECT * FROM `bill_trans` WHERE `trans_type` = 'i' AND `bill_number` = '$bill_number' AND `date_added` = '$today' and mach = $machine_number");
                    while($item = $items->fetch(PDO::FETCH_ASSOC))
                    {
                        $item_barcode = $item['item_barcode'];
                        $item_qty = $item['item_qty'];
//                        echo $item_barcode;
                        $db->db_connect()->exec("INSERT INTO `bill_hold`(`bill_grp`,`item_barcode`,`item_qty`) values ('$randomString','$item_barcode','$item_qty')");




                    }
                    // todo print held bill
                    $delete = "DELETE FROM `bill_trans` WHERE `bill_number` = '$bill_number' and mach = $machine_number";
                    // delete item
//                    echo $delete;
                    $db->db_connect()->exec($delete);
                    $response['message'] = "Bill Hold Number : $randomString";

                } else {
                    $response['message'] = bill_total;
                }
                header('Content-Type: application/json');
                echo json_encode($response);
            }

            elseif ($function === 'hold_bill_v2'){ // hold a bill



                $response = ['code'=>500,'message'=>'NOT INITIALIZED'];
                // generate random 4 digit number
                $entry_number = str_replace('-','',$today) . (new \mechconfig\MechConfig)->lite_row_count('hold_hd','entry_no','`id` > 0');
                $entry_number ++;
                $entry_no = str_rot13(str_shuffle($entry_number));


                // insert  into header
                try {
                    $insert_hold_hd = "INSERT INTO hold_hd ('entry_no') values ('$entry_no')";
                    (new \mechconfig\MechConfig)->mech_db()->query($insert_hold_hd);


                    // loop through bill tran and add all pending
                    $cur_q = "SELECT * FROM bill_trans where `bill_number` = '$bill_number' AND date_added = '$today'";
                    $current_tran = (new \mechconfig\MechConfig)->mech_db()->query($cur_q);


//                    $fetch = $current_tran->fetch(PDO::FETCH_ASSOC);
//
//                    while($tran = $fetch)
//                    {
//                        $barcode = $tran['item_barcode'];
//                        $qty = $tran['item_qty'];
//                        $vals = "('$entry_no','$barcode','$qty')";
//                        $insert_tr = "INSERT INTO hold_tran (entry_no,barcode,tran_qty) values $vals";
//                        // insert into hold bill
//                        (new \mechconfig\MechConfig)->mech_db()->query($insert_tr);
//
//                    }


                    // delete from bill trans
//                    $del_tr = "DELETE FROM bill_trans where bill_number = '$bill_number' and date_added = '$today'";
//                    (new \mechconfig\MechConfig)->mech_db()->query($del_tr);


                    $response['code'] = 200;
                    $response['message'] = $insert_tr;

                } catch (PDOException $e)
                {
                    $response['code'] = 500;
                    $response['message'] = $e->getMessage();
                }




                header('Content-Type: application/json');
                echo json_encode($response);


            }

            elseif ($function === 'sub_total')// sub total
            {
                // sub total
                $bill_cond = "`trans_type` = 'i' AND `bill_number` = '$bill_number'  AND `date_added` = '$today'";
                if((new \mechconfig\MechConfig)->lite_row_count('bill_trans',"bill_number",$bill_cond) > 0 )
                {

                    $bill_condition = "`clerk` = '$myName' AND `bill_number` = '$bill_number' AND `trans_type` = 'i' and `date_added` = '$today'";
                    $disc_condition = "`clerk` = '$myName' AND `bill_number` = '$bill_number' AND `trans_type` = 'D' and `date_added` = '$today'";

                    $sub_total = (new \mechconfig\MechConfig)->sum('bill_trans',"bill_amt",$bill_condition);

                    $tax_total = (new \mechconfig\MechConfig)->sum('bill_trans',"tax_amt",$bill_condition);

                    $anton->done(number_format($sub_total,2)."()".number_format($tax_total,2));

                }
            }

            elseif ($function === 'payment') // making payment
            {


                $amount_paid = $anton->post('amount_paid');

                $myName = $_SESSION['clerk_id'];
                $today = today;
                // get current bill details
                $bill_number = bill_no;
                $machine_number = (new MechConfig())->mech_details()['mechine_number'];

                $bill_trans_count = "`date_added` = '$today' and `mach` = '$machine_number' and `bill_number` = '$bill_number'";
                $bill_sql = (new db_handler())->db_connect()->query("SELECT * FROM bill_trans WHERE $bill_trans_count");

                // make payment
                if((new \db_handeer\db_handler())->row_count('bill_trans','bill_number',"`bill_number` = '$bill_number'") > 0 )
                {

                    $method = $anton->post('method');
                    $response = $bill->makePyament($method,$amount_paid);
                    (new anton())->log2file("FINAL RESPONSE");
                    (new anton())->log2file(var_export($response,true),'',1);
                    (new anton())->log2file("DONE");
                    echo json_encode($response);
                    print_r($response);



                    die();


                    // get bill quantity items
//                    $tran_qty_stmt = $db->db_connect()->query("SELECT SUM(item_qty) as itrm_qty from `bill_trans` WHERE `bill_number` = '$bill_number' and mach = $machine_number");
//                    $tran_qty = (new \mechconfig\MechConfig)->sum('bill_trans','item_qty',"`bill_number` = '$bill_number' and mach = $machine_number");
                    $tran_qty = (new \db_handeer\db_handler())->col_sum('bill_trans','item_qty',"`bill_number` = '$bill_number' and mach = $machine_number");

//                    $gross_amt  = (new \mechconfig\MechConfig)->sum('bill_trans','bill_amt',"`bill_number` = '$bill_number' and mach = $machine_number");
                    $gross_amt  = (new \db_handeer\db_handler())->col_sum('bill_trans','bill_amt',"`bill_number` = '$bill_number' and mach = $machine_number");


//                    $tax_amt = (new \mechconfig\MechConfig)->sum('bill_trans','tax_amt',"`bill_number` = '$bill_number' and mach = $machine_number");
                    $tax_amt = (new \db_handeer\db_handler())->col_sum('bill_trans','tax_amt',"`bill_number` = '$bill_number' and mach = $machine_number");



                        $bill_header_insert = "INSERT INTO bill_header (mach_no, clerk, bill_no, pmt_type, gross_amt, tax_amt, net_amt,tran_qty)VALUES
                                                                            ($machine_number, '$myName', $bill_number, '$method', $gross_amt, $tax_amt, $gross_amt - $tax_amt, $tran_qty);
    ";
                    // mark bill as canceled
                    try {
                        // todo print_bill
                        //$anton->print_bill($bill_number,'P');
                        (new \db_handeer\db_handler())->db_connect()->exec($bill_header_insert);
                        (new \db_handeer\db_handler())->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item_desc`,`trans_type`,`clerk`,`item_barcode`) values ('$machine_number','$bill_number','$method','P','$myName','PAYMENT')");

                        $anton->done($bill_number);

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
//                    $anton->err('bill_recall_does_not_exits');
                    $response['status'] = 404;
                    $response['message'] = "Bill Not Found";
//                    die();
                }
                else
                {
                    // load bill
                    $held_bill = $db->db_connect()->query("SELECT * FROM `bill_hold` WHERE `bill_grp` = '$bill_grp' and `bill_date` = '$today'");
                    while($item = $held_bill->fetch(PDO::FETCH_ASSOC))
                    {
                        $barcode = $item['item_barcode'];
                        $item_qty = $item['item_qty'];
//                        print_r($barcode);
//                        die();

                        // insert into bill
                        $db->add_item_bill("$bill_number","$barcode","$item_qty","$myName");

                    }
                    // delete all bill item
                    $db->delete("`bill_hold`","`bill_grp` = '$bill_grp'");
                    $response['status'] = 200;
                    $response['message'] = "Bill Loaded";
//                    $anton->done('bill_found');
                }
                header('Content-Type: application/json');
//                header('Content-Type: application/json');
                echo json_encode($response);





            }

            elseif ($function === 'discount')
            {
                $rate = $anton->post('rate');
                $user_id = $anton->post('user_id');
                $password = $anton->post('password');
                $rate = $anton->post('rate');


                if($db->clerkAuth($user_id,$password))
                {
                    $billRef = billRef;
                    //$anton->done('pass');
                    // apply discount
                    // check if discount already, already applied
                    if($db->row_count('bill_trans',"`bill_number` = $bill_number AND `clerk` = '$clerk_code' AND `date_added` = '$today' AND `trans_type` = 'D'") < 1)
                    {
                        $db->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item_desc`,`trans_type`,`clerk`,`item_barcode`,`bill_amt`,`date_added`,`billRef`) values ('$machine_number','$bill_number','DISCOUNT','D','$clerk_code','DICOUNT','$rate','$today','$billRef')");
                        $anton->done('discount_applied');
                    }
                    else
                    {
                        // update discount
                        $db->db_connect()->exec("UPDATE `bill_trans` SET `bill_amt` = '$rate' WHERE `bill_number` = $bill_number AND `clerk` = '$clerk_code' AND `date_added` = '$today' AND `trans_type` = 'D'");
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

            elseif ($function === 'loy_redem'){
                $response = array(
                    'code'=>505,'message'=>''
                );
                $amount = $anton->post('amount');
                $billRef = billRef;
                if($db->row_count('loyalty_tran',"`billRef` = '$billRef'") === 1){
                    // there is a valid customer
                    $cust_code = $db->get_rows('loyalty_tran',"`billRef` = '$billRef'")['cust_code'];

                    // calculate points
                    $points = $amount / (5/100);

                    $n_amount = $amount - ($amount * 2);
                    $n_points = $points - ($points * 2);

                    // VALIDATE POINTS
                    if($db->sum('loyalty_point_stmt','value',"`cust_code` = '$cust_code'") >= $points)
                    {
                        // there is meat
                        try {
                            $db->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item_desc`,`trans_type`,`clerk`,`item_barcode`,`bill_amt`,`date_added`,`billRef`) values ('$machine_number','$bill_number','LOYALTY','L','$clerk_code','LOYALTY',$n_amount,'$today','$billRef')");
//                            (new \loyalty\Loyalty())->givePoints($cust_code,$billRef,$n_points);
                            $response['code'] = 202;
                            $response['message'] = "AMOUNT REDEEMED";
                        } catch (Exception $e){
                            $response['code'] = 505;
                            $response['message'] = $e->getMessage();
                        }
                    } else {
                        // invalid points
                        $response['code'] = 505;
                        $response['code'] = "POINTS MISMATCH";
                    }

                } else {
                    // no valid customer
                    $response['code'] = 404;
                    $response['message'] = "CUSTOMER NOT FOUND";
                }







                header("Content-Type:Application/Json");
                echo json_encode($response);

            }

            elseif ($function === 'mj')
            {

                $user_id = $anton->post('user_id');
                $password = $anton->post('password');
//
                $resp = ['status'=>505,'message'=>'admin_auth'];

//
                $db->clerkAuth($user_id,$password) ? $resp['status'] = 200 : $resp['message'] = 'Authenticate Failed';
                print_r(json_encode($resp));
            }
            //sub total
            elseif ($function === 'subtotal') {

                $bill = $bill_number;

                $trans = $db->db_connect()->query("SELECT * FROM bill_trans WHERE `bill_number` = '$bill_number' and `date_added` = '$today' and mach = $machine_number and `trans_type` = 'i'");
                if(bill_total['tran_qty'] > 0 )
                {
                    // check if there is discount
                    $disc_count = $db->row_count('bill_trans', "`bill_number` = '$bill_number' and `date_added` = '$today' and mach = $machine_number and `trans_type` = 'D'");
                    // get discount details
                    $discount = $db->get_rows('bill_trans', "`bill_number` = '$bill_number' and `date_added` = '$today' and mach = $machine_number and `trans_type` = 'D'") ;


                    while ($tran = $trans->fetch(PDO::FETCH_ASSOC)) {

                        $id = $tran['id'];
                        $tran_barcode = $tran['item_barcode'];
                        $product = $db->get_rows('prod_mast',"`barcode` = '$tran_barcode'",'array');

                        $tran_qty = $tran['item_qty'];

//                        if($disc_count === 1)
//                        {
//                            $dr = $discount['bill_amt'];
//                        } else {
//                            $dr = 0;
//                        }

                        $dr = 0;

                        $cur_rp = $product['retail'];
                        $tg = $product['tax_grp'];
                        $taxDetails = (new \db_handeer\db_handler())->get_rows('tax_master',"`id` = '$tg'");
                        $rate = $taxDetails['rate'];
                        $tax_description =$taxDetails['description'];
                        $tax_code = $taxDetails['attr'];


                        $new_rp =  $cur_rp - $cur_rp * ($dr/100) ;
                        $new_bill_amt = $new_rp * $tran_qty;

                        $taxx = $anton->tax($tax_code,$new_bill_amt);
                        $new_tax = $taxx['details']['taxableAmt'];

                        print_r($taxx);

                        // update
                        $db->db_connect()->exec("UPDATE bill_trans SET `retail_price` = $new_rp,`bill_amt` = $new_bill_amt, tax_amt= $new_tax WHERE `id` = '$id'");


                    }

                }
            }
            //subtotal

            // FINAL REPORTS
            elseif ($function === 'final_report')
            {
                $user_id = $anton->post('user_id');
                $password = $anton->post('password');
//
                $resp = ['status'=>505,'message'=>'admin_auth'];
                $status = $resp['status'];
                $message = $resp['message'];
                if($db->clerkAuth($user_id,$password))
                {
                    $status = 201;
                    $message = "User Logged In";
                    $report_type = $anton->post('report_type');
                    // print eod
                    $db->print_report($report_type);

                } else {
                    $message = "COuld Not Login";
                }

                print_r(json_encode($resp));
            }
            //FINAL REPORTS

            elseif ($function === 'redeem'){
                echo "REDEEM LOYALTY";
            }

            else{
                print_r('UNKNOWN FUNCTION');
            }

        }

        else{
            print_r($_POST);
        }
    }
