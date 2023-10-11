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

    function printbill($mech_no,$bill_number,$payment = 'payment'){

        if(bill_print) {


            $db_hander = new db_handler();

            $today = today;
            $bill_hd_count = "`bill_date` = '$today' and `mach_no` = '$mech_no' and `bill_no` = '$bill_number'";
            if ($db_hander->row_count('bill_header', $bill_hd_count) > 0) {

                $bill_header = $db_hander->get_rows('bill_header', $bill_hd_count);
                $curRef = $bill_header['billRef'];
                $billRef = $bill_header['billRef'];
                $billSummary = (new Billing())->billSummaryV2($billRef);


                $payment = $bill_header['pmt_type'];
                $bill_total = (new Billing())->billTotal($bill_number, $today);
                $tran_qty = $bill_total['tran_qty'];
                $taxable_amt = number_format($bill_total['taxable_amt'], 2);
                $tax_amt = number_format($bill_total['tax_amt'], 2);
                $bill_amt = number_format($bill_total['bill_amt'], 2);
                $amt_paid = number_format($bill_header['amt_paid'], 2);
                $amt_bal = number_format($bill_header['amt_bal'], 2);

                $items = array();

                $bill_trans_count = "`date_added` = '$today' and `mach` = '$mech_no' and `bill_number` = '$bill_number' and `trans_type` = 'i'";
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
                    
                    array_push($items, new item(substr($item_desc, 0, 30), $price)); // push item desc and cost
                    array_push($items, new item($bq, '')); // push barcode Quantity
                }

                $subtotal = new item('Subtotal', '12.95');

                $non_taxable = new item('Non-Taxable Amount', $bill_header['non_taxable_amt']);
                $taxable = new item('Taxable Amount', $bill_header['taxable_amt']);
                $tax = new item('Tax Amount', $bill_header['tax_amt']);
                $billAmt = new item('Bill Amount', $bill_header['gross_amt']);
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
                if ($payment === 'refund') {
                    $printer->text("REFUND\n");
                } else {
                    $printer->text("TAX INVOICE\n");
                }
                $printer->feed(2);
                $printer->setEmphasis(false);

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("Bill# $bill_number");
                $printer->feed();
                $printer->text(new item(date('d-M-Y'), 'TIME : ' . date('H:i')));

                $printer->text(new item('M# : 6', 'Clerk : Admin'));

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
                $printer->text($tax);
                $printer->text($billAmt);
                $printer->text($paidAmt);
                $printer->text($balAmt);
                $printer->feed();


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

                $printer->text($date . "\n");
                // $printer->feed(1);

                // $printer->setBarcodeHeight(80);
                // $printer->setBarcodeWidth(5);
                // $printer->barcode("$curRef", Printer::BARCODE_JAN13);
                // $printer->setTextSize(2, 1);
                // $printer->text("$curRef \n");


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
                    $db_hander->db_connect()->exec($del_bills);
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
        $connector = new WindowsPrintConnector(printer);


        /* Information for the receipt */





        /* Date is kept the same for testing */
        $date = date('l jS \of F Y h:i:s A');
//$date = "Monday 6th of April 2015 02:56:25 PM";

        /* Start the printer */
        $logo = EscposImage::load(logo, false);
        $printer = new Printer($connector);

        /* Print top logo */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> graphics($logo);


        /* Title of receipt */
        $printer -> setEmphasis(true);
        $printer -> text("SALES REPORT\n");
        $printer -> setEmphasis(false);

        $printer -> feed();

        # get machines
        $machines = $db_hander->db_connect()->query("SELECT mech_no FROM mech_setup");

        while ($machine = $machines->fetch(PDO::FETCH_ASSOC)){
            $t = 0;
            $m_no = $machine['mech_no'];

            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer -> setEmphasis(true);
            $printer -> text("MECH #$m_no");
            $printer->feed(2);
            $printer -> setEmphasis(false);


            # get sales for machine
            $mach_sales_query = "select mech_setup.mech_no,mech_setup.descr, bill_header.pmt_type, sum(bill_header.gross_amt) as 'gross', sum(bill_header.tax_amt) as 'tax', sum(bill_header.net_amt) as 'net' from mech_setup join bill_header on bill_header.mach_no = mech_setup.mech_no where mech_no = '$m_no' group by bill_header.pmt_type, mech_setup.mech_no, mech_setup.descr;";
            (new anton())->log2file($mach_sales_query);
            $m_sales = $db_hander->db_connect()->query($mach_sales_query);
            while ($m_sale = $m_sales->fetch(PDO::FETCH_ASSOC)){

                $pmt_type = $m_sale['pmt_type'];
                $total = $m_sale['net'];
                $printer->setUnderline(1);
                $printer -> text(new item($pmt_type,$total));
                $printer->feed(1);


            }
            $t = $db_hander->sum('bill_header',"net_amt","`mach_no` = '$m_no'");
            $printer -> setEmphasis(true);
            $printer -> text(new item("TOTAL",number_format($t,2)));
            $printer->setEmphasis(false);
            $printer->setUnderline(0);
        }


        $printer -> feed();




        $printer -> text($date . "\n");

        /* Cut the receipt and open the cash drawer */
        $printer -> cut();
        $printer -> pulse();

        $printer -> close();


        printCut();



    }
