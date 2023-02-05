<?php

require __DIR__ . '/../../escpos/vendor/autoload.php';

use db_handeer\db_handler;
use mechconfig\MechConfig;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;




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
            $rightCols = 10;
            $leftCols = 38;
            if ($this -> dollarSign) {
                $leftCols = $leftCols / 2 - $rightCols / 2;
            }
            $left = str_pad($this -> name, $leftCols) ;

            $sign = ($this -> dollarSign ? '$ ' : '');
            $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
            return "$left$right\n";
        }
    }

    function printbill($mech_no,$bill_number,$payment = 'payment'){
        $connector = new WindowsPrintConnector("POS");

        $today = date('Y-m-d');
        $bill_hd_count = "`bill_date` = '$today' and `mach_no` = '$mech_no' and `bill_no` = '$bill_number'";
        if((new db_handler())->row_count('bill_header',$bill_hd_count) > 0)
        {
            $bill_header = (new db_handler())->get_rows('bill_header',$bill_hd_count);
            $payment = $bill_header['pmt_type'];
            $bill_total = (new \billing\Billing())->billTotal($bill_number,$today);
            $tran_qty = $bill_total['tran_qty'];
            $taxable_amt = number_format($bill_total['taxable_amt'],2);
            $tax_amt = number_format($bill_total['tax_amt'],2);
            $bill_amt = number_format($bill_total['bill_amt'],2);
            $amt_paid = number_format($bill_total['amt_paid'],2);
            $amt_bal = number_format($bill_total['amt_bal'],2);

            $items = array(

            );

            $bill_trans_count = "`date_added` = '$today' and `mach` = '$mech_no' and `bill_number` = '$bill_number' and `trans_type` = 'i'";
            $bill_sql = (new db_handler())->db_connect()->query("SELECT * FROM bill_trans WHERE $bill_trans_count");
            $billSn = 0;
            while ($row = $bill_sql->fetch(PDO::FETCH_ASSOC))
            {
                $billSn++;
                $item_qty = $row['item_qty'];
                $item_barcode = $row['item_barcode'];
                if($billSn > 9)
                {
                    $bq = "   $item_barcode QTY : $item_qty";
                } else
                {
                    $bq = "  $item_barcode QTY : $item_qty";
                }

                $item_name = $row['item_desc'];
                $item_desc = "$billSn $item_name";
                $price = number_format($row['bill_amt'] ,2);
                array_push($items,new item($item_desc,$price)); // push item desc and cost
                array_push($items,new item($bq,'')); // push barcode Quantity
            }

            $subtotal = new item('Subtotal', '12.95');

            $taxable = new item('Taxable Amount', $taxable_amt);
            $tax = new item('Tax Amount', $tax_amt);
            $billAmt = new item('Bill Amount', $bill_amt);
            $paidAmt = new item('Paid Amount', $amt_paid);
            $balAmt = new item("Bal. Amount",$amt_bal);

            $nhil = new item('NHIL (2.5%)', '1.30');
            $getf = new item('GETL (2.5%)', '1.30');
            $covid = new item('COVID (1%)', '1.30');
            $total = new item('TAXABLE AMT', '1.30');
            $vat = new item('VAT (21.9%)', '1.30');
            /* Date is kept the same for testing */
            $date = date('l jS \of F Y h:i:s A');
            //$date = "Monday 6th of April 2015 02:56:25 PM";

            $printer = new Printer($connector);
            $printer -> close();
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

            /* Title of receipt */
            $printer -> feed(2);
            $printer -> setEmphasis(true);
            if($payment === 'refund')
            {
                $printer -> text("REFUND\n");
            } else {
                $printer -> text("TAX INVOICE\n");
            }
            $printer -> feed(2);
            $printer -> setEmphasis(false);

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer -> text("Bill# $bill_number");
            $printer->feed();
            $printer -> text("M# $mech_no");
            $printer ->feed();
            $printer -> text("Admin");
            $printer -> feed();

            /* Items */
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer -> setEmphasis(true);
            $printer -> text(new item('', '$'));
            $printer -> setEmphasis(false);
            foreach ($items as $item) {
                $printer -> text($item);
            }

            $printer -> feed();

            /* Tax and total */
            $printer -> text($taxable);
            $printer -> text($tax);
            $printer -> text($billAmt);
            $printer -> text($paidAmt);
            $printer -> text($balAmt);
            $printer -> feed();

            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text("TAX BREAKDOWN\n");
            $printer -> selectPrintMode();

            $printer -> text($nhil);
            $printer -> text($getf);
            $printer -> text($covid);
            $printer -> feed();
            $printer->setUnderline(1);

            /* Footer */
            $printer -> feed(2);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("Thank you for shopping at ExampleMart\n");
            $printer -> text("For trading hours, please visit example.com\n");
            $printer -> feed(2);
            $printer -> text($date . "\n");

            /* Cut the receipt and open the cash drawer */
            $printer -> cut();
            $printer -> pulse();
            $printer -> close();


        } else {
            //todo print no found bill
        }



    }

    function printzreport($recId)
    {
        $connector = new WindowsPrintConnector("POS");
        $printer = new Printer($connector);
        $shift_count = (new db_handler())->row_count('shifts',"`recId` = '$recId'");
        if($shift_count === 1)
        {
            $shift = (new db_handler())->get_rows('shifts',"`recId` = '$recId'");
            $mech = $shift['mech_no'];
            $date = $shift['shift_date'];

            $bills_count = (new db_handler())->row_count('bill_header',"mach_no = '$mech' and bill_date = '$date'");
            if($bills_count > 0)
            {
                // get all bills sum by payment
                $bill_hd_sql = "select  pmt_type, count(pmt_type) as 'pmt_count',sum(net_amt) as 'total' from bill_header group by pmt_type where mach_no = '$mech' and bill_date = '$date'";
                $bill_hd_stmt = (new db_handler())->db_connect()->query($bill_hd_sql);
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

                $printer -> feed();

                /* Items */
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> setEmphasis(true);
                $printer -> text(new item('', '$'));
                $printer -> setEmphasis(false);
                foreach ($hd_arr as $item) {
                    $printer -> text($item);
                }

                $printer ->setEmphasis(true);
                $printer -> text(new item('Subtotal',$subTotal));
                $tax = (new db_handler())->sum('bill_tax_tran','tax_amt',"`bill_date` = '$date' and `mech_no` = '$mech'");
                $printer -> text(new item('Tax',number_format($tax,2)));
                $printer -> text(new item('Total',number_format($subTotal-$tax,2)));



                // print details
            } else {
                $printer -> text("NO BILL HEADER FOR MACHINE #$mech on $date");
            }



        } else {
            $printer ->text("NO OPEN SHIFT");
        }


        /* Cut the receipt and open the cash drawer */
        $printer -> cut();
        $printer -> pulse();
        $printer -> close();
    }

//    printbill('1','45');
