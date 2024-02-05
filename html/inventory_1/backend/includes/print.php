<?php

require __DIR__ . '/../../escpos/vendor/autoload.php';

use db_handeer\db_handler;
use mechconfig\MechConfig;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use billing\shift;
use tools\anton;
use billing\Billing;





    class item
    {
        private $name;
        private $price;
        private $dollarSign;

        public function __construct($name = '', $price = '', $dollarSign = false)
        {
            $this -> name = $name;
            $this -> price = $price;
            $this -> dollarSign = $dollarSign;
        }

        public function __toString()
        {
            $rightCols = 15;
            $leftCols = 33;
            if ($this -> dollarSign) {
                $leftCols = $leftCols / 2 - $rightCols / 2;
            }
            $left = str_pad($this -> name, $leftCols) ;

            $sign = ($this -> dollarSign ? '$ ' : '');
            $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
            return "$left$right\n";
        }
    }

    function printHold($hold_no){
        try {
            // Enter the share name for your USB printer here
            $connector = null;
            $connector = new WindowsPrintConnector(printer);

            /* Print a "Hello world" receipt" */
            $printer = new Printer($connector);
            $logo = EscposImage::load(logo, false);

                /* Print top logo */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->graphics($logo);

                /* Name of shop */
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text(company_name);
            $printer->selectPrintMode();
            $printer->setEmphasis(false);
            $printer->feed();
            $printer->text(company_country . ' , ' . company_city);
            $printer->feed();
            $printer->text("Mob : " . company_mob);
            $printer->feed(2);

            $printer->text('Bill Held');
            $printer->feed(2);
            $printer->setTextSize(2, 5);
            $printer -> text("$hold_no\n");
            $printer -> cut();

            /* Close printer */
            $printer -> close();
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }

    function printMessage($message){
        try {
            // Enter the share name for your USB printer here
            $connector = null;
            $connector = new WindowsPrintConnector(printer);

            /* Print a "Hello world" receipt" */
            $printer = new Printer($connector);
            $printer -> text("$message\n");
            $printer -> cut();

            /* Close printer */
            $printer -> close();
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }

    function printCut(){
        $connector = null;
        $connector = new WindowsPrintConnector(printer);
        $printer = new Printer($connector);
        /* Cut the receipt and open the cash drawer */
        $printer -> cut();
        $printer -> close();
    }

    function printbill($billRef){

        if(bill_print) {


            $db_hander = new db_handler();

            $today = today;

            $bill_hd_count = "`billRef` = '$billRef'";
            if ($db_hander->row_count('bill_header', $bill_hd_count) > 0) {

                $bill_header = $db_hander->get_rows('bill_header', $bill_hd_count);
                $curRef = $bill_header['billRef'];
                $billRef = $bill_header['billRef'];
                $billSummary = (new Billing())->billSummaryV2($billRef);
                $head = $billSummary['bill_header'];
                $bill_number = $bill_header['bill_no'];




                $payment = $bill_header['pmt_type'];
                $bill_total = (new Billing())->billTotal($bill_number, $today);
                $tran_qty = $bill_total['tran_qty'];
                $taxable_amt = number_format($bill_total['taxable_amt'], 2);
                $tax_amt = number_format($bill_total['tax_amt'], 2);
                $bill_amt = number_format($bill_total['bill_amt'], 2);
                $amt_paid = number_format($bill_header['amt_paid'], 2);
                $amt_bal = number_format($bill_header['amt_bal'], 2);

                $items = array();

                $bill_trans_count = "`billRef` = '$billRef'";
                

                $bill_sql = $db_hander->db_connect()->query("SELECT * FROM bill_trans WHERE $bill_trans_count");
                $billSn = 0;
                while ($row = $bill_sql->fetch(PDO::FETCH_ASSOC)) {
                    $billSn++;
                    $item_qty = $row['item_qty'];
                    $item_barcode = $row['item_barcode'];
                    if ($billSn > 9) {
                        $bq = "   $item_barcode QTY : $item_qty";
                    } else {
                        $bq = "  $item_barcode QTY : $item_qty";
                    }

                    $item_name = $row['item_desc'];
                    $item_desc = "$billSn $item_name";
                    $price = number_format($row['bill_amt'], 2);

                    $items[] = new item(substr($item_desc, 0, 30), number_format($price, 2)); // push item desc and cost
                    $items[] = new item($bq, ''); // push barcode Quantity
                }

                $subtotal = new item('Subtotal', '12.95');

                $non_taxable = new item('Non-Taxable', $head['NON_TAXABLE_AMOUNT']);
                $taxable = new item('Taxable', $head['TAXABLE_AMOUNT']);
                $discount = new item('Discount', $head['DISCOUNT']);
                $tax = new item('Tax Amount', $head['TOTAL_VAT']);
                $billAmt = new item('Bill Amount', $head['BILL_AMT']);
                $paidAmt = new item('Paid Amount', $bill_header['amt_paid']);
                $balAmt = new item("Bal. Amount", $bill_header['amt_bal']);


                /* Date is kept the same for testing */
                $date = date('l jS \of F Y ') . date('H:i');
                //$date = "Monday 6th of April 2015 02:56:25 PM";

                $connector = null;
                $connector = new WindowsPrintConnector(printer);
                $printer = new Printer($connector);
                $logo = EscposImage::load(logo, false);

                /* Print top logo */
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->graphics($logo);

                /* Name of shop */
                $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer->text(company_name);
                $printer->selectPrintMode();
                $printer->setEmphasis(false);
                $printer->feed();
                $printer->text(company_country . ' , ' . company_city);
                $printer->feed();
                $printer->text("Mob : " . company_mob);

                /* Title of receipt */
                $printer->feed(2);
                $printer->setEmphasis(true);

                $sales_type = strtoupper($bill_header['sales_type']);
                $printer->text("$sales_type\n");

                $printer->feed(2);
                $printer->setEmphasis(false);
                $time = $bill_header['bill_time'];
                $date = $bill_header['bill_date'];
                $clerk = $bill_header['clerk'];
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text(new item("BILL # : $bill_number", $date));
                $printer->text(new item("CLERK : $clerk",  $time));
                $mech_no = $bill_header['mach_no'];
                $printer->text(new item("M# : $mech_no", ));

                $printer->feed();
                /* Items */
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setEmphasis(true);
                $printer->text(str_repeat('-', 48) . "\n");
                $printer->text(new item('No. Product', 'Amount'));
                $printer->text(str_repeat('-', 48) . "\n");
                $printer->setEmphasis(false);
                $printer->feed();
                foreach ($items as $item) {
                    $printer->text($item);
                }


                $printer->feed();
                $printer->text(str_repeat('-', 48) . "\n");
                /* Tax and total */
                $printer->text($non_taxable);
                $printer->text($taxable);
                $printer->text($discount);
                $printer->text($tax);
                $printer->text($billAmt);
                $printer->text($paidAmt);
                $printer->text($balAmt);
                $printer->feed();

                if($db_hander->row_count('loyalty_tran',"`billRef` = '$billRef'") === 1 ){
                    $customer = $db_hander->get_rows('loyalty_tran',"`billRef` = '$billRef'");
                    $printer->text(str_repeat('-', 48) . "\n");
                    $printer->text(new item('LOYALTY', ''));
                    $printer->text(str_repeat('-', 48) . "\n");
                    $printer->text(new item('CUSTOMER', $customer['cust_name']));
                    $printer->text(new item('POINTS BEFORE', number_format($customer['points_before'],2)));
                    $printer->text(new item('POINTS EARNED', number_format($customer['points_earned'],2)));
                    $printer->text(new item('POINTS STAND', number_format($customer['current_points'],2)));
                    $printer->feed();
                }



                $printer->text(str_repeat('-', 48) . "\n");
                $printer->text(new item('TAX DESC', 'TAX AMOUNT'));
                $printer->text(str_repeat('-', 48) . "\n");


                $nh = $db_hander->sum('bill_trans', 'nhis', "`billRef` = '$billRef'");
                $gf = $db_hander->sum('bill_trans', 'gfund', "`billRef` = '$billRef'");
                $cv = $db_hander->sum('bill_trans', 'covid', "`billRef` = '$billRef'");
                $vat = $db_hander->sum('bill_trans', 'vat', "`billRef` = '$billRef'");

                $nhil = new item('NHIL (2.5%)', $nh);
                $printer->text($nhil);
                $getf = new item('GETL (2.5%)', $gf);
                $printer->text($getf);
                $covid = new item('COVID (1%)', $cv);
                $printer->text($covid);
                $printer->text(str_repeat('-', 48) . "\n");
                $vat = new item('VAT (15%)', $vat);
                $printer->text($vat);

                if (evat === true) {

                    $evat_signatures = (new \billing\Evat())->get_signature($bill_header['billRef']);
                    $printer->text(str_repeat('-', 48) . "\n");
                    $printer->feed();
                    $printer->setUnderline(1);
                    $printer->text("SDC INFORMATION \n");
                    $printer->setUnderline(0);
                    $ysdcmrctim = $evat_signatures['ysdcmrctim'];
                    $printer->text("TIME SDC : $ysdcmrctim \n");

                    $ysdcid = $evat_signatures['ysdcid'];
                    $printer->text("SDC ID : $ysdcid \n");

                    $ysdcrecnum = $evat_signatures['ysdcrecnum'];
                    $printer->text("REC. NUMBER : $ysdcrecnum \n");

                    $ysdcintdata = $evat_signatures['ysdcintdata'];
                    $printer->text("INT. DATA: $ysdcintdata \n");

                    $ysdcregsig = $evat_signatures['ysdcregsig'];
                    $printer->text("REC. SIGN: $ysdcregsig \n");

                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->qrCode($evat_signatures['qr_code']);

                    $printer->feed();
                    $printer->setUnderline(1);

                }

                /* Footer */
                $printer->feed();
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("Thank you for shopping at ExampleMart\n");
                $printer->text("For trading hours, please visit example.com\n");
                $printer->feed(2);

                // $printer->text($date . "\n");
                // $printer->feed(1);

                $printer->setBarcodeHeight(80);
                $printer->setBarcodeWidth(5);
                $printer->barcode("$curRef", Printer::BARCODE_JAN13);
                $printer->setTextSize(2, 1);
                $printer->text("$curRef \n");


                /* Cut the receipt and open the cash drawer */
                $printer->cut();
                $printer->close();


            } else {
                //todo print no found bill
                printMessage("CANNOT FINE BILL");
            }

        }



    }

    function printzreport($recId)
    {
        $db_hander = new db_handler();
        $shiftCL = new shift();
        $date = today;
        
        $shift_count = $db_hander->row_count('shifts',"`recId` = '$recId'");
        $return = array(
            'status'=>false,"message"=>null
        );



        if(true)
        {
            

            //$shift = $db_hander->get_rows('shifts',"`recId` = '$recId'");
            $shift = $shiftCL->my_shift($recId);
            $mech = $shift['counter'];
            $shift_no = $shift['shift_no'];
            $start_date = $shift['shift_date'];
            $start_time = $shift['start_time'];
            $end_date = today;
            $end_time = date('H:i:s');
            
            $bill_cond = "mach_no = '$mech' and bill_date = '$date' and `shift` = '$shift_no'";
            
            $bills_count = $db_hander->row_count('bill_header',"mach_no = '$mech' and bill_date = '$date' and `shift` = '$shift_no'");
            if($bills_count > 0)
            {
                $connector = new WindowsPrintConnector(printer);
                $printer = new Printer($connector);

                // die(print_r($shift));
                // get all bills sum by payment
                $bill_hd_sql = "select  pmt_type, count(pmt_type) as 'pmt_count',sum(net_amt) as 'total' from bill_header where mach_no = '$mech' and bill_date = '$date' and  `shift` = '$shift_no' group by pmt_type";
                
                $bill_hd_stmt = $db_hander->db_connect()->query($bill_hd_sql);
                $bill_sum = (new  db_handler())->fetch_rows("select sum(net_amt) as gross,sum(tax_amt) as tax ,sum(gross_amt) as net from bill_header where mach_no = '$mech' and bill_date = '$date' and  `shift` = '$shift_no';");
                $subTotal = 0;
                $hd_arr = array();
                while($hd_row = $bill_hd_stmt->fetch(PDO::FETCH_ASSOC))
                {
                    $pmt_type = $hd_row['pmt_type'];
                    $total = $hd_row['total'];
                    $subTotal += $total;

                    array_push($hd_arr,new item($pmt_type,$total));
                }


                $logo = EscposImage::load(logo, false);

                /* Print top logo */
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> graphics($logo);

                /* Name of shop */
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text(company_name);
                $printer -> selectPrintMode();
                $printer -> setEmphasis(false);
                $printer -> feed();
                $printer -> text(company_country . ' , ' . company_city);
                $printer -> feed();
                $printer -> text("Mob : ".company_mob);

                $printer -> feed(2);
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                // header
                $printer->text("Shift No.: $shift_no");$printer->feed();
                $printer->text("Shift Start : $start_date $start_time");$printer->feed();
                $printer->text("Shift End : $end_date $end_time");$printer->feed();

                $printer->feed();

                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer->setUnderline(1);
                $printer->text("CASH REPORT Mach# ".mech_no);
                $printer->setUnderline(0);
                $printer->feed();

                /* Items */

                $printer -> setEmphasis(true);

                $printer -> text(new item('DESCRIPTION', 'AMOUNT'));
                $printer -> setEmphasis(false);
                foreach ($hd_arr as $item) {
                    $printer -> text($item);
                }

                $printer ->setEmphasis(true);
                $printer -> text(new item('Total',$bill_sum['gross']));
                $printer -> feed();

                // GROUP WISE with percentage report
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer->setUnderline(1);
                $printer->text("Department Sales Report Mach# ".mech_no);
                $printer->setUnderline(0);
                $printer->feed();

                $db_handler = new db_handler();
                $stmt = $db_handler->db_connect()->prepare("SELECT ig.shrt_name, SUM(bill_amt) AS total, ROUND((SUM(bill_amt) / (SELECT SUM(bill_amt) FROM bill_trans)) * 100, 2) AS percentage FROM bill_trans RIGHT JOIN prod_mast pm ON bill_trans.item_barcode = pm.barcode RIGHT JOIN item_group ig ON pm.item_grp = ig.id WHERE bill_trans.mach = ? AND DATE(bill_trans.date_added) = ? AND shift = ? GROUP BY ig.shrt_name HAVING SUM(bill_amt) IS NOT NULL OR SUM(bill_amt) > 0");
                $stmt->execute([$mech, $end_date,$shift_no]);
                $printer->text(new item('Department','Sales (%)'));
                $printer ->setEmphasis(false);
                while ($d_sales = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $shrt_name = $d_sales['shrt_name'];
                    $total = $d_sales['total'];
                    $percentage = $d_sales['percentage'];

                    $printer ->text(new item($shrt_name,"$total ($percentage)"));
                }
                // END GROUP WISE with percentage report

                //



                // print details
                /* Cut the receipt and open the cash drawer */
               
                

                $zserial = $db_hander->row_count("zserial","`mech_no` = '$mech'") + 1;
                $saleSummary = (new Billing())->MechSalesSammry($mech);
                $gross = $saleSummary['gross'];
                $deduct = $saleSummary['deduct'];
                $net = $saleSummary['net'];
                $day = today;
                $clerk_code = clerk_code;
                $zQuery = "insert into zserial(zSerial, mech_no, sales_date, clerk_code, shift_no, gross, deduction, net) VALUES 
                                                ('$zserial','$mech','$day','$clerk_code','$shift_no','$gross','$deduct','$net')";

                    // delete from bill_trans
                $del_bills = "delete from bill_header where mach_no = '$mech' and bill_date ='$day';
                    delete from bill_trans where mach = '$mech' and date_added = '$day';
                    delete from bill_tax_tran where mech_no = '$mech' and bill_date = '$day';";
                
                try {
                    $db_hander->exe($zQuery);
//                    $db_hander->db_connect()->exec($del_bills);
                    $db_hander->commit();
                    // END shift
                    (new shift())->end_shit($recId);
                    $return['status'] = true;
                    $return['message'] = "Z REPORT TAKEN";
                } catch (\Throwable $th) {
                    $return['status'] = $th->getMessage();
                }
                


                $printer -> cut();
                $printer -> pulse();
                $printer -> close();

            } else {
                printMessage("NO BILL HEADER FOR MACHINE #$mech on $date");
                $return['message'] = "NO BILL HEADER FOR MACHINE #$mech on $date";
            }



        }
        else {
            printMessage("NO OPEN SHIFT");
            $return['message'] = "NO OPEN SHIFT";
        }


        return $return;
    }

    // print sales report
    function printSales(){

        $db_hander = new db_handler();

        /* Fill in your own connector here */
        $sales_connector = new WindowsPrintConnector(printer);


        /* Information for the receipt */





        /* Date is kept the same for testing */
        $date = date('l jS \of F Y h:i:s A');
//$date = "Monday 6th of April 2015 02:56:25 PM";

        /* Start the printer */
        $logo = EscposImage::load(logo, false);
        $sales_printer = new Printer($sales_connector);

        /* Print top logo */
        $sales_printer -> setJustification(Printer::JUSTIFY_CENTER);
        $sales_printer -> graphics($logo);


        /* Title of receipt */
        $sales_printer -> setEmphasis(true);
        $sales_printer -> text("SALES REPORT\n");
        $sales_printer -> setEmphasis(false);

        $sales_printer -> feed();

        # get machines
        $machines = $db_hander->db_connect()->query("SELECT mech_no FROM mech_setup");

        while ($machine = $machines->fetch(PDO::FETCH_ASSOC)){
            $t = 0;
            $m_no = $machine['mech_no'];

            $sales_printer -> setJustification(Printer::JUSTIFY_LEFT);
            $sales_printer -> setEmphasis(true);
            $sales_printer -> text("MECH #$m_no");
            $sales_printer->feed(2);
            $sales_printer -> setEmphasis(false);


            # get sales for machine
            $mach_sales_query = "select mech_setup.mech_no,mech_setup.descr, bill_header.pmt_type, sum(bill_header.gross_amt) as 'gross', sum(bill_header.tax_amt) as 'tax', sum(bill_header.net_amt) as 'net' from mech_setup join bill_header on bill_header.mach_no = mech_setup.mech_no where mech_no = '$m_no' group by bill_header.pmt_type, mech_setup.mech_no, mech_setup.descr;";
            // 0.
            $m_sales = $db_hander->db_connect()->query($mach_sales_query);
            while ($m_sale = $m_sales->fetch(PDO::FETCH_ASSOC)){

                $pmt_type = $m_sale['pmt_type'];
                $total = $m_sale['net'];
                $sales_printer->setUnderline(1);
                $sales_printer -> text(new item($pmt_type,$total));
                $sales_printer->feed(1);


            }
            $t = $db_hander->sum('bill_header',"net_amt","`mach_no` = '$m_no'");
            $sales_printer -> setEmphasis(true);
            $sales_printer -> text(new item("TOTAL",number_format($t,2)));
            $sales_printer->setEmphasis(false);
            $sales_printer->setUnderline(0);
        }


        $sales_printer -> feed();




        $sales_printer -> text($date . "\n");

        /* Cut the receipt and open the cash drawer */
        $sales_printer -> cut();
        $sales_printer -> pulse();

        $sales_printer -> close();


        printCut();



    }

    function print_eod($sales_date){
        # get all mech in shift
        $db = new db_handler();
        $sh = (new shift());
        $shifts = $sh->shifts('*',$sales_date);

        if($shifts['code'] === 200){
            // printer connection
            $eod_connector = new WindowsPrintConnector(printer);
            $eod_printer = new Printer($eod_connector);

            $logo = EscposImage::load(logo, false);

            /* Print top logo */
            $eod_printer -> setJustification(Printer::JUSTIFY_CENTER);
            $eod_printer -> graphics($logo);

            /* Name of shop */
            $eod_printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $eod_printer -> text(company_name);
            $eod_printer -> selectPrintMode();
            $eod_printer -> setEmphasis(false);
            $eod_printer -> feed();
            $eod_printer -> text(company_country . ' , ' . company_city);
            $eod_printer -> feed();
            $eod_printer -> text("Mob : ".company_mob);
            $eod_printer->feed(2);
            $eod_printer->text("EOD REPORT");

            $eod_printer -> feed(2);
            $eod_printer -> setJustification(Printer::JUSTIFY_LEFT);
            // header


            $eod_printer->feed();





            // there are shifts
            $sfts = $shifts['message']['trans'];
            for ($i = 0; $i < count($sfts); $i++) {
                $sft = $sfts[$i];

                $recId = $sft['recId'];
                $this_shift = $sh->my_shift($recId);
                $mech_no = $this_shift['counter'];
                $shift_no = $this_shift['shift_no'];
                $st = $this_shift['shift_date'] . " " . $this_shift['start_time'];
                $end = $this_shift['end_date'] . " " . $this_shift['end_time'];

                $eod_printer->text("Mech No.: $mech_no");
                $eod_printer->feed();
                $eod_printer->text("Clerk: NOT SET");$eod_printer->feed();
                $eod_printer->text("Shift No.: $shift_no");$eod_printer->feed();
                $eod_printer->text("Shift Start : $st");
                $eod_printer->feed();
                $eod_printer->text("Shift End : $end");$eod_printer->feed();

                $pmts_sql = "CALL ReportPaymentSummary($mech_no,'$sales_date',$shift_no);";
                $pmt_stmt = $db->db_connect()->prepare($pmts_sql);
                $pmt_stmt->execute();
                $eod_printer->text(str_repeat('-', 48) . "\n");
                $eod_printer->text((new item("PAY METHOD","AMT")));
                $eod_printer->text(str_repeat('-', 48) . "\n");
                while($payment = $pmt_stmt->fetch(PDO::FETCH_ASSOC)){
                    $eod_printer->text((new item($payment['pmt_type'],$payment['total'])));
                }
                $eod_printer->text(str_repeat('-', 48) . "\n");
                $eod_printer->text((new item("GROSS",0.00)));
                $eod_printer->text((new item("TAX",0.00)));
                $eod_printer->text((new item("NET",0.00)));


                // get payments for machine

                $eod_printer->text(str_repeat('-', 48) . "\n");

                $eod_printer->feed();



            }

            # print summary
            $sh_sum = $shifts['message']['summary'];
            $eod_printer->text((new item("NON TAXABLE",$sh_sum['non_taxable_amt'])));
            $eod_printer->text((new item("TAXABLE",$sh_sum['taxable_amt'])));
            $eod_printer->text((new item("TAX",$sh_sum['tax_amt'])));
            $eod_printer->text((new item("GROSS",$sh_sum['gross'])));
            $eod_printer->text((new item("NET",$sh_sum['net'])));

            $eod_printer -> cut();
            /* Close printer */
            $eod_printer -> close();



        } else {
            // no shift
            printMessage("CANNOT PRINT EOD ${shifts['message']}");
        }


    }