<?php

namespace billing;

class reports extends \db_handeer\db_handler
{
    public function eod(){
        print_r('Hello World');
    }

    public function print_report(string $report_type)
    {
        if($report_type === 'eod')
        {
            $this->eod();
        }
    }
}
// make class objects
$Reports = new reports();
