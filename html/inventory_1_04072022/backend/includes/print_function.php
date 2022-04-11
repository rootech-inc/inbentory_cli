<?php
    /* Fill in your own connector here */
    require '../print_config/vendor/autoload.php';
    use Mike42\Escpos\Printer;
    use Mike42\Escpos\EscposImage;
    use Mike42\Escpos\PrintConnectors\FilePrintConnector;
    $connector = new FilePrintConnector("/dev/usb/lp0");
    $connector = new FilePrintConnector("/dev/usb/lp0");
    function print_bill()
    {


        /* Information for the receipt */
        $items = array(
            new item("Example item #1 Example", "4.00"),
            new item("Another thing", "3.50"),
            new item("Something else", "1.00"),
            new item("A final item", "4.45"),
        );
        $subtotal = new item('Subtotal', '12.95');
        $tax = new item('A local tax', '1.30');
        $total = new item('Total', '14.25', true);
        /* Date is kept the same for testing */
        $date = date('l jS \of F Y h:i:s A');
//$date = "Monday 6th of April 2015 02:56:25 PM";

        /* Start the printer */
        $logo = EscposImage::load("/backend/print_config/example/resources/escpos-php.png", false);
        $printer = new Printer($connector);

        /* Print top logo */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> graphics($logo);

        /* Name of shop */
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text("ExampleMart Ltd.\n");
        $printer -> selectPrintMode();
        $printer -> text("Shop No. 42.\n");
        $printer -> feed();

        /* Title of receipt */
        $printer -> setEmphasis(true);
        $printer -> text("SALES INVOICE\n");
        $printer -> setEmphasis(false);

        /* Items */
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> setEmphasis(false);
        $printer -> text(new item('', '$'));
        $printer -> setEmphasis(false);
        foreach ($items as $item) {
            $printer -> text($item);
        }
        $printer -> setEmphasis(true);
        $printer -> text($subtotal);
        $printer -> setEmphasis(false);
        $printer -> feed();

        /* Tax and total */
        $printer -> text($tax);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text($total);
        $printer -> selectPrintMode();

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


    }