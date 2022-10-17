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
            $tax_amt = $anton->post('tax_amt');




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
                          ('$ref_doc','$rec_date','$supp_id','$remarks','$invoice_number',$total_amount,$tax_grp,$tax_amt,'$myName','$loc_id')"
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
//                $prev_c = $item_details['cost'];
//                $o_cost = "INSERT INTO `price_change` (item_code, price_type, previous, current) VALUES ('$item_code','c','$prev_c','$prod_cost')";
//                $prev_r = $item_details['retail'];
//                $o_retail = "INSERT INTO `price_change` (item_code, price_type, previous, current) VALUES ('$item_code','r','$prev_r','$retail')";
//                $db->db_connect()->exec($o_cost);
//                $db->db_connect()->exec($o_retail);
//                // update cost and retail
//                $db->db_connect()->exec("UPDATE `prod_master` SET `cost` = '$prod_cost', `prev_retail` = `retail`, `retail` = '$retail' WHERE `item_code` = '$item_code'");

//                echo "\n Item $item_code has $qty and each price is $price with cost price of $cost and retail of $retail \n";

            }


            $net_amt = $inv_amt + $tax_amt;
            // update invoice amount
            $db->db_connect()->exec("UPDATE `grn_hd` SET `invoice_amt` = '$inv_amt',`net_amt` = '$net_amt', `tax_amt` = '$tax_amt'  WHERE entry_no = '$entry_no'");
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
            $company = $db->get_rows('company','none');
            $grn_status = $grn_hd['status'];
            $supplier = $grn_hd['supplier'];

            if($grn_status == '0')
            {
                $approved = 'Pending';
            }
            elseif ($grn_status == '1')
            {
                // get approved person
                $approved = $db->get_rows('doc_trans',"`doc_type` = `trans_func` = 'ADD' AND 'GRN' AND `entry_no` = '$entry_no'")['created_by'];
            }
            elseif ($grn_status == '-1')
            {
                // get deleted
                $approved = $db->get_rows('doc_trans',"`doc_type` = `trans_func` = 'DEL' AND 'GRN' AND `entry_no` = '$entry_no'")['created_by'];

            }

            class PDF extends FPDF
            {

                // Page header
                function Header()
                {

//                // Logo
//               $this->Image('logo.png',10,6,30,);
//                // Arial bold 15
//                $this->SetFont('Arial','B',12);
//                // Move to the right
//
//                // Title
//                $this->Cell(280,5,$company['c_name'],0,1,'R');
//                $this->Cell(280,5,'VAT : '.$company['vat_code'],0,1,'R');
//                $this->Cell(280,5,$company['box']. ", ".$company['country'].", ".$company['city'].", ".$company['street'],0,1,'R');
//                $this->Cell(280,5,'Tel : '.$company['phone'],0,1,'R');
//                $this->Cell(280,5,'Email : '.$company['email'],0,1,'R');
//
//                $this->SetFont('Arial','B',20);
//                $this->Cell(93,10,'',0,0,'C');
//                $this->Cell(93,15,'PURCHASE ORDER',1,0,'C');
//                $this->Cell(93,10,'',0,1,'C');
//
//                $this->SetFont('Arial','B',12);
//                $this->Cell(140,10,"Delivery Details",0,0,'L');
//                $this->Cell(140,10,"Order Details",0,1,'R');
//                $this->SetFont('Arial','',12);

                    // branch % date
//                $this->Cell(140,5,"Delivery Date : 00/00/0000",0,0,'L');
//                $this->Cell(140,5,"Date : ".$po_hd['created_on'],0,1,'R');
//                # branch & order number
//                $this->Cell(140,5,"Branch : Test Branch",0,0,'L');
//                $this->Cell(140,5,"Order No : $po_number",0,1,'R');
//                # address & Supplier Code
//                $this->Cell(140,5,"Address : PO BOX 150",0,0,'L');
//                $this->Cell(140,5,"Supplier Code : ".$po_hd['suppler'],0,1,'R');
//                # city & Suppler Name
//                $this->Cell(140,5,"City : Accra , Adenta",0,0,'L');
//                $this->Cell(140,5,"Supplier Name : ".$db->get_rows('supp_mast',"`supp_id` = '$supplier'")['supp_name'],0,1,'R');
//                #empty and total amount
//                $this->Cell(140,5,"",0,0,'L');
//                $this->Cell(140,5,"Total Cost : ".number_format($po_hd['total_amount'],2),0,1,'R');

                    // Line break
//                $this->Ln(20);
//                // table
//                $this->SetFont('Arial','B',12);
//                $this->Cell(40,10,"Item Code",1,0,"L");
//                $this->Cell(40,10,"Description",1,0,"L");
//                $this->Cell(40,10,"Pack Id",1,0,"L");
//                $this->Cell(40,10,"Pack Desc",1,0,"L");
//                $this->Cell(40,10,"Quantity",1,0,"L");
//                $this->Cell(40,10,"Cost",1,0,"L");
//                $this->Cell(40,10,"Total Cost",1,1,"L");
                    // Line break
                }

                // Page footer
                function Footer()
                {
                    // Position at 1.5 cm from bottom
                    $this->SetY(-15);
                    // Arial italic 8
                    $this->SetFont('Arial','I',8);
                    // Page number
                    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
                }
            }

            // Instantiation of inherited class
            $pdf = new PDF('L','mm','A4');
            $pdf->AliasNbPages();
            $pdf->AddPage();

            // Logo
