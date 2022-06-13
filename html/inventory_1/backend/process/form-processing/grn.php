<?php
    require '../../includes/core.php';

    if(isset($_POST['function'])) // if we are posting a form with function
    {
        $function = $anton->post('function'); // function values

        if($function === 'new_grn') // save a new grn
        {
//            print_r($_POST);
//            die();
            // get grn_hd_values
            $loc_id = $anton->post('loc_id');
            $supp_id = $anton->post('supp_id');
            $ref_doc = $anton->post('ref_doc');
            $tax_grp = $anton->post('tax_grp');
            $remarks = $anton->post('remarks');
            $total_amount = $anton->post('total_amount');
            $rec_date = $anton->post('rec_date');
            $invoice_number = $anton->post('invoice_number');

            if($db->row_count('grn_hd',"`po_number` = '$ref_doc'") > 0)
            {
                $grn_details = $db->get_rows('grn_hd',"`po_number` = '$ref_doc'");
                $created_by = $grn_details['created_by'];
                $rec_on = $grn_details['date_received'];
                $doc_date = $grn_details['created_on'];

                $anton->err("Goods have been received on <i style='color: #00A5E3'>$rec_on</i> by <i style='color: red'>$created_by</i> and document created on <i>$doc_date</i>");
                die();
            }

            // lock grn_hd
            $db->db_connect()->beginTransaction();
            $db->db_connect()->exec("LOCK TABLES grn_hd WRITE");
            // insert into grn hd
            $db->db_connect()->exec(
                "INSERT INTO grn_hd (po_number, date_received, supplier, remarks, invoice_num, invoice_amt, tax, tax_amt,created_by,loc) VALUES 
                          ('$ref_doc','$rec_date','$supp_id','$remarks','$invoice_number',0.00,0,0.00,'$myName','$loc_id')"
            );

            // get last document
            $last_doc_query = $db->db_connect()->query("SELECT `id` FROM `grn_hd` ORDER BY `id` DESC LIMIT 1");
            $last_doc = $last_doc_query->fetch(PDO::FETCH_ASSOC);
            $grn_id = $last_doc['id'];
            $entry_no = "GR$grn_id";
            // update entry number
            $db->db_connect()->exec("UPDATE grn_hd set `entry_no` = '$entry_no' ORDER BY `id` DESC LIMIT 1");

            // unlock table
            $db->unlock('grn_hd');

            // loop through arrays
            $inv_amt = 0;
            $tax_amt = 0;
            foreach ($_POST['item_code'] as $key => $value)
            {
                $item_code = $_POST['item_code'][$key];
                $qty = $_POST['qty'][$key];
                $cost = $_POST['price'][$key];
                $total_amt = $_POST['total_amt'][$key];


                $tax = $_POST['tax'][$key];
                $net = $_POST['net'][$key];
                $prod_cost = $_POST['cost'][$key];
                $retail = $_POST['retail'][$key];


                $inv_amt += $total_amt;

                $tax_amt += $tax;

                // get item details
                $item_details = $db->get_rows('prod_master',"`item_code` = '$item_code'");
                $barcode = $item_details['barcode'];
                $item_desc = $item_details['item_desc'];
                $packing_details = $db->get_rows('prod_packing',"`item_code` = '$item_code' AND `purpose` = 2");
                $pack_desc = $packing_details['pack_desc'];
                $pack_um = $packing_details['qty'];
                $pack_id = $packing_details['pack_id'];
                $packing_d = $db->get_rows('packaging',"`id` = '$pack_id'");
                $packing = $packing_d['desc'];


                $insert = "INSERT INTO `grn_trans` (entry_no, item_code, barcode, item_description, owner, pack_desc, packing,qty,cost,total_cost,date_added,pack_um,net_amt,tax_amt,prod_cost,ret_amt) VALUES 
                                                   ('$entry_no','$item_code','$barcode','$item_desc','$myName','$pack_desc','$packing','$qty','$cost','$total_amt','$today','$pack_um','$net','$tax','$prod_cost','$retail')";

//                $anton->br($insert);
                $db->db_connect()->exec($insert);

                // insert into price change
                $prev_c = $item_details['cost'];
                $o_cost = "INSERT INTO `price_change` (item_code, price_type, previous, current) VALUES ('$item_code','c','$prev_c','$prod_cost')";
                $prev_r = $item_details['retail'];
                $o_retail = "INSERT INTO `price_change` (item_code, price_type, previous, current) VALUES ('$item_code','r','$prev_r','$retail')";
                $db->db_connect()->exec($o_cost);
                $db->db_connect()->exec($o_retail);
                // update cost and retail
                $db->db_connect()->exec("UPDATE `prod_master` SET `cost` = '$prod_cost', `prev_retail` = `retail`, `retail` = '$retail' WHERE `item_code` = '$item_code'");

//                echo "\n Item $item_code has $qty and each price is $price with cost price of $cost and retail of $retail \n";

            }


            $net_amt = $inv_amt + $tax_amt;
            // update invoice amount
            $db->db_connect()->exec("UPDATE `grn_hd` SET `invoice_amt` = '$inv_amt',`net_amt` = '$net_amt'");
            // insert into tax transaction
            $db->db_connect()->exec("INSERT INTO `tax_trans` (doc, tax_amt,entry_no) values ('GR','$tax_amt','$entry_no')");
            // update po to done
            $db->db_connect()->exec("UPDATE `po_hd` set `grn` = 1 where `doc_no` = '$ref_doc'");

            $anton->set_session(['action=view']);
            if($db->doc_trans('GRN',"$entry_no",'ADD'))
            {
                $anton->done('done');
            } else
            {
                $anton->err("Document Saved but could not generate entry transactions");
            }




        }

        elseif ($function === 'search_grn_item') // find item
        {
            $query = $anton->post('search_query');
            $supp_id = $anton->post('supp_id');
            $search_result = $db->grn_list_item($query,$supp_id);
            var_dump($search_result);
            echo("DONE");
        }

        elseif ($function === 'line_tax') // calculate line tax
        {
            $tax_class = $anton->post('tax_class');
            $value = floatval($anton->post('value'));

//            print_r($_POST);

            $anton->done(number_format($db->input_tax($value,$tax_class),2));
        }

        elseif ($function === 'print_grn') // print grn
        {
            $entry_no = $anton->post('entry_no');
            $grn_hd = $db->get_rows('grn_hd',"`entry_no` = '$entry_no'");
            $grn_trans = $db->db_connect()->query("SELECT * FROm grn_trans where entry_no = '$entry_no'");

            if($grn_hd['status'] == '0')
            {
                $approved = 'Pending';
            }
            elseif ($grn_hd['status'] == '1')
            {

            }

        }

    }