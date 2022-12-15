<?php

namespace billing;

class reports extends \db_handeer\db_handler
{
    private array $response = ['code'=>000,'message'=>000];

    public function json_enc($data){


        return json_encode($data);
    }
    public function eod(){
        $this->response['code'] = 200;
        $this->response['message'] = 'Eod Printed. please check printer for print our';
        echo $this->json_enc($this->response);
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
