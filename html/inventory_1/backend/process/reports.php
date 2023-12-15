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

            }
            elseif ($function === 'z_report'){

                // Z REPORT
                $clerk_code = $anton->post('clerk_code');
                $clerk_key = $anton->post('clerk_key');
                $recId = $anton->post('recId');
                // admin authenticate
                $admin_auth = $auth->adminAuth($clerk_code,$clerk_key);
                if($admin_auth['code'] === 200)
                {
                    include '../includes/print.php';
                       
                    
                    // access granted
                    $zreport = $Reports->z_report($recId);
                    if($zreport['code'] === 202)
                    {
                        //include '../includes/print.php';
                        // print z details
                        $zreport = printzreport($recId);
                        
                    } else {
                        // roll back data
                    }

                    echo json_encode($zreport);

                } else {
                    echo $anton->json_enc($admin_auth);
                    // access denied
                }
            }
            elseif ($function === 'take_eod'){
                require '../../api/ApiResponse.php';
                require '../includes/print.php';
                $response = (new API\ApiResponse());
                try{

                    $sale_date = $anton->post('sale_date');

                    // validate eod again
                    $eod_count = $db->row_count('eod_serial',"`sales_date` = '$sale_date'");
                    $open_shifts = $db->row_count('shifts',"`shift_date` = '$sale_date' AND `end_time` is NULL");
                    if ($eod_count === 1){

                        // check if shift still open for other machines
                        if($open_shifts > 0){
                            $response->error("You have $open_shifts shifts(s) still open");
                        } else {

                            // take report
                            print_eod($sale_date);
                            $close = (new \billing\shift())->closeEod($sale_date);
                            $code = $close['status_code'];
                            $message = $close['message'];
                            if($code === 200){
                                $response->error($message);
                            } else {
                                $response->success("COULD NOT CLOSE EOD: $message");
                            }

                        }

                    } else {
                        $response->error("Invalidate sales date");
                    }



                } catch (\Exception $e){
                    $response->error($e->getMessage());
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

                $pdf = new PDF('P','mm','A4');
                $pdf->AliasNbPages();
                $pdf->AddPage('P',[210,297]);

                // Logo
                $pdf->Image(logo,10,6,30,);
                // Arial bold 15
                $pdf->SetFont('Arial','B',12);
                // Move to the right
                $pdf->SetFont('Arial','B',20);
                $pdf->Cell(190,15,"ITEM AVAILABILITY REPORT",0,1,'R');

//                $pdf->Ln(2);

                $pdf->SetFont('Arial','',10);
                $pdf->Cell(190,5,"LOCATION : $loc",0,1,'R');

                $pdf->SetFont('Arial','',10);
                $pdf->Cell(190,5,"AS OF : $as_of",0,1,'R');
                $pdf->Ln(20);
                // table
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(30,5,"BARCODE",1,0,"L");
                $pdf->Cell(140,5,"DESCRIPTION",1,0,"L");
                $pdf->Cell(20,5,"QUANTITY",1,1,"L");
                $pdf->SetFont('Times','',5);

                $conn = $db->db_connect();
                $sql = "CALL item_availability('$location','$as_of')";
                
                $stmt = $db->db_connect()->query($sql);

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    // get columns
                    $barcode = $row['barcode'];
                    $name = $row['item_desc'];
                    $qty = $row['stock'];
                    
                    // make pdf
                    $pdf->Cell(30,5,$barcode,1,0,"L");
                    $pdf->Cell(140,5,$name,1,0,"L");
                    $pdf->Cell(20,5,$qty,1,1,"L");
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