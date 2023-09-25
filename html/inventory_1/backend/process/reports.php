<?php
    require '../includes/core.php';
    $req_method = $_SERVER['REQUEST_METHOD'];
    $response = ['status'=>000,'message'=>000];

    if($req_method === 'POST')
    {
        if(isset($_POST['function']))
        {

            $function = $anton->post('function');
            if($function === 'eod'){
                // TAKE EOD
                $clerk_code = $anton->post('clerk_code');
                $clerk_key = $anton->post('clerk_key');
                // admin authenticate
                $admin_auth = $auth->adminAuth($clerk_code,$clerk_key);
                if($admin_auth['code'] === 200)
                {
                    // access granted
                    $Reports->eod();

                } else {
                    echo $anton->json_enc($admin_auth);
                    // access denied
                }

            } elseif ($function === 'z_report'){

                // Z REPORT
                $clerk_code = $anton->post('clerk_code');
                $clerk_key = $anton->post('clerk_key');
                $recId = $anton->post('recId');
                // admin authenticate
                $admin_auth = $auth->adminAuth($clerk_code,$clerk_key);
                if($admin_auth['code'] === 200)
                {
                    // access granted
                    $zreport = $Reports->z_report($recId);
                    if($zreport['code'] === 202)
                    {
                        include '../includes/print.php';
                        // print z details
                        //printzreport($recId);
                    } else {
                        // roll back data
                    }

                    echo json_encode($zreport);

                } else {
                    echo $anton->json_enc($admin_auth);
                    // access denied
                }
            }
            elseif ($function === 'print_availability'){
                // get details
                $location = $anton->post('loc_id');
                $as_of = $anton->post('as_of');
                $loc = $location;

                class PDF extends FPDF
                {

                    // Page header
                    function Header()
                    {
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

                $pdf = new PDF('L','mm','A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                // Logo
                $pdf->Image(logo,10,6,30,);
                // Arial bold 15
                $pdf->SetFont('Arial','B',12);
                // Move to the right
                $pdf->SetFont('Arial','B',20);
                $pdf->Cell(90,10,'',0,0,'C');
                $pdf->Cell(112,15,"ITEM AVAILABILITY REPORT",1,0,'C');
                $pdf->Cell(90,10,'',0,1,'C');
                $pdf->Ln(2);

                $pdf->SetFont('Arial','',10);
                $pdf->Cell(90,10,'',0,0,'C');
                $pdf->Cell(112,15,"LOCATION : $loc",0,0,'L');
                $pdf->Cell(90,10,'',0,1,'L');

                $pdf->SetFont('Arial','',10);
                $pdf->Cell(90,10,'',0,0,'C');
                $pdf->Cell(112,15,"AS OF : $as_of",0,0,'L');
                $pdf->Cell(90,10,'',0,1,'C');
                $pdf->Ln(20);
                // table
                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(90,10,"BARCODE",1,0,"L");
                $pdf->Cell(130,10,"DESCRIPTION",1,0,"L");
                $pdf->Cell(60,10,"AVAIL QTY",1,1,"L");
                $pdf->SetFont('Times','',10);

                $conn = $db->db_connect();
                $sql = "CALL item_availability('$location','$as_of')";
                
                $stmt = $db->db_connect()->query($sql);

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    // get columns
                    $barcode = $row['barcode'];
                    $name = $row['item_desc'];
                    $qty = $row['stock'];
                    
                    // make pdf
                    $pdf->Cell(90,10,$barcode,1,0,"L");
                    $pdf->Cell(130,10,$name,1,0,"L");
                    $pdf->Cell(60,10,$qty,1,1,"L");
                }
                $f_name = "item_availability_$location"."_$as_of".".pdf";
                $f = root."/assets/docs/$f_name";
                $pdf->Output($f,'F');
                $r = array('status'=>'done','file'=>$f_name);
                
                header("Content-Type:Application/Json");
                echo json_encode($r);

            }

        }
    }