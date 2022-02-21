<?php
    include_once 'backend/includes/core.php';
    include_once  'backend/includes/pdf/fpdf.php';

    $pdf = new FPDF('P','pt','pos');
    $pdf->AddPage();
    $pdf->SetRightMargin(1);
    $pdf->SetLeftMargin(1);
    $pdf->SetFont('Arial','B',15);
    $pdf->Ln();
    $pdf->Cell(0,10,'HOLD BILL','','1','C','');

    $pdf->Ln(10);
    $pdf->Cell(0,10,'--------','','1','C','');
    $pdf->Ln(10);

    $pdf->SetFont('Arial','i',8);
    $pdf->Cell(0,10,'SNEDA SHOPPING CENTER','','1','C','');
    $pdf->Cell(0,10,'SPINTEX ROAD','','1','C','');
    $pdf->Cell(0,10,'ACCRA , GHANA','','1','C','');
    $pdf->Cell(0,10,'MOB : 054 631 0011','','1','C','');
    $pdf->Cell(0,10,'Email : info@domain.com','','1','C','');

    $pdf->SetFont('Arial','i',6);
    $pdf->Ln();
    $pdf->Cell(0,10,'Hold By: Jane Doe','','','','');
    $pdf->Cell(0,10,'Time : '.date('Y-m-d H:m-s'),'','','R','');

    $pdf->SetFont('Arial','B',25);
    $pdf->Ln(50);
    $pdf->Cell(0,10,'1234','','1','C','');
    $pdf->Ln(50);

    //$pdf->Image('barcode.png',null,null,150,20);

    $pdf->Output('F','test.pdf');