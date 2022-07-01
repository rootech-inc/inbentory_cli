<?php
    require '../../includes/core.php';
    $function  = $anton->post('function');
    //print_r($function);

    if($function === 'new_po')// adding new po
    {
        // add
        $location = $anton->post('location');
        $supplier = $anton->post('supplier');
        $po_type = $anton->post('po_type');
        $remarks = $anton->post('remarks');
        $total_amount = $anton->post('total_amount');


        if($db->row_count('loc',"`loc_id` = '$location'") != 1) // check location
        {
            $anton->err('Select Location');
            die();
        }



        if($db->row_count('supp_mast',"`supp_id` = '$supplier'") != 1) // check supplier
        {
            $anton->err('Select Supplier');
            die();
        }

        // insert into po_hd and get last id
        $db->db_connect()->beginTransaction();
        $db->db_connect()->exec("LOCK TABLES po_hd WRITE");
        $db->db_connect()->query("insert into po_hd (location, suppler, type, remarks, total_amount, owner)
values ('$location','$supplier','$po_type','$remarks','$total_amount','$myName');");
        # update doc_number
        $db->db_connect()->exec("UPDATE `po_hd` SET `doc_no` = CONCAT('PO',id) ORDER BY `id` DESC LIMIT 1");
        $po_num_query = $db->db_connect()->query("SELECT `id` FROM `po_hd` ORDER BY id DESC LIMIT 1");
        $po_num_res = $po_num_query->fetch(PDO::FETCH_ASSOC);
        $po_id = $po_num_res['id'];
        $po_number = 'PO'.$po_num_res['id'] ;
        # update doc number
        $db->db_connect()->exec("UPDATE `po_hd` SET `doc_no` = '$po_number' WHERE `id` = '$po_id'");
        $db->db_connect()->exec("UNLOCK TABLES");
//        $po_number = $db->db_connect()->lastInsertId();

        $total_cost = 0;
        for ($index = 0; $index < count($_POST['item_code']); $index++)
        {
            $barcode = $_POST['item_code'][$index];
            $item_details = $db->get_rows('prod_master',"`barcode` = '$barcode'");
            $item_code = $item_details['item_code'];
            $item_desc = $_POST['item_desc'][$index];
            $item_pack = $_POST['item_pack'][$index];
            $item_qty = $_POST['item_qty'][$index];
            $item_packing = $_POST['item_packing'][$index];
            $item_cost = $_POST['item_cost'][$index];
            $item_amount = $_POST['item_amount'][$index];

            $total_cost += $item_cost;

            $insert_query = "insert into po_trans 
            (item_code, barcode, item_description, owner, pack_desc, packing, cost,qty, parent,total_cost) values 
            ('$item_code','$barcode','$item_desc','$myName','$item_pack','$item_packing','$item_cost','$item_qty','$po_number','$total_amount')";
//            echo $insert_query;
            $db->db_connect()->exec($insert_query);

        }
        $db->db_connect()->exec("UPDATE po_hd set total_amount = $total_amount");
        $anton->set_session(['action=view']);
        $anton->done("done_reload");

        //print_r($_POST);

    }

    elseif ($function === 'print_po') // print po into pdf
    {
        // make pdf of po
//        require('../../includes/pdf/fpdf.php');
        $po_number = $anton->post('po_number');
        $po_tran_sql = $db->db_connect()->query("SELECT * FROM `po_trans` WHERE `parent` = '$po_number'");
        $po_hd = $db->get_rows('po_hd',"`doc_no` = '$po_number'");
        $company = $db->get_rows('company','none');
        $supplier = $po_hd['suppler'];

        if($po_hd['status'] == '0')
        {
            $apprv = "Not Approved";
        } else
        {
            $apprv = $po_hd['approved_by'];
        }

        $total_quantity = $db->col_sum('po_trans','qty',"`parent` = '$po_number'");
        $total_each_cost = $db->col_sum('po_trans','cost',"`parent` = '$po_number'");
        $total_cost = $db->col_sum('po_trans','total_cost',"`parent` = '$po_number'");

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
        $pdf->Image('logo.png',10,6,30,);
        // Arial bold 15
        $pdf->SetFont('Arial','B',12);
        // Move to the right

        // Title
        $pdf->Cell(280,5,$company['c_name'],0,1,'R');
        $pdf->Cell(280,5,'VAT : '.$company['vat_code'],0,1,'R');
        $pdf->Cell(280,5,$company['box']. ", ".$company['country'].", ".$company['city'].", ".$company['street'],0,1,'R');
        $pdf->Cell(280,5,'Tel : '.$company['phone'],0,1,'R');
        $pdf->Cell(280,5,'Email : '.$company['email'],0,1,'R');

        $pdf->SetFont('Arial','B',20);
        $pdf->Cell(93,10,'',0,0,'C');
        $pdf->Cell(93,15,'PURCHASE ORDER',1,0,'C');
        $pdf->Cell(93,10,'',0,1,'C');

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(140,10,"Delivery Details",0,0,'L');
        $pdf->Cell(140,10,"Order Details",0,1,'R');
        $pdf->SetFont('Arial','',12);

        // branch % date
        $pdf->Cell(140,7,"Delivery Date : 00/00/0000",0,0,'L');
        $pdf->Cell(140,7,"Date : ".$po_hd['created_on'],0,1,'R');
        # branch & order number
        $pdf->Cell(140,7,"Branch : Test Branch",0,0,'L');
        $pdf->Cell(140,7,"Order No : $po_number",0,1,'R');
        # address & Supplier Code
        $pdf->Cell(140,7,"Address : PO BOX 150",0,0,'L');
        $pdf->Cell(140,7,"Supplier Code : ".$po_hd['suppler'],0,1,'R');
        # city & Suppler Name
        $pdf->Cell(140,7,"City : Accra , Adenta",0,0,'L');
        $pdf->Cell(140,7,"Supplier Name : ".$db->get_rows('supp_mast',"`supp_id` = '$supplier'")['supp_name'],0,1,'R');
        #empty and total amount
        $pdf->Cell(140,7,"",0,0,'L');
        $pdf->Cell(140,7,"Total Cost : ".number_format($po_hd['total_amount'],2),0,1,'R');

        // Line break
        $pdf->Ln(20);

        // table
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,10,"Item Code",1,0,"L");
        $pdf->Cell(40,10,"Description",1,0,"L");
        $pdf->Cell(40,10,"Pack Id",1,0,"L");
        $pdf->Cell(40,10,"Pack Desc",1,0,"L");
        $pdf->Cell(40,10,"Quantity",1,0,"L");
        $pdf->Cell(40,10,"Cost",1,0,"L");
        $pdf->Cell(40,10,"Total Cost",1,1,"L");
        $pdf->SetFont('Times','',10);

        while($po_t = $po_tran_sql->fetch(PDO::FETCH_ASSOC))
        {
            $item_code = $po_t['item_code'];
            $pack_desc = $db->get_rows('prod_packing',"`item_code` = '$item_code' AND `purpose` = '2'")['pack_id'];
            $pack_desc_x = $db->get_rows('packaging',"`id` = '$pack_desc'")['desc'];

            $pdf->Cell(40,7,$po_t['item_code'],1,0,"L");
            $pdf->Cell(40,7,$po_t['item_description'],1,0,"L");
            $pdf->Cell(40,7,$po_t['pack_desc'],1,0,"L");
            $pdf->Cell(40,7,$po_t['packing'],1,0,"L");
            $pdf->Cell(40,7,$po_t['qty'],1,0,"L");
            $pdf->Cell(40,7,$po_t['cost'],1,0,"L");
            $pdf->Cell(40,7,$po_t['total_cost'],1,1,"L");
            // Line break
        }


            $pdf->Cell(160,7);
            $pdf->Cell(40,7,number_format($total_quantity,2),1,0,'L');
            $pdf->Cell(40,7,number_format($total_each_cost,2),1,0,'L');
            $pdf->Cell(40,7,number_format($total_cost,2),1,1,'L');

            $pdf->Ln(10);
            $pdf->SetFont('Times','I',10);
            $pdf->Cell(0,5,"PLEASE NOTE, WE ARE PLEASED TO INFORM YOU THAT WE HAVE PLACED AN ORDER FOR THE ITEM AS PER THE ATTACHMENT.",0,1,'C');
            $pdf->Cell(0,5,"This order has to be delivered within five working days of the order date otherwise purchase order will ",0,1,'C');
            $pdf->Cell(0,5,"be treated as canceled order. ",0,1,'C');
            $pdf->Cell(0,5,"Please contact the warehouse manager / procurement : 020 000 00000 ",0,1,'C');

            // signs box
            $pdf->Ln(20);
            $pdf->SetFont('Times','B',15);
            $pdf->Cell(80,10,$po_hd['owner'],1,0,'C');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(80,10,$apprv,1,1,'C');
            // sign text
            $pdf->SetFont('Times','',12);
            $pdf->Cell(80,10,'Created By',0,0,'C');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(40,10,'',0,0,'L');
            $pdf->Cell(80,10,'Approved By',0,1,'C');


        $pdf->Output("test.pdf",'F');
        $db->doc_trans('PO',"$po_number","PRI");
        echo 'done';
    }

    elseif ($function == 'update_po') // update po
    {
        $po_number = $anton->get_session('po_number');
        if(empty($po_number)) // validate if there is a po number
        {
            $anton->err("Could ot set PO Number");
            die();
        }

        if($db->row_count('po_hd',"`doc_no` = '$po_number'") !== 1) // validate if po exist
        {
            $anton->err("Document $po_number cannot be found");
            die();
        }

        // get values from form and validate
        $loc_id = $anton->post('loc_id');
        if(empty($loc_id)) // validate if there is a po number
        {
            $anton->err("Invalid Location");
            die();
        }

        $remarks = $anton->post('remarks');


        // get sum of po items in values
        $total_cost = $db->col_sum('po_trans','total_cost',"`parent` = '$po_number'");


        $update_details = array(
            'table' => 'po_hd',
            'columns' => array('location','remarks','total_amount','edited_by','edited_on'),
            'values' => array($loc_id,$remarks,$total_cost,$myName,$current_time),
            'condition' =>  "`doc_no` = '$po_number'"
        );

        if($db->update_record($update_details))
        {
            // unset po_numer and set action to view
            $anton->set_session(['action=view']);
            unset($_SESSION['po_number']);
            $anton->done('done_reload');
        } else
        {
            $anton->err("Could not save PO");
        }


    }

