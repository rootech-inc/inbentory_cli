<?php

    require '../../includes/core.php';
    //print_r($_POST);

    if(isset($_POST['function']))
    {
        $function = $anton->post('function');

        if($function === 'new_item') // add new item
        {
            $category = $anton->post('category');
            $sub_category = $anton->post('sub_category');
            $supplier = $anton->post('supplier');
            $barcode = $anton->post('barcode');
            $description = $anton->post('description');
            $short_description = $anton->post('short_description');
            $packaging = $anton->post('packaging');
            $stock_type = $anton->post('stock_type');
            $expiry_date = $anton->post('expiry_date');
            $tax = $anton->post('tax');
            $cost_price = $anton->post('cost_price');
            $retail_without_tax = $anton->post('retail_without_tax');
            $retail_with_tax = $anton->post('retail_with_tax');
            $pack_qty = $anton->post('pack_qty');
            $expiry = $anton->post('expiry');
            $uni = md5($description.$barcode.$today.$myName);

            // check if barcode
            if($db->row_count('prod_master',"`barcode` = '$barcode'") > 0)
            {
                $anton->err("Can's insert barcode duplicate");
                exit();
            }

            if($expiry == '1')
            {
                if(!isset($_POST['expiry_date']) || empty($_POST['expiry_date']))
                {
                    $anton->err('Please Select Expiry Date');
                    exit();
                }
            }

            // check if item with same name exist
            if($db->row_count('prod_master',"`item_desc` = '$description'") > 0)
            {
                $anton->err("Item Exist");
                exit();
            }

            if($supplier == '0')
            {
                $anton->err('Select Supplier');
                exit();
            }



            if($db->row_count('item_group',"`id` = '$category'") < 1)
            {
                $anton->err("Group Does Not Exist");
                exit();
            }

//            print_r($_POST);
//            die();

            // check supplier

            // insert into product master
            $product_master = "INSERT INTO `prod_master` 
                            (
                             `group`,`sub_group`,`supplier`,
                           `barcode`,`item_desc`,`item_desc1`,
                             `cost`,`retail`,`tax`,`packing`,
                             `stock_type`,`expiry_date`,`prev_retail`,`item_uni`,`owner`
                             ) values 
                             (
                              '$category','$sub_category','$supplier','$barcode','$description','$short_description','$cost_price',
                              '$retail_with_tax','$tax','$packaging','$stock_type','$expiry_date','$retail_with_tax','$uni','$myName'
                             )";


            try { // insert into product master
                $db->db_connect()->exec($product_master);
            } catch (PDOException $e){
                $anton->err($e->getMessage());
                exit();
            }

            // insert into barcode
            $prod_detail_code = $db->get_rows("prod_master","`barcode` = '$barcode'")['item_code'];
            $item_code = $prod_detail_code;
            $barcode_sql = "INSERT INTO `barcode` (`item_code`,`barcode`,`item_desc`,`item_desc1`,`retail`) values 
                ('$prod_detail_code','$barcode','$description','$short_description','$retail_with_tax')";

            // insert into prices
            $prices = "INSERT INTO `price_change` (`item_code`,`price_type`,`previous`,`current`) values ('$prod_detail_code','r','$retail_with_tax','$retail_with_tax')";

            try { // insert into barcode
                $db->db_connect()->exec($barcode_sql);
            } catch (PDOException $e){
                // delete product
                $db->delete('prod_master',"`uni` = '$uni'");

                $anton->err($e->getMessage());
                exit();
            }

            try { // insert into price change
                $db->db_connect()->exec($prices);
            } catch (PDOException $e){
                // delete from products and also from barcode
                $db->delete('prod_master',"`uni` = '$uni'");
                $db->delete('barcode',"`item_code` = '$prod_detail_code'");
                $anton->err($e->getMessage());
                exit();
            }

            // insert packing
            $pack_desc = $db->get_rows('packaging', "`id` = '$packaging'")['desc'];
                $prod_packing = "insert into prod_packing (`item_code`, `pack_id`, `qty`,`pack_desc`,`purpose`)values 
                                                                   ('$prod_detail_code','$packaging','$pack_qty','$pack_desc',1),
                                                                   ('$prod_detail_code','$packaging','$pack_qty','$pack_desc',2);";
            // insert expiry
            if($expiry == '1')
            {
                $db->delete('prod_expiry',"`item_code` = '$item_code'"); // delete expiry
                $expiry_sql = "insert into prod_expiry (item_code, expiry_date)values ('$item_code','$expiry_date')";
                $db->db_connect()->exec($expiry_sql);
            }
            try { // insert into packages
                // delete Packing
                $db->delete('prod_packing',"`item_code` = '$item_code'");
                $db->db_connect()->exec($prod_packing);
            } catch (PDOException $e){

                $anton->err($e->getMessage());
                exit();
            }

            $anton->set_session(['action=view']);
            $anton->done("prod_added");


        }
    }
