<?php

    require '../../includes/core.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        class MyPDF extends FPDF {
            // Header function to create the two-column header


            // Page footer function (optional)
            function Footer() {
                // Set position at 1.5 cm from bottom
                $this->SetY(-15);

                // Set font and font size
                $this->SetFont('Arial', 'I', 8);

                // Page number
                $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
            }
        }


        $anton = (new anton());
        $db =(new \db_handeer\db_handler());
        $doc = $anton->post('doc');
        $entry = $anton->post('entry');
        $response = array(
            'status_code'=>000,
            'message'=>"Initializing"
        );
        if($doc === 'profoma'){// print proforma
            //check if entry exist
            if($db->row_count('prof_hd',"entry_no = '$entry'") === 1){
                // get records
                $hd = $db->get_rows('prof_hd',"entry_no = '$entry'");
                $cust_id = $hd['customer'];
                $customer = $db->get_rows('customers',"`customer_id` = '$cust_id'");
                // make header
                $pdf = new MyPDF('P', 'mm', 'A4'); // Page orientation (Portrait), unit (mm), page format (A4)
                $pdf->AliasNbPages(); // Set total page number for footer
                $pdf->AddPage();

                // Set document title (optional)
                $pdf->SetTitle('Your Document Title');

                // Set font and font size
                $pdf->SetFont('Arial', 'B', 18);
                $pdf->Cell(190,10,company_name,0,1,'C');
                $pdf->SetFont('Arial', '', 15);
                $pdf->Cell(190,10,'PROFORMA INVOICE',0,1,'C');
                $pdf->Cell(190,10,'TIN-192129',0,1,'C');
                $yPos = 40; // Adjust the Y position for line placement
                $pdf->SetDrawColor(0, 0, 0); // Set line color to black (RGB)
                $pdf->Line(0, $yPos, 210, $yPos); // X1, Y1, X2, Y2 (left, top, right, top)

                $width = 95;
                // Calculate column widths (assuming equal columns)

                $pdf->SetFont('Arial', 'B', 12);
                // Left column content
                $pdf->Cell($width, 10, 'Delivery Info.', 0, 0, 'L');

                // Right column content
//                $pdf->Cell($width, 10, 'Customer Info', 0, 0, 'R');

                // Line break for next content
                $pdf->Ln(10);

                // Your content for the rest of the PDF
                $pdf->SetFont('Arial', '', 10); // Adjust font for content
                $pdf->Cell($width, 5, "Entry : ".$hd['entry_no'], 0, 0);
                $tax = "NO";
                if($hd['taxable'] === 1){
                    $tax = 'YES';
                }
                $pdf->Cell($width, 5, "Taxable : $tax", 0, 1,'R');

                // issue date
                $pdf->Cell($width, 5, "Customer : ".$customer['first_name'] . " " . $customer['last_name'], 0, 0);
                $pdf->Cell($width, 5, "Net Amt.: " .$hd['net_amt'] , 0, 1,'R');

                // due date
                $pdf->Cell($width, 5, "Phone : " .$customer['phone_number'], 0, 0);
                $pdf->Cell($width, 5, "Tax Amt. : " .$hd['tax_amt'], 0, 1,'R');


                $address = $customer['country'] . ", ".$customer['city'];
                $pdf->Cell($width, 5, "Address : $address", 0, 0,'L');
                $pdf->Cell($width, 5, "Other Cost : ".$hd['other_cost'], 0, 1,"R");

                $pdf->Cell($width, 5, $customer['address'], 0, 0,'L');
                $pdf->Cell($width, 5, "Gross : ".$hd['gross_amt'], 0, 1,"R");

                $file = uniqid('pdf_') . '.pdf';
                $pdf->Ln(10);


                $trans_query = "SELECT * FROM prof_tran where entry_no = '$entry'";
                $tran_stmt = $db->db_connect()->prepare($trans_query);
                $tran_stmt->execute();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(10,5,"SN",1,0,"L");
                $pdf->Cell(30,5,"BARCODE",1,0,"L");
                $pdf->Cell(45,5,"Description",1,0,"L");
                $pdf->Cell(15,5,"Pack",1,0,"L");
                $pdf->Cell(15,5,"Pack Qty",1,0,"L");
                $pdf->Cell(15,5,"Quantity",1,0,"L");
                $pdf->Cell(15,5,"Cost",1,0,"L");
                $pdf->Cell(15,5,"Net",1,0,"L");
                $pdf->Cell(15,5,"Tax",1,0,"L");
                $pdf->Cell(15,5,"Gross",1,1,"L");

                $pdf->SetFont('Arial', '', 5);
                while($tran = $tran_stmt->fetch(PDO::FETCH_ASSOC)){
                    // do each line
                    $pdf->Cell(10,3,$tran['line_no'],1,0,"L");
                    $pdf->Cell(30,3,$tran['barcode'],1,0,"L");
                    $pdf->Cell(45,3,$tran['item_desc'],1,0,"L");
                    $pdf->Cell(15,3,trim($tran['packing']),1,0,"L");
                    $pdf->Cell(15,3,$tran['pack_qty'],1,0,"L");
                    $pdf->Cell(15,3,$tran['tran_qty'],1,0,"L");
                    $pdf->Cell(15,3,number_format($tran['unit_cost'],2),1,0,"L");
                    $pdf->Cell(15,3,number_format($tran['net_cost'],2),1,0,"L");
                    $pdf->Cell(15,3,number_format($tran['tax_amt'],2),1,0,"L");
                    $pdf->Cell(15,3,number_format($tran['gross_amt'],2),1,1,"L");
                }


                $pdf->Output($file, 'F'); // Output PDF to a file

                $response['status_code'] = 200;
                $response['message'] = $file;
            }
            else {
                $response['status_code'] = 404;
                $response['message'] = "No entry found";
            }

        }

        else if ($doc === 'invoice'){
            if($db->row_count('invoice_hd',"entry_no = '$entry'") === 1){
                // get records
                $hd = $db->get_rows('invoice_hd',"entry_no = '$entry'");
                $cust_id = $hd['customer'];
                $customer = $db->get_rows('customers',"`customer_id` = '$cust_id'");
                // make header
                $pdf = new MyPDF('P', 'mm', 'A4'); // Page orientation (Portrait), unit (mm), page format (A4)
                $pdf->AliasNbPages(); // Set total page number for footer
                $pdf->AddPage();

                // Set document title (optional)
                $pdf->SetTitle('Your Document Title');

                // Set font and font size
                $pdf->SetFont('Arial', 'B', 18);
                $pdf->Cell(190,10,company_name,0,1,'C');
                $pdf->SetFont('Arial', '', 15);
                $pdf->Cell(190,10,'SALES INVOICE',0,1,'C');
                $pdf->Cell(190,10,'TIN-192129',0,1,'C');
                $yPos = 40; // Adjust the Y position for line placement
                $pdf->SetDrawColor(0, 0, 0); // Set line color to black (RGB)
                $pdf->Line(0, $yPos, 210, $yPos); // X1, Y1, X2, Y2 (left, top, right, top)

                $width = 95;
                // Calculate column widths (assuming equal columns)

                $pdf->SetFont('Arial', 'B', 12);
                // Left column content
                $pdf->Cell($width, 10, 'Delivery Info.', 0, 0, 'L');

                // Right column content
//                $pdf->Cell($width, 10, 'Customer Info', 0, 0, 'R');

                // Line break for next content
                $pdf->Ln(10);

                // Your content for the rest of the PDF
                $pdf->SetFont('Arial', '', 10); // Adjust font for content
                $pdf->Cell($width, 5, "Entry : ".$hd['entry_no'], 0, 0);
                $tax = "NO";
                if($hd['taxable'] === 1){
                    $tax = 'YES';
                }
                $pdf->Cell($width, 5, "Taxable : $tax", 0, 1,'R');

                // issue date
                $pdf->Cell($width, 5, "Customer : ".$customer['first_name'] . " " . $customer['last_name'], 0, 0);
                $pdf->Cell($width, 5, "Net Amt.: " .$hd['net_amt'] , 0, 1,'R');

                // due date
                $pdf->Cell($width, 5, "Phone : " .$customer['phone_number'], 0, 0);
                $pdf->Cell($width, 5, "Tax Amt. : " .$hd['tax_amt'], 0, 1,'R');


                $address = $customer['country'] . ", ".$customer['city'];
                $pdf->Cell($width, 5, "Address : $address", 0, 0,'L');
                $pdf->Cell($width, 5, "Other Cost : ".$hd['other_cost'], 0, 1,"R");

                $pdf->Cell($width, 5, $customer['address'], 0, 0,'L');
                $pdf->Cell($width, 5, "Gross : ".$hd['gross_amt'], 0, 1,"R");

                $file = uniqid('inv_') . '.pdf';
                $pdf->Ln(10);


                $trans_query = "SELECT * FROM invoice_tran where entry_no = '$entry'";
                $tran_stmt = $db->db_connect()->prepare($trans_query);
                $tran_stmt->execute();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(10,5,"SN",1,0,"L");
                $pdf->Cell(30,5,"BARCODE",1,0,"L");
                $pdf->Cell(45,5,"Description",1,0,"L");
                $pdf->Cell(15,5,"Pack",1,0,"L");
                $pdf->Cell(15,5,"Pack Qty",1,0,"L");
                $pdf->Cell(15,5,"Quantity",1,0,"L");
                $pdf->Cell(15,5,"Cost",1,0,"L");
                $pdf->Cell(15,5,"Net",1,0,"L");
                $pdf->Cell(15,5,"Tax",1,0,"L");
                $pdf->Cell(15,5,"Gross",1,1,"L");

                $pdf->SetFont('Arial', '', 5);
                while($tran = $tran_stmt->fetch(PDO::FETCH_ASSOC)){
                    // do each line
                    $pdf->Cell(10,3,$tran['line_no'],1,0,"L");
                    $pdf->Cell(30,3,$tran['barcode'],1,0,"L");
                    $pdf->Cell(45,3,$tran['item_desc'],1,0,"L");
                    $pdf->Cell(15,3,trim($tran['packing']),1,0,"L");
                    $pdf->Cell(15,3,$tran['pack_qty'],1,0,"L");
                    $pdf->Cell(15,3,$tran['tran_qty'],1,0,"L");
                    $pdf->Cell(15,3,number_format($tran['unit_cost'],2),1,0,"L");
                    $pdf->Cell(15,3,number_format($tran['net_cost'],2),1,0,"L");
                    $pdf->Cell(15,3,number_format($tran['tax_amt'],2),1,0,"L");
                    $pdf->Cell(15,3,number_format($tran['gross_amt'],2),1,1,"L");
                }


                $pdf->Output($file, 'F'); // Output PDF to a file

                $response['status_code'] = 200;
                $response['message'] = $file;
            }
            else {
                $response['status_code'] = 404;
                $response['message'] = "No entry found";
            }
        }

        echo json_encode($response);
    }