<?php

namespace billing;

class reports extends \db_handeer\db_handler
{
    private array $response = ['code'=>000,'message'=>000];

    public function json_enc($data){


        return json_encode($data);
    }
    public function eod(){
        $day = today;
        // check if there is a machine with an open shift
        if($this->row_count('shifts',"`shift_date` = '$day' AND `end_time` = NULL") > 0 )
        {
            // copy bill header into bill_history_header
            $bill_header_sql = "SELECT mach_no, clerk, bill_no, pmt_type, gross_amt, tax_amt, disc_rate, net_amt, bill_date, bill_time, tran_qty, amt_paid, amt_bal FROM bill_header";
            if($this->delete('bill_history_header',"`bill_date` = '$day'"))
            {
                $bill_header_stmt = $this->db_connect()->prepare($bill_header_sql);
                $bill_header_stmt->execute();

                while ($header = $bill_header_stmt->fetch(\PDO::FETCH_ASSOC)){
                    $history_header_sql = "INSERT INTO bill_history_header (mach_no, clerk, bill_no, pmt_type, gross_amt, tax_amt, disc_rate, net_amt, bill_date, bill_time, tran_qty, amt_paid, amt_bal)  
                    values (?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $history_header_stmt = $this->db_connect()->prepare($history_header_sql);
                    $history_header_stmt->bindColumn($header);
                    $history_header_stmt->execute();
                }

            }

            // copy bill trans into history trans

            // print bill

            // clear all from bill trans

            $this->response['code'] = 200;
            $this->response['message'] = 'Eod Printed. please check printer for print our';
        } else {
            $this->response['code'] = 403;
            $this->response['message'] = 'Please close all counters before taking EOD';
        }




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
