<?php

namespace billing;

use db_handeer\db_handler;

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

            $bill_header_sql = "INSERT INTO history_header SELECT * FROM bill_header;";
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

    public function z_report($recId): array
    {
//
        $record = $db->get_rows('shifts',"`recId` = '$recId'");
        $day = $record['shift_date'];
        $mech_no = $record['mech_no'];
        $shift = $record['shift_no'];

        if($this->row_count('mech_setup',"`mech_no` = '$mech_no'") === 1)
        {

            if($this->row_count('shifts',"`shift_date` = '$day' AND `end_time` is null and `mech_no` = '$mech_no'") === 1){

                try {
                    //todo:: print z report
                    $invalidQuery = "DELETE from bill_trans where mach = '$mech_no' and bill_number not in (select bill_no  from bill_header group by bill_no)";
                    $hist_header = "INSERT INTO history_header SELECT * FROM bill_header where mach_no = '$mech_no'";
                    $hist_tran = "INSERT INTO history_trans SELECT * FROM bill_trans where mach = '$mech_no'";
                    $hist_tax = "INSERT INTO history_tax_tran SELECT * FROM bill_tax_tran where mech_no = '$mech_no'";

                    $this->exe($invalidQuery);
                    (new \anton())->log2file($hist_header);
                    $this->db_connect()->exec($hist_header);
                    (new \anton())->log2file($hist_tran);
                    $this->db_connect()->exec($hist_tran);
                    (new \anton())->log2file($hist_tax);
                    $this->db_connect()->exec($hist_tax);
//                $this   ->db_connect()->exec("UPDATE shifts set end_time = CURTIME() where mech_no = '$mech_no' and shift_date = '$day' ");

                    $zserial = $db->row_count("zserial","`mech_no` = '$mech_no'") + 1;
                    $saleSummary = (new Billing())->MechSalesSammry($mech_no);
                    $gross = $saleSummary['gross'];
                    $deduct = $saleSummary['deduct'];
                    $net = $saleSummary['net'];



                    // z serial
                    $clerk_code = clerk_code;
                    $zQuery = "insert into zserial(zSerial, mech_no, sales_date, clerk_code, shift_no, gross, deduction, net) VALUES 
                                                ('$zserial','$mech_no','$day','$clerk_code','$shift','$gross','$deduct','$net')";

                    // delete from bill_trans
                    $del_bills = "delete from bill_header where mach_no = '$mech_no' and bill_date ='$day';
                    delete from bill_trans where mach = '$mech_no' and date_added = '$day';
                    delete from bill_tax_tran where mech_no = '$mech_no' and bill_date = '$day';";




                    $db->exe($zQuery);
                    $db->db_connect()->exec($del_bills);
                    $db->commit();
                    // END shift
                    (new shift())->end_shit($recId);
                    $this->response['code'] = 202;
                    $this->response['message'] = "Z-Report Taken";
                } catch (\Exception  $e){


                    // delete
                    $deleteQ = "delete from history_header where mach_no = '$mech_no' and bill_date ='$day';
                    delete from history_trans where mach = '$mech_no' and date_added = '$day';
                    delete from history_tax_tran where mech_no = '$mech_no' and bill_date = '$day';
                    delete from zserial where mech_no = '$mech_no' and sales_date = '$day';";
                                        $db->db_connect()->exec($deleteQ);
                    $this->response['code'] = $e->getCode();
                    $this->response['message'] = $e->getMessage() . " LINE : ".$e->getLine();

                }
            }

            else
            {
                $this->response['code'] = 404;
                $this->response['message'] = "No active shift for machine" . $this->row_count('shifts',"`shift_date` = '$day' AND `end_time` is null and `mech_no` = '$mech_no'");
            }



        } else {
            $this->response['code'] = 505;
            $this->response['message'] = "invalid machine number ($mech_no)";
        }

        return $this->response;
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