//            $pdf->Image('logo.png',10,6,30,);
            // Arial bold 15
            $pdf->SetFont('Arial','B',12);
            // Move to the right

            // Title
//            $pdf->Cell(280,5,$company['c_name'],0,1,'R');
//            $pdf->Cell(280,5,'VAT : '.$company['vat_code'],0,1,'R');
//            $pdf->Cell(280,5,$company['box']. ", ".$company['country'].", ".$company['city'].", ".$company['street'],0,1,'R');
//            $pdf->Cell(280,5,'Tel : '.$company['phone'],0,1,'R');
//            $pdf->Cell(280,5,'Email : '.$company['email'],0,1,'R');

            $pdf->SetFont('Arial','B',20);
            $pdf->Cell(93,10,'',0,0,'C');
            $pdf->Cell(93,15,'Goods Received Note',1,0,'C');
            $pdf->Cell(93,10,'',0,1,'C');

            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(140,10,"Delivery Details",0,0,'L');
            $pdf->Cell(140,10,"Order Details",0,1,'R');
            $pdf->SetFont('Arial','',12);

            // branch % date
            $pdf->Cell(140,7,"GRN No : $entry_no",0,0,'L');
            $pdf->Cell(140,7,"Rec Date : ".$grn_hd['date_received'],0,1,'R');
            # branch & order number
            $pdf->Cell(140,7,"PO No : ".$grn_hd['po_number'],0,0,'L');
            $pdf->Cell(140,7,"Inv No : ".$grn_hd['invoice_num'],0,1,'R');
            # address & Supplier Code
            $pdf->Cell(140,7,"Entry Date : ".$grn_hd['created_on'],0,0,'L');
            $pdf->Cell(140,7,"Inv Amt : ".number_format($grn_hd['invoice_amt'],2),0,1,'R');

            # city & Suppler Name
            $loc = $grn_hd['loc'];
            $location = $db->get_rows('loc',"`loc_id` = '$loc'")['loc_desc'];

            $pdf->Cell(140,7,"Location : $loc - $location",0,0,'L');
            $pdf->Cell(140,7,"Tax Amt : ".number_format($grn_hd['tax_amt'],2),0,1,'R');

            # Supplier
            $supp = $grn_hd['supplier'];
            $supplier = $db->get_rows('supp_mast',"`supp_id` = '$supp'")['supp_name'];
            $pdf->Cell(140,7,"Suppler : $supplier",0,0,'L');
            $pdf->Cell(140,7,"Net Amt : ".number_format($grn_hd['net_amt'],2),0,1,'R');
            # remarks
            $pdf->Cell(100,7,"Remarks : " . $grn_hd['remarks'],0,1,'L');


            // Line break
            $pdf->Ln(20);

            // table
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(10,10,"LN",1,0,"L");
            $pdf->Cell(40,10,"Item",1,0,"L");
            $pdf->Cell(43,10,"Description",1,0,"L");
            $pdf->Cell(23,10,"Pack ID",1,0,"L");
            $pdf->Cell(23,10,"Packing",1,0,"L");
            $pdf->Cell(20,10,"QTY",1,0,"L");
            $pdf->Cell(23,10,"Price",1,0,"L");
            $pdf->Cell(23,10,"Inv Amt",1,0,"L");
            $pdf->Cell(23,10,"Tax Amt",1,0,"L");
            $pdf->Cell(23,10,"Net Amt",1,0,"L");
            $pdf->Cell(23,10,"Retail",1,1,"L");

            $pdf->SetFont('Times','',10);
            // get grn trans
            $grn_trans = $db->db_connect()->query("SELECT * FROM grn_trans WHERE entry_no = '$entry_no'");
            $grn_ln = 0;
            while ($grn_tran = $grn_trans->fetch(PDO::FETCH_ASSOC))
            {
                $grn_ln ++;
                $pdf->Cell(10,10,"$grn_ln",1,0,"L");
                $pdf->Cell(40,10,$grn_tran['barcode'],1,0,"L");
                $pdf->Cell(43,10,$grn_tran['item_description'],1,0,"L");
                $pdf->Cell(23,10,$grn_tran['packing'],1,0,"L");
                $pdf->Cell(23,10,$grn_tran['pack_desc'],1,0,"L");
                $pdf->Cell(20,10,$grn_tran['qty'],1,0,"L");
                $pdf->Cell(23,10,$grn_tran['cost'],1,0,"L");
                $pdf->Cell(23,10,$grn_tran['total_cost'],1,0,"L");
                $pdf->Cell(23,10,$grn_tran['tax_amt'],1,0,"L");
                $pdf->Cell(23,10,$grn_tran['net_amt'],1,0,"L");
                $pdf->Cell(23,10,$grn_tran['ret_amt'],1,1,"L");
            }

            // signs box
            $pdf->Ln(20);
            $pdf->SetFont('Times','B',15);
            $pdf->Cell(80,10,$grn_hd['created_by'],1,0,'C');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(80,10,$approved,1,1,'C');
            // sign text
            $pdf->SetFont('Times','',12);
            $pdf->Cell(80,10,'Created By',0,0,'C');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(80,10,'Approved By',0,1,'C');

            $pdf->Output("$entry_no.pdf",'F');
            // transactions
            $db->doc_trans('GRN',"$entry_no","PRI");
            $anton->done("$entry_no.pdf");

        }

        elseif ($function === 'update_grn')
        {
            // get values from form
            $entry_no = $anton->post('entry_no');

            # todo 1. delete all transactions
            $db->delete('grn_trans',"`entry_no` = '$entry_no'");

            # todo 2. insert entries as new again
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

            # todo 3. update header details ( inv_amt, tax_amt, net_amt )
            $net_amt = $inv_amt + $tax_amt;
            $db->db_connect()->exec("UPDATE `grn_hd` SET `invoice_amt` = '$inv_amt',`net_amt` = '$net_amt', `tax_amt` = '$tax_amt' WHERE entry_no = '$entry_no'");

            # todo 4. update tax transaction
            $db->delete('tax_trans',"`doc` = 'GRN' AND `entry_no` = '$entry_no'");
            $db->db_connect()->exec("INSERT INTO `tax_trans` (doc, tax_amt,entry_no) values ('GR','$tax_amt','$entry_no')");

            # todo 5. add document transaction
            $db->doc_trans('GRN',"$entry_no",'ED');

            $anton->set_session(['action=view']);
            $anton->done('done');


            // delete all entry number
        }

    }