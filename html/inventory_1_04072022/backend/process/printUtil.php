<?php
    require $_SERVER['DOCUMENT_ROOT'] . "backend/process/print_config/vendor/autoload.php";
    use Mike42\Escpos\PrintConnectors\FilePrintConnector;
    use Mike42\Escpos\Printer;
    $connector = new FilePrintConnector("/dev/usb/lp0");
    $printer = new Printer($connector);
    $printer -> text("Hello World!\n");
    $printer -> cut();
    $printer -> close();