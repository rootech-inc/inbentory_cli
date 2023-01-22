<?php

require __DIR__ . '/../../escpos-php/vendor/autoload.php';

use db_handeer\db_handler;
use mechconfig\MechConfig;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;



/* A wrapper to do organise item names & prices into columns */
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

    function printbill($mech_no,$bill_number){
        $connector = new WindowsPrintConnector("POS");$connector = new WindowsPrintConnector("POS");
        $printer = new Printer($connector);

        $today = date('Y-m-d');
        $bill_hd_count = "`bill_date` = '$today' and `mach_no` = '$mech_no' and `bill_no` = '$bill_number'";
        if((new db_handler())->row_count('bill_header',$bill_hd_count) > 0)
        {
            $bill_header = (new db_handler())->get_rows('bill_header',$bill_hd_count);
            $bill_total = (new \billing\Billing())->billTotal($bill_number,$today);
            $tran_qty = $bill_total['tran_qty'];
            $taxable_amt = number_format($bill_total['taxable_amt'],2);
            $tax_amt = number_format($bill_total['tax_amt'],2);
            $bill_amt = number_format($bill_total['bill_amt'],2);
            $amt_paid = number_format($bill_total['amt_paid'],2);
            $amt_bal = number_format($bill_total['amt_bal'],2);

            $items = array(

            );

            $bill_trans_count = "`date_added` = '$today' and `mach` = '$mech_no' and `bill_number` = '$bill_number'";
            $bill_sql = (new db_handler())->db_connect()->query("SELECT * FROM bill_trans WHERE $bill_trans_count");

            while ($row = $bill_sql->fetch(PDO::FETCH_ASSOC))
            {
                $item_qty = $row['item_qty'];
                $item_barcode = $row['item_barcode'];
                $item_name = $row['item_desc'];
                $item_desc = "$item_qty X $item_name - $item_barcode";
                $price = $row['retail_price'];
                $item = new item($item_desc,$price);
                array_push($items,$item);
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
            $printer -> text("TAX INVOICE\n");
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
            // no print
        }

    }

//    printbill('1','45');
